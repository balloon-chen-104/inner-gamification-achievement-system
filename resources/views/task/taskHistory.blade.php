@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">任務欄</div>
                <div class="card-body">
                    <div class="float-right input-group input-group-sm mb-3" style="width:23ch">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="catInputGroupSelect">任務種類</label>
                        </div>
                        <select class="custom-select" name="" id="catInputGroupSelect">
                            @if ($group->categories->count() > 0)
                                <option value="-1" selected>全部（預設）</option>
                                @foreach ($group->categories as $category)
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
                        @if ($group->tasks->count() > 0)
                            <option value="-1" selected>預設</option>
                            <option value="">按到期日</option>
                            <option value="">按任務新增日</option>
                            <option value="">按任務分數</option>
                        @else
                            <option value="0" selected>尚無任務</option>
                        @endif
                        </select>
                    </div> --}}
                    @if ($group->tasks->count() > 0)
                        <table class="table table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <td scope="col">姓名</td>
                                    <td scope="col">敘述</td>
                                    <td scope="col">分數</td>
                                    <td scope="col">到期日</td>
                                    <td scope="col">剩餘次數</td>
                                    <td scope="col">完成回報</td>
                                </tr>
                            </thead>
                            @foreach ($group->tasks()->expired()->get() as $task)
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
                                    <td>{{ $task->remain_times }}</td>
                                    <td><button class="btn btn-sm btn-secondary" disabled>回報</button></td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    @else
                    <hr class="mt-5">
                    目前沒有已到期任何任務
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
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
@endsection
