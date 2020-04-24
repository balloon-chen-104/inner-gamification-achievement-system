@extends('layouts.app')

@section('content')

@foreach ($data['flash_messages'] as $bulletin)
    <div class="alert alert-success ml-5 mr-5">{{ $bulletin->content }}
        @if ($data['autority'])
            <a href="#" class="close" onclick="event.preventDefault();document.getElementById('flash_message_switch-form-{{ $bulletin->id }}').submit();"></a>
        @endif
    </div>

    <form id="flash_message_switch-form-{{ $bulletin->id }}" action="/setting/{{$bulletin->id}}/updateFlashMessage" method="post">
        <input type="hidden" name="flash_message_switch" value="bulletin">
        @method('PUT')
        @csrf
    </form>
@endforeach

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">公告</div>
                <div class="card-body">
                    
                    @if (count($data['announcements']) == 0)
                        <p>尚無公告</p>
                    @else
                        @foreach ($data['announcements'] as $announcement)
                            <div class="card card-body mb-2">
                                <p class="text-dark">{!! $announcement->content !!}</p>
                                @if ($data['autority'])
                                    <form action="/bulletin/{{ $announcement->id }}" method="post">
                                        <a href="/bulletin/{{ $announcement->id }}/edit" class="btn btn-secondary btn-sm">編輯</a>&nbsp
                                        <input type="submit" class="btn btn-outline-danger btn-sm" value="刪除">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    
                    <br>
                    @if ($data['autority'])
                        <div class="d-flex justify-content-center">
                            <a href="/bulletin/create" class="btn btn-secondary btn-sm">新增公告</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    p {
        color: black;
    }
</style>

@endsection
