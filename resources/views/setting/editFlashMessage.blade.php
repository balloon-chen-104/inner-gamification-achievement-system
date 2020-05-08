@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">群組ID</div>
                <div class="card-body">
                    {{\App\Group::where('id', Auth::user()->active_group)->first()->group_token}}
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

                    <p class="text-dark">週期：{{ $data['cycle'] }}天&nbsp&nbsp&nbsp</p>
                    <p class="text-dark">起始日：{{ $data['started_at'] }}</p>
                    @php
                        $expiredDate = date_add(date_create($data['started_at']), date_interval_create_from_date_string($data['cycle'] -1 . 'days'));
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
                        @foreach ($data['flash_messages'] as $flash_message)
                            <tr>
                                <form action="/setting/{{$flash_message->id}}/updateFlashMessage" method="post">
                                    @if ($flash_message->id == $data['id'])
                                        <td>
                                            <input type="text" name="flashMessage" class="form-control w-75" value="{{ $flash_message->content }}" required>
                                        </td>
                                        <td>
                                            <input type="submit" class="btn btn-secondary btn-sm" value="確定">&nbsp
                                            <a href="/setting" class="btn btn-outline-secondary btn-sm">返回</a>
                                            @method('PUT')
                                            @csrf
                                        </td>
                                    @else
                                        <td>{{ $flash_message->content }}</td>
                                        <td></td>
                                    @endif
                                </form>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
