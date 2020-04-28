@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="alert alert-success" id="success-msg" role="alert" style="display:none"></div>
            <div class="alert alert-danger" id="error-msg" role="alert" style="display:none"></div>
            @php
                $categories = \App\Group::find(Auth::user()->active_group)->categories;
            @endphp
            @if ($categories->count() == 0)
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
            <div class="card">
                <div class="card-header">任務欄</div>
                <div class="card-body">
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
                    @if ($tasks->get('todayTasks')->count() > 0 || $tasks->get('otherTasks')->count() > 0)
                        <table class="table table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <td scope="col">任務名</td>
                                    <td scope="col">敘述</td>
                                    <td scope="col">分數</td>
                                    <td scope="col">到期日</td>
                                    <td scope="col">剩餘次數</td>
                                    <td scope="col">完成回報</td>
                                </tr>
                            </thead>
                            @foreach ($tasks->get('todayTasks') as $task)
                                @php
                                    $confirmed = 0;
                                    $isReport = false;
                                    foreach($task->users as $user) {
                                        if($user->pivot->user_id == Auth::user()->id && $user->pivot->task_id == $task->id) {
                                            $isReport = true;
                                            $confirmed = $user->pivot->confirmed;
                                        }
                                    }
                                @endphp
                                @if($confirmed == 0)
                                <tbody id="category-{{$task->category_id}}">
                                    <tr class="table-info">
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
                                        @if ($isReport)
                                        <td><button class="btn btn-sm btn-secondary" disabled>待審核</button></td>
                                        @else
                                        <td><button class="btn btn-sm btn-primary" id="report-{{$task->id}}" onclick="getTask({{ $task }})">回報</button></td>
                                        @endif
                                    </tr>
                                </tbody>
                                @endif
                            @endforeach
                            @foreach ($tasks->get('otherTasks') as $task)
                            @php
                                $confirmed = 0;
                                $isReport = false;
                                foreach($task->users as $user) {
                                    if($user->pivot->user_id == Auth::user()->id && $user->pivot->task_id == $task->id) {
                                        $isReport = true;
                                        $confirmed = $user->pivot->confirmed;
                                    }
                                }
                            @endphp
                                @if ($confirmed == 0)
                                <tbody id="category-{{$task->category_id}}">
                                    <tr>
                                        <td>{{ $task->name }}</td>
                                        <td>{{ $task->description }}</td>
                                        <td>{{ $task->score }}</td>
                                        <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                            ->tz('Europe/London')
                                            ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                            ->diffForHumans()
                                        }}</td>
                                        <td class="text-center">{{ $task->remain_times }}</td>
                                        @if ($isReport)
                                        <td><button class="btn btn-sm btn-secondary" disabled>待審核</button></td>
                                        @else
                                        <td><button class="btn btn-sm btn-primary" id="report-{{$task->id}}" onclick="getTask({{ $task }})">回報</button></td>
                                        @endif
                                    </tr>
                                </tbody>
                                @endif
                            @endforeach
                        </table>
                    @else
                        <hr class="mt-5">
                        目前沒有任何任務
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('inc.reportTask')
<script>
    function getTask(task) {
        $('#task-name').empty();
        $('#task-report').empty();
        $('#task-name').append(task.name);
        $('#task-id').val(task.id);
        $('#reportTaskModalCenter').modal('toggle');
    }
    $(() => {
        $('#catInputGroupSelect').change(() => {
            let selected = $("#catInputGroupSelect").find(":selected").val();
            if(selected > 0) {
                $('tbody').hide();
                $(`tbody#category-${$("#catInputGroupSelect").find(":selected").val()}`).show();
            }else if(selected < 0) {
                $('tbody').show();
            }
        })
    })
</script>
@if ($categories->count() == 0)
@include('inc.addCategory')
@endif
@endsection
