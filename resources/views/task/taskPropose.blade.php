{{-- {{dd($tasks)}} --}}
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="alert alert-my-color" id="success-msg" role="alert" style="display:none"></div>
            <div class="alert alert-danger" id="error-msg" role="alert" style="display:none"></div>
            <div class="card mb-3">
                <div class="card-header" style="cursor: pointer" onclick="$('.card-body:eq(0)').slideToggle();">提案新任務</div>
                <div class="card-body" style="display:none">
                    <form id="add-task-form" data-confirmed="0" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col mr-3">
                                <label for="add-task-name">任務名稱</label>
                                <input type="text" class="form-control" id="add-task-name">
                            </div>
                            <div class="col">
                                <label for="add-task-category">任務種類</label>
                                <select class="custom-select" name="" id="add-task-category">
                                    @php
                                        $categories = \App\Group::find(Auth::user()->active_group)->categories;
                                    @endphp
                                    @if ($categories->count() > 0)
                                        <option value="-1" selected disabled>選擇任務種類</option>
                                        @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    @else
                                        <option value="0" selected>尚無任務種類</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="add-task-description">任務內容描述</label>
                            <textarea name="task-description" class="form-control" id="add-task-description" cols="30" rows="2" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="add-task-expired-at">任務到期日</label>
                            <input type="text" class="form-control datepicker" id="add-task-expired-at" required>
                        </div>
                        <div class="form-row">
                            <div class="col mr-3">
                                <label for="add-task-score">分數</label>
                                <input type="number" id="add-task-score" class="form-control" value="20" required>
                            </div>
                            <div class="col">
                                <label for="add-task-remain">剩餘次數</label>
                                <input type="number" id="add-task-remain" class="form-control" value="20" required>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">提案</button>
                    </form>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header" style="cursor: pointer" onclick="$('.card-body:eq(1)').slideToggle();">審核中的提案任務</div>
                <div class="card-body" style="display:none">
                    @if ($tasks->get('proposed_tasks')->count() > 0)
                        <table class="table table-hover" id="current-task">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">任務名</th>
                                    <th scope="col">敘述</th>
                                    <th scope="col">分數</th>
                                    <th scope="col">到期日</th>
                                    <th scope="col">剩餘次數</th>
                                    <th scope="col">提案狀態</th>
                                    <th scope="col">修改再提交</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks->get('proposed_tasks') as $task)
                                <tr data-category="{{$task->category_id}}" id="edit-task-{{$task->id}}">
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                        ->tz('Europe/London')
                                        ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                        ->toDateString()
                                    }}</td>
                                    <td class="text-center">{{ $task->remain_times }}</td>
                                    @if ($task->confirmed == 0)
                                        <td>
                                            <span class="badge badge-primary">提案審核中</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                修改
                                            </button>
                                        </td>
                                    @else
                                        <td>
                                            <span class="badge badge-danger">提案遭駁回</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="getTaskInEdit(this, -1)">
                                                修改
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        目前沒有任何任務
                    @endif
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header" style="cursor: pointer" onclick="$('.card-body:eq(2)').slideToggle();">已通過的提案任務</div>
                <div class="card-body" style="display:none">
                    @if ($tasks->get('passed_tasks')->count() > 0)
                        <table class="table table-hover" id="current-task">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">任務名</th>
                                    <th scope="col">敘述</th>
                                    <th scope="col">分數</th>
                                    <th scope="col">到期日</th>
                                    <th scope="col">剩餘次數</th>
                                    <th scope="col">提案狀態</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks->get('passed_tasks') as $task)
                                <tr data-category="{{$task->category_id}}" id="edit-task-{{$task->id}}">
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                        ->tz('Europe/London')
                                        ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                        ->toDateString()
                                    }}</td>
                                    <td class="text-center">{{ $task->remain_times }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">已通過</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        目前沒有任何任務
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('inc.datePicker')
<script>
    // console.log($('#add-task').has('table thead').length)
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });
</script>
@include('inc.editTask')
@include('inc.addTask')
@endsection
