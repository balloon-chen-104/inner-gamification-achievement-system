@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card">
                <div class="card-header">基本資料</div>
                
                <div class="card-body">
                    <table class="table">
                        <form action="/profile/{{ $data['id'] }}" method="post" enctype="multipart/form-data">
                            <tr>
                                @if ($data['photo'] == 'default-photo.jpg')
                                    <td rowspan="3" class="text-center"><img src="{{ asset('storage/images/default-photo.jpg') }}" id="photo"></td>
                                @else
                                    <td rowspan="3" class="text-center"><img src="{{ asset('storage/images/user_'.$data['id'].'/'.$data['photo']) }}" id="photo"></td>
                                @endif
                                <td>{{ $data['name'] }}</td>
                            </tr>
                            <tr>
                                <td width="60%">{{ $data['email'] }}</td>
                            </tr>
                            <tr>
                                <td><textarea name="self_expectation" class="form-control" required>{{ $data['self_expectation'] }}</textarea></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <label class="btn btn-secondary">
                                        <input name="photo" id="upload-photo" style="display:none;" type="file">上傳照片
                                        <i class="fas fa-check check"></i>
                                    </label>
                                </td>
                                <td>
                                    <input type="submit" class="btn btn-secondary" value="確定">&nbsp
                                    <a href="/profile/{{$data['id']}}" class="btn btn-outline-secondary">返回</a>
                                </td>
                            </tr>
                            @method('PUT')
                            @csrf
                        </form>
                    </table>

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
                                @foreach ($data['completeTasksInThisPeriod'] as $completeTask)
                                    <p class="text-dark">{{ $completeTask }}</p>
                                @endforeach
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

        $('#upload-photo').click(function(){
            $('.check').show();
        })
    });
</script>

<style>
    #photo {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }
    
    .progress-pie-chart {
        margin-top: 0px;
    }

    .check {
        display: none;
    }
</style>

@endsection
