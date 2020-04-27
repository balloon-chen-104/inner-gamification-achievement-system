<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toggleSwitch.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cross.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pieChart.css') }}" rel="stylesheet">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

    <style>
        .dropdown-menu li:hover .sub-menu {
            visibility: visible;
            transition: all .8s linear;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
            transition: all .8s linear;
        }
    </style>
</head>
<body>
    <div class="wrapper" id="app">
        @guest
        @else
            <!-- Sidebar  -->
            <nav id="sidebar" class="">
                <div class="sidebar-header">
                    <h3>群組</h3>
                </div>

                <ul class="list-unstyled components">
                    @foreach (Auth::user()->groups as $group)
                        <li @if($group->id == Auth::user()->active_group) class="active" @endif>

                            {{-- Check if user is admin --}}
                            @if($group->id == Auth::user()->active_group)
                                @php
                                    if($group->pivot->authority){
                                        $isAdmin = true;
                                    } else{
                                        $isAdmin = false;
                                    }
                                @endphp
                            @endif

                            <a id="group_{{ $group->id }}" href="#"
                               onclick="event.preventDefault();
                                        document.getElementById('active-group-form-{{ $group->id }}').submit();">
                                {{ $group->name }}
                            </a>

                            <form id="active-group-form-{{ $group->id }}" action="/users/{{Auth::user()->id}}" method="POST" style="display: none;">
                                <input type="text" name="active_group" value="{{ $group->id }}">
                                @method('PUT')
                                @csrf
                            </form>
                        </li>
                    @endforeach
                </ul>

                <ul class="list-unstyled CTAs">
                    @guest
                    @else
                        <li>
                            <a href="#" class="download" onclick="$('#addGroupModalCenter').modal('toggle')">建立群組</a>
                            <a href="#" class="download" onclick="$('#enterGroupModalCenter').modal('toggle')">加入群組</a>
                        </li>
                        <li>
                            <a class="article" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('登出') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </nav>
        @endguest

        <!-- Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <ul class="navbar-nav ml-auto">
                        @guest
                        @else
                            <li class="nav-item mr-3">
                                <button type="button" id="sidebarCollapse" class="btn btn-secondary">
                                    <i class="fas fa-align-left"></i>
                                    {{-- <span>Toggle Sidebar</span> --}}
                                </button>
                                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <i class="fas fa-align-justify"></i>
                                </button>
                            </li>
                        @endguest
                        <li class="nav-item">
                            <a class="navbar-brand" href="{{ url('/') }}">
                                {{ config('app.name', 'Laravel') }}
                            </a>
                        </li>
                    </ul>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('登入') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('註冊') }}</a>
                                    </li>
                                @endif
                            @else
                                @if (isset($isAdmin))
                                    <li class="nav-item">
                                        <a class="nav-link" href="/bulletin">{{ __('佈告欄') }}</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{{ __('任務') }}</a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{route('task.index')}}">所有任務</a>
                                            <a class="dropdown-item" href="{{route('task.history')}}">過去的任務</a>
                                            <div class="dropdown-divider"></div>
                                            @if($isAdmin)
                                                <a class="dropdown-item" href="{{route('task.edit')}}">新增／修改任務</a>
                                                <a class="dropdown-item" href="#">審核員工完成任務</a>
                                            @else
                                                <a class="dropdown-item" href="#">提案新任務</a>
                                            @endif

                                    </li>
                                    @if ($isAdmin)
                                        <li class="nav-item">
                                            <a class="nav-link" href="/leaderboard">{{ __('成員數據') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/setting">{{ __('設定') }}</a>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a class="nav-link" href="/leaderboard">{{ __('排行榜') }}</a>
                                        </li>
                                    @endif
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" href="/profile">{{ Auth::user()->name }}</a>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @auth
                <input type="text" id="api-token" value="{{Auth::user()->api_token}}" style="display:none">
                @endauth
                @yield('content')
            </main>
        </div>

    </div>
    @auth
    @include('inc.addGroup')
    @include('inc.enterGroup')
    @include('inc.updateApiToken')
    @endauth
    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>

    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'bulletin-ckeditor' );
    </script>

</body>
</html>
