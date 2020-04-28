@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">英雄榜</div>

                <div class="card-body">

                     <table class="table table-hover">
                         <thead>
                            <tr>
                                <th>獎牌</th>
                                <th>頭像</th>
                                <th>姓名</th>
                                <th>本期積分</th>
                                <th>總積分</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user['medal'] }}</td>
                                    <td><img src="/storage2/images/{{ $user['photo'] }}" class="photo"></td>
                                    <td>{{ $user['name'] }}</td>
                                    <td>{{ $user['periodScore'] }}</td>
                                    <td>{{ $user['allScore'] }}</td>
                                    <td><a href="/profile/{{$user['id']}}" class="btn btn-outline-secondary btn-sm">更多</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .photo {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
</style>

@endsection
