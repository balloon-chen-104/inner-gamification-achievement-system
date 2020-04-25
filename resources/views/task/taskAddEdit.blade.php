@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header" style="cursor: pointer" onclick="toggleCategory()">新增任務種類</div>
                <div class="card-body" id="add-category" style="display:none">
                    <form action="">
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
                        <div class="form-group">
                            <label for="add-task-name">任務名稱</label>
                            <input type="text" class="form-control" id="add-task-name">
                        </div>
                        <button type="submit" class="btn btn-primary">新增</button>
                    </form>
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
                                    <td scope="col">修改任務</td>
                                </tr>
                            </thead>
                            @foreach ($group->tasks()->notExpired()->get() as $task)
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
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editTaskModalCenter"
                                            onclick="getTask({{$task->id}}, '{{$task->name}}',
                                            '{{$task->description}}', '{{\Carbon\Carbon::parse($task->expired_at)
                                                ->tz('Europe/London')
                                                ->setTimeZone('Asia/Taipei')
                                                ->locale('zh_TW')
                                                ->toDateString()}}')">
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
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<link rel="stylesheet" href={{ asset('css/bootstrap-datepicker.min.css') }}>
<script>
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });
    function getTask(id, name, description, expired_at) {
        $('#task-name').val(name);
        $('#task-id').val(id);
        $('#task-description').val(description);
        $('#task-expired-at').val(expired_at);
    }
    function toggleCategory() {
        $('#add-category').slideToggle();
    }
    function toggleTaskAdd() {
        $('#add-task').slideToggle();
    }
    function toggleTaskEdit() {
        $('#edit-task').slideToggle();
    }
</script>
@endsection
