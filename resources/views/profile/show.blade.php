@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card">
                <div class="card-header">基本資料</div>
                
                <div class="card-body">
                    <table class="table">
                        <tr>
                            {{-- 上傳圖片有問題 --}}
                            {{-- 問題1. 連結吃不到檔案 --}}
                            {{-- 問題2. 似乎有快取問題，檔案已經更新畫面卻不會馬上更新 --}}
                            {{-- 在 edit.blade 有一樣的問題 --}}
                            {{-- 在 leaderboard.index.blade 有一樣的問題 --}}
                            {{-- <td rowspan="3" class="text-center"><img src="/storage/images/{{ $data['photo'] }}" id="photo"></td> --}}
                            <td rowspan="3" class="text-center"><img src="/storage2/images/{{ $data['photo'] }}" id="photo"></td>
                            <td>{{ $data['name'] }}</td>
                        </tr>
                        <tr>
                            <td width="60%">{{ $data['email'] }}</td>
                        </tr>
                        <tr>
                            <td>{{ $data['self_expectation'] }}</td>
                        </tr>
                        @if (Auth::user()->id == $data['id'])
                            <tr>
                                <td></td>
                                <td><a href="/profile/{{ $data['id'] }}/edit" class="btn btn-outline-secondary">編輯</a></td>
                            </tr>
                        @endif
                    </table>

                    {{-- hover info --}}
                    <div id="info">
                        <table>
                            <tr>
                                <td class="info-td">職稱</td>
                                <td class="info-td">{{ $data['job_title'] }}</td>
                            </tr>
                            <tr>
                                <td class="info-td">部門</td>
                                <td class="info-td">{{ $data['department'] }}</td>
                            </tr>
                            <tr>
                                <td class="info-td">辦公室</td>
                                <td class="info-td">{{ $data['office_location'] }}</td>
                            </tr>
                            <tr>
                                <td class="info-td">分機</td>
                                <td class="info-td">{{ $data['extension'] }}</td>
                            </tr>
                        </table>
                    </div>

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
                <div class="card-header">獎牌與積分</div>

                <div class="card-body">

                    <table width="100%">
                        <tr>
                        <td width="40%"><p class="text-dark font-weight-bold">本期積分：{{ $data['periodScore'] }}</p></td>
                            <td width="60%" class="text-center"><p class="text-dark font-weight-bold">累積積分：{{ $data['allScore'] }}</p></td>
                        </tr>
                        <tr>
                            <td valign="top">
                                @if (count($data['completeTasksInThePast']) > 0)
                                    @foreach ($data['completeTasksInThisPeriod'] as $completeTask)
                                        <p class="text-dark">{{ $completeTask }}</p>
                                    @endforeach
                                    <a id="see-more-btn" href="#" class="btn btn-outline-secondary">看全部（歷史紀錄）</a>
                                    <div id="see-more">
                                        <hr>
                                        @foreach ($data['completeTasksInThePast'] as $completeTask)
                                            <p class="text-dark">{{ $completeTask }}</p>
                                        @endforeach
                                    </div>
                                @else
                                    @foreach ($data['completeTasksInThisPeriod'] as $completeTask)
                                        <p class="text-dark">{{ $completeTask }}</p>
                                    @endforeach
                                @endif
                            </td>
                            <td valign="top" class="text-center">
                                @if ($data['medals']['scoreToNextRank'] == 1)
                                    <p>已達到最高階級</p>
                                @else
                                    <p class="text-dark">下一階級：{{ $data['medals']['currentScoreInThisRank'] }} / {{ $data['medals']['scoreToNextRank'] }}</p>
                                @endif
                                <!--Pie Chart -->
                                @php
                                    $percent = floor($data['medals']['currentScoreInThisRank'] / $data['medals']['scoreToNextRank'] * 100);
                                @endphp
                                <div class="progress-pie-chart" data-medal="{{ $data['medals']['medal'] }}" data-percent="{{ $percent }}">
                                    <div class="ppc-progress">
                                        <div class="ppc-progress-fill"></div>
                                    </div>
                                    <div class="ppc-percents">
                                        <div class="pcc-percents-wrapper">
                                            <span class="medal"></span>
                                            <span class="percent"></span>
                                        </div>
                                    </div>
                                </div>
                                <!--End Chart -->
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery CDN - Slim version (=without AJAX) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<script>
    $(function(){
        let $ppc = $('.progress-pie-chart'),
            medal = $ppc.data('medal'),
            percent = parseInt($ppc.data('percent')),
            deg = 360 * percent/100;
        if (percent > 50) {
            $ppc.addClass('gt-50');
        }
        $('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
        if(medal == '金牌I'){
            $('.ppc-percents .medal').html(medal);
        } else {
            $('.ppc-percents .medal').html(medal);
            $('.ppc-percents .percent').html(percent+'%');
        }

        $("img").hover(function(){
            $('#info').toggle();
        });

        $("#see-more-btn").click(function(){
            event.preventDefault();
            $('#see-more-btn').hide();
            $('#see-more').show();
        });
    });
</script>

<style>
    #photo {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }
    #info {
        display: none;
        position: absolute;
        top: 20%;
        left: 36%;
        background-color: white;
        border: 1px solid;
        padding: 10px;
        box-shadow: 3px 5px #888888;
    }
    .info-td {
        padding: 5px;
    }

    .progress-pie-chart {
        margin-top: 0px;
    }

    #see-more {
        display: none;
    }
</style>

@endsection
