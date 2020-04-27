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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>金牌3</td>
                                <td><a href="/profile"><img src="/balloon.jpg" class="photo"></a></td>
                                <td><a href="/profile">ＡＡＡ</a></td>
                                <td>500</td>
                                <td>2000</td>
                            </tr>
                            <tr>
                                <td>銀牌3</td>
                                <td><a href="/profile"><img src="/balloon.jpg" class="photo"></a></td>
                                <td><a href="/profile">ＢＢＢ</a></td>
                                <td>400</td>
                                <td>1700</td>
                            </tr>
                            <tr>
                                <td>銅牌1</td>
                                <td><a href="/profile"><img src="/balloon.jpg" class="photo"></a></td>
                                <td><a href="/profile">ＣＣＣ</a></td>
                                <td>200</td>
                                <td>1300</td>
                            </tr>
                            <tr>
                                <td>銅牌3</td>
                                <td><a href="/profile"><img src="/balloon.jpg" class="photo"></a></td>
                                <td><a href="/profile">ＤＤＤ</a></td>
                                <td>100</td>
                                <td>1000</td>
                            </tr>
                            <tr>
                                <td>銅牌1</td>
                                <td><a href="/profile"><img src="/balloon.jpg" class="photo"></a></td>
                                <td><a href="/profile">ＥＥＥ</a></td>
                                <td>50</td>
                                <td>1200</td>
                            </tr>
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
