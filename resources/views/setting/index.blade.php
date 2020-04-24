@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">群組ID</div>
                <div class="card-body">
                    {{\App\Group::find(Auth::user()->active_group)->first()->group_token}}
                </div>
            </div>
        </div>
    </div>
</div>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">佈告欄週期設定</div>

                <div class="card-body">

                    <p class="text-dark">週期：{{ $data['cycle'] }}天&nbsp&nbsp&nbsp<a href="/setting/editCycle" class="btn btn-secondary btn-sm">修改</a></p>
                    <p class="text-dark">起始日：{{ $data['started_at'] }}</p>
                    @php
                        $expiredDate = date_add(date_create($data['started_at']), date_interval_create_from_date_string($data['cycle'] - 1 . 'days'));
                        $expiredDate = date_format($expiredDate, 'Y-m-d');
                    @endphp
                    <p class="text-dark">結算日：{{ $expiredDate }}</p>

                </div>
            </div>
        </div>
    </div>
</div>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">快訊開關設定</div>

                <div class="card-body">

                     <table class="table table-striped table-bordered">
                        @if (count($data['flash_messages']) == 0)
                            <p>尚無快訊</p>
                        @else
                            @foreach ($data['flash_messages'] as $flash_message)
                                <tr>
                                    <td>{{ $flash_message->content }}</td>
                                    <td>
                                        <form id="flash_message_switch-form-{{ $flash_message->id }}" action="/setting/{{$flash_message->id}}/updateFlashMessage" method="post">
                                            <label class="switch">
                                                <input type="checkbox"
                                                    @if($flash_message->flash_message_switch) checked @endif
                                                    onchange = "document.getElementById('flash_message_switch-form-{{ $flash_message->id }}').submit();">
                                                <span class="slider round"></span>
                                            </label>
                                            <input type="hidden" name="flash_message_switch" value="1">
                                            @method('PUT')
                                            @csrf
                                        </form>
                                    </td>
                                    <td>
                                        <form action="/setting/{{$flash_message->id}}/destroyFlashMessage" method="post">
                                                <a href="/setting/{{$flash_message->id}}/editFlashMessage" class="btn btn-secondary btn-sm">編輯</a>&nbsp
                                                <input type="submit" class="btn btn-outline-danger btn-sm" value="刪除">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>

                    <div class="d-flex justify-content-center">
                        <a href="/setting/createFlashMessage" class="btn btn-secondary btn-sm">新增快訊</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
