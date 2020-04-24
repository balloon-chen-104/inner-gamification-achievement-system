@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">任務欄</div>
                <div class="card-body">

                    {{-- <p>任務種類</p> --}}
                    <div class="float-right input-group mb-3" style="width:25ch">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="catInputGroupSelect">任務種類</label>
                          </div>
                        <select class="custom-select" name="" id="catInputGroupSelect">
                            <option value="-1" selected>全部（預設）</option>
                            @foreach ($group->categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($group->tasks->count() > 0)
                        <table class="table table-striped">
                        @foreach ($group->tasks as $task)
                            <thead class="thead-light">
                                <tr>
                                    <td>姓名</td>
                                    <td>敘述</td>
                                    <td>分數</td>
                                    <td>完成回報</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->score }}</td>
                                    <td><button class="btn btn-sm btn-primary">回報</button></td>
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
@endsection
