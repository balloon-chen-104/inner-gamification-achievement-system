@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="alert alert-my-color" id="success-msg" role="alert" style="display:none"></div>
            <div class="alert alert-danger" id="error-msg" role="alert" style="display:none"></div>
            @php
                $group = \App\Group::find(Auth::user()->active_group);
                $isAdmin = false;
                foreach($group->users as $user) {
                    if($user->id == Auth::user()->id && $user->pivot->authority == 1) {
                        $isAdmin = true;
                    }
                }
                $categories = $group->categories;
            @endphp
            @if ($isAdmin && $categories->count() == 0)
            <div class="card mb-3">
                <div class="card-header" style="cursor: pointer" onclick="toggleCategory()">新增任務種類</div>
                <div class="card-body" id="add-category" style="display:none">
                    <form id="add-category-form">
                        <div class="form-group">
                            <label for="category-name">種類名稱</label>
                            <input type="text" class="form-control" id="category-name">
                        </div>
                        <button type="submit" class="btn btn-primary">新增</button>
                    </form>
                </div>
            </div>
            <script>
                function toggleCategory() {
                    $('#add-category').slideToggle();
                }
            </script>
            @endif
            <div class="card mb-3">
                <div class="card-header">任務欄</div>
                <div class="card-body" id="task-card">
                    <div class="float-right input-group input-group-sm mb-3" style="width:23ch">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="catInputGroupSelect">任務種類</label>
                        </div>
                        <select class="custom-select" name="" id="catInputGroupSelect">
                            @if ($categories->count() > 0)
                                <option value="-1" selected>全部（預設）</option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            @else
                                <option value="0" selected>尚無任務種類</option>
                            @endif
                        </select>
                    </div>
                    {{-- <div class="float-right input-group input-group-sm mb-3 mr-3" style="width:23ch">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="dateInputGroupSelect">任務排序</label>
                        </div>
                        <select class="custom-select" name="" id="dateInputGroupSelect">
                        @if ($tasks->count() > 0)
                            <option value="-1" selected>預設</option>
                            <option value="">按到期日</option>
                            <option value="">按任務新增日</option>
                            <option value="">按任務分數</option>
                        @else
                            <option value="0" selected>尚無任務</option>
                        @endif
                        </select>
                    </div> --}}
                    @php
                        $todayNotReportedTasks = $tasks->get('todayTasks')->filter(function($task) {
                            if($task->users()->where('users.id', Auth::user()->id)->first() == NULL){
                                return true;
                            }
                            return false;
                        });
                        $otherNotReportedTasks = $tasks->get('otherTasks')->filter(function($task) {
                            if($task->users()->where('users.id', Auth::user()->id)->first() == NULL){
                                return true;
                            }
                            return false;
                        })
                    @endphp
                    @if ($todayNotReportedTasks->count() > 0 || $otherNotReportedTasks->count() > 0)
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">任務名</td>
                                    <th scope="col">敘述</td>
                                    <th scope="col">分數</td>
                                    <th scope="col">到期日</td>
                                    <th scope="col">剩餘次數</td>
                                    <th scope="col">完成回報</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todayNotReportedTasks as $task)
                                <tr class="table-info" data-category="{{$task->category_id}}">
                                    <td>
                                        {{ $task->name }}
                                        <span class="badge badge-danger" style="font-size: 10px">
                                            今日新增
                                        </span>
                                    </td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                        ->tz('Europe/London')
                                        ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                        ->diffForHumans()
                                    }}</td>
                                    <td class="text-center">{{ $task->remain_times }}</td>
                                    <td><button class="btn btn-sm btn-primary" id="report-{{$task->id}}" onclick="getTaskInReport({{ $task->id }}, '{{$task->name}}', 0)">回報</button></td>
                                </tr>
                                @endforeach
                                @foreach ($otherNotReportedTasks as $task)
                                <tr data-category="{{$task->category_id}}">
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                        ->tz('Europe/London')
                                        ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                        ->diffForHumans()
                                    }}</td>
                                    <td class="text-center">{{ $task->remain_times }}</td>
                                    <td><button class="btn btn-sm btn-primary" id="report-{{$task->id}}" onclick="getTaskInReport({{ $task->id }}, '{{$task->name}}', 0)">回報</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <hr class="mt-5">
                        目前沒有最新任務
                    @endif

                </div>
            </div>

            <div class="card">
                <div class="card-header">回報的任務</div>
                <div class="card-body" id="task-card-reported">
                    <div class="float-right input-group input-group-sm mb-3" style="width:23ch">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="catInputGroupSelect2">任務種類</label>
                        </div>
                        <select class="custom-select" name="" id="catInputGroupSelect2">
                            @if ($categories->count() > 0)
                                <option value="-1" selected>全部（預設）</option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            @else
                                <option value="0" selected>尚無任務種類</option>
                            @endif
                        </select>
                    </div>
                    {{-- <div class="float-right input-group input-group-sm mb-3 mr-3" style="width:23ch">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="dateInputGroupSelect">任務排序</label>
                        </div>
                        <select class="custom-select" name="" id="dateInputGroupSelect">
                        @if ($tasks->count() > 0)
                            <option value="-1" selected>預設</option>
                            <option value="">按到期日</option>
                            <option value="">按任務新增日</option>
                            <option value="">按任務分數</option>
                        @else
                            <option value="0" selected>尚無任務</option>
                        @endif
                        </select>
                    </div> --}}
                    @php
                        $todayReportedTasks = $tasks->get('todayTasks')->filter(function($task) {
                            $user = $task->users()->where('users.id', Auth::user()->id)->first();
                            if($user == NULL){
                                return false;
                            }else{
                                if($user->pivot->confirmed == 0 || $user->pivot->confirmed == -1)
                                return true;
                            }
                        });
                        $otherReportedTasks = $tasks->get('otherTasks')->filter(function($task) {
                            $user = $task->users()->where('users.id', Auth::user()->id)->first();
                            if($user == NULL){
                                return false;
                            }else{
                                if($user->pivot->confirmed == 0 || $user->pivot->confirmed == -1)
                                return true;
                            }
                        })
                    @endphp
                    @if ($todayReportedTasks->count() > 0 || $otherReportedTasks->count() > 0)
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">任務名</th>
                                    <th scope="col">敘述</th>
                                    <th scope="col">分數</th>
                                    <th scope="col">到期日</th>
                                    <th scope="col">剩餘次數</th>
                                    <th scope="col">回報狀態</th>
                                    <th scope="col">完成回報</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todayReportedTasks as $task)
                                @php
                                    $user = $task->users()->where('users.id', Auth::user()->id)->first();
                                    $confirmed = $user->pivot->confirmed;
                                @endphp
                                <tr class="table-info" data-category="{{$task->category_id}}">
                                    <td>
                                        {{ $task->name }}
                                    </td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                        ->tz('Europe/London')
                                        ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                        ->diffForHumans()
                                    }}</td>
                                    <td class="text-center">{{ $task->remain_times }}</td>
                                    @if ($confirmed == 0)
                                    <td>
                                        <span class="badge badge-primary">任務審核中</span>
                                    </td>
                                    <td><button class="btn btn-sm btn-secondary" id="report-{{$task->id}}" disabled>再回報</button></td>
                                    @else
                                    <td>
                                        <span class="badge badge-danger">任務遭駁回</span>
                                    </td>
                                    <td><button class="btn btn-sm btn-primary" id="report-{{$task->id}}" onclick="getTaskInReport({{ $task->id }}, '{{$task->name}}', 1)">再回報</button></td>
                                    @endif
                                </tr>
                                @endforeach
                                @foreach ($otherReportedTasks as $task)
                                @php
                                    $user = $task->users()->where('users.id', Auth::user()->id)->first();
                                    $confirmed = $user->pivot->confirmed;
                                @endphp
                                <tr data-category="{{$task->category_id}}">
                                    <td>
                                        {{ $task->name }}
                                    </td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                        ->tz('Europe/London')
                                        ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                        ->diffForHumans()
                                    }}</td>
                                    <td class="text-center">{{ $task->remain_times }}</td>
                                    @if ($confirmed == 0)
                                    <td>
                                        <span class="badge badge-primary">任務審核中</span>
                                    </td>
                                    <td><button class="btn btn-sm btn-secondary" id="report-{{$task->id}}" disabled>再回報</button></td>
                                    @else
                                    <td>
                                        <span class="badge badge-danger">任務遭駁回</span>
                                    </td>
                                    <td><button class="btn btn-sm btn-primary" id="report-{{$task->id}}" onclick="getTaskInReport({{ $task->id }}, '{{$task->name}}', 1)">再回報</button></td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <hr class="mt-5">
                        <span>目前沒有回報任務</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('inc.reportTask')
<script>
    function getTaskInReport(id, name, isReport) {
        $('#task-name').empty();
        $('#task-report').empty();
        $('#task-name').append(name);
        $('#task-id').val(id);
        $('#is-report').val(isReport);
        $('#reportTaskModalCenter').modal('toggle');
    }
    $('#catInputGroupSelect').change(() => {
        let selected = $("#catInputGroupSelect").find(":selected").val();
        if(selected > 0) {
            $('table:eq(0) tr').hide();
            $('thead:eq(0) tr').show();
            $(`tbody:eq(0) tr[data-category=${$("#catInputGroupSelect").find(":selected").val()}]`).show();
        }else if(selected < 0) {
            $('table:eq(0) tr').show();
        }
    })
    $('#catInputGroupSelect2').change(() => {
        let selected = $("#catInputGroupSelect2").find(":selected").val();
        if(selected > 0) {
            $('table:eq(1) tr').hide();
            $('thead:eq(1) tr').show();
            $(`tbody:eq(1) tr[data-category=${$("#catInputGroupSelect").find(":selected").val()}]`).show();
        }else if(selected < 0) {
            $('table:eq(1) tr').show();
        }
    })
</script>
@if ($categories->count() == 0)
@include('inc.addCategory')
@endif
@endsection
