@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="alert alert-success" id="success-msg" role="alert" style="display:none"></div>
            <div class="alert alert-danger" id="error-msg" role="alert" style="display:none"></div>
            <div class="card mb-3">
                <div class="card-header" style="cursor: pointer" onclick="toggleCategory()">新增任務種類</div>
                <div class="card-body" id="add-category" style="display:none">
                    <form action="" id="add-category-form">
                        <div class="form-group">
                            <label for="category-name">種類名稱</label>
                            <input type="text" class="form-control" id="category-name">
                        </div>
                        <button type="submit" class="btn btn-primary">新增</button>
                    </form>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header" style="cursor: pointer" onclick="toggleTaskAdd()">新增任務</div>
                <div class="card-body" id="add-task" style="display:none">
                    <form action="">
                        <div class="form-row">
                            <div class="col mr-3">
                                <label for="add-task-name">任務名稱</label>
                                <input type="text" class="form-control" id="add-task-name">
                            </div>
                            <div class="col">
                                <label for="add-task-name">任務種類</label>
                                <select class="custom-select" name="" id="add-task-category">
                                    @if ($group->categories->count() > 0)
                                        <option value="-1" selected disabled>選擇任務種類</option>
                                        @foreach ($group->categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    @else
                                        <option value="0" selected>尚無任務種類</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="task-description">任務內容描述</label>
                            <textarea name="task-description" class="form-control" id="add-task-description" cols="30" rows="2" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="task-expired-at">任務到期日</label>
                            <input type="text" class="form-control datepicker" id="add-task-expired-at" required>
                        </div>
                        <div class="form-row">
                            <div class="col mr-3">
                                <label for="task-score">分數</label>
                                <input type="number" id="add-task-score" class="form-control" value="20" required>
                            </div>
                            <div class="col">
                                <label for="task-remain">剩餘次數</label>
                                <input type="number" id="add-task-remain" class="form-control" value="20" required>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">新增</button>
                    </form>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header" style="cursor: pointer" onclick="toggleTaskPropose()">審查員工任務提案</div>
                <div class="card-body" id="propose-task" style="display:none">
                    @if ($group->tasks->count() > 0)
                        <table class="table table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <td scope="col">姓名</td>
                                    <td scope="col">敘述</td>
                                    <td scope="col">分數</td>
                                    <td scope="col">到期日</td>
                                    <td scope="col">剩餘次數</td>
                                    <td scope="col">確認提案</td>
                                </tr>
                            </thead>
                            @php
                                $todayTasks = $group->tasks()->today()->notExpired()->notConfirmed()->latest()->get();
                                $otherTasks = $group->tasks()->notConfirmed()->notExpired()->get()->diff($todayTasks);
                            @endphp
                            @foreach ($todayTasks as $task)
                            <tbody id="category-{{$task->category_id}}">
                                <tr class="table-info">
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                        ->tz('Europe/London')
                                        ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                        ->toDateString()
                                    }}</td>
                                    <td class="text-center">{{ $task->remain_times }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            確認
                                        </button>
                                        <button class="btn btn-sm btn-secondary">
                                            駁回
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                            @foreach ($otherTasks as $task)
                            <tbody id="category-{{$task->category_id}}">
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                        ->tz('Europe/London')
                                        ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                        ->toDateString()
                                    }}</td>
                                    <td class="text-center">{{ $task->remain_times }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            修改
                                        </button>
                                        <button class="btn btn-sm btn-secondary">
                                            駁回
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    @else
                        目前沒有任何提案
                    @endif
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header" style="cursor: pointer" onclick="toggleTaskEdit()">修改目前已有任務</div>
                <div class="card-body" id="edit-task" style="display:none">
                    @if ($group->tasks->count() > 0)
                        <table class="table table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <td scope="col">姓名</td>
                                    <td scope="col">敘述</td>
                                    <td scope="col">分數</td>
                                    <td scope="col">到期日</td>
                                    <td scope="col">剩餘次數</td>
                                    <td scope="col">修改任務</td>
                                </tr>
                            </thead>
                            @php
                                $todayTasks = $group->tasks()->today()->notExpired()->confirmed()->latest()->get();
                                $otherTasks = $group->tasks()->confirmed()->notExpired()->get()->diff($todayTasks);
                            @endphp
                            @foreach ($todayTasks as $task)
                            <tbody id="category-{{$task->category_id}}">
                                <tr class="table-info">
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                        ->tz('Europe/London')
                                        ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                        ->diffForHumans()
                                    }}</td>
                                    <td class="text-center">{{ $task->remain_times }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editTaskModalCenter"
                                            onclick="getTask({{$task}})">
                                            修改
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                            @foreach ($otherTasks as $task)
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
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editTaskModalCenter"
                                            onclick="getTask({{$task}})">
                                            修改
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    @else
                        目前沒有任何任務
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('inc.editTask')
@include('inc.datePicker')
<script>
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });
    function getTask(task) {
        $('#task-name').val(task.name);
        $('#task-id').val(task.id);
        $('#task-description').val(task.description);
        $('#task-category').children().each(function(){
            if($(this).val() == task.category_id){
                $(this).attr('selected', true);
            }
        })
        const expiredAt = new Date(task.expired_at);
        let expiredYear = expiredAt.getFullYear();
        let expiredMonth = (expiredAt.getMonth() + 1 < 10)? `0${expiredAt.getMonth() + 1}`: expiredAt.getMonth() + 1;
        let expiredDate = (expiredAt.getDate()< 10)? `0${expiredAt.getDate()}`: expiredAt.getDate();
        $('#task-expired-at').val(`${expiredYear}-${expiredMonth}-${expiredDate}`);
        $('#task-score').val(task.score);
        $('#task-remain').val(task.remain_times);
    }
    function toggleCategory() {
        $('#add-category').slideToggle();
    }
    function toggleTaskAdd() {
        $('#add-task').slideToggle();
    }
    function toggleTaskPropose() {
        $('#propose-task').slideToggle();
    }
    function toggleTaskEdit() {
        $('#edit-task').slideToggle();
    }
</script>
@include('inc.addCategory')
@endsection
