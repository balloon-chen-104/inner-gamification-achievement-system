@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card">
                <div class="card-header">基本資料</div>
                
                <div class="card-body">
                    <table class="table">
                        <form action="">
                            <tr>
                                <td rowspan="3" class="text-center"><img src="/balloon.jpg" id="photo"></td>
                                <td>氣球</td>
                            </tr>
                            <tr>
                                <td width="60%">balloon@gmail.com</td>
                            </tr>
                            <tr>
                                <td><textarea name="" id="" class="form-control">一句話一句話一句話一句話一句話一句話一句話一句話一句話一句話</textarea></td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <a href="#" class="btn btn-secondary">上傳照片</a>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-secondary">確定</a>
                                    <a href="/profile" class="btn btn-outline-secondary">返回</a>
                                </td>
                            </tr>
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
                            <td width="40%"><p class="text-dark font-weight-bold">本期積分：300</p></td>
                            <td width="60%" class="text-center"><p class="text-dark font-weight-bold">累積積分：1000</p></td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <p class="text-dark">完成任務A得10分</p>
                                <p class="text-dark">完成任務B得20分</p>
                                <p class="text-dark">完成任務C得30分</p>
                                <p class="text-dark">完成任務D得30分</p>
                                <p class="text-dark">完成任務E得30分</p>
                                <p class="text-dark">完成任務F得30分</p>
                            </td>
                            <td valign="top" class="text-center">
                                <p class="text-dark">下一階級：1000/2000</p>
                                <!--Pie Chart -->
                                <div class="progress-pie-chart" data-medal="銀牌2" data-percent="65">
                                    <div class="ppc-progress">
                                        <div class="ppc-progress-fill"></div>
                                    </div>
                                    <div class="ppc-percents">
                                        <div class="pcc-percents-wrapper">
                                            <span>%</span>
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
        $('.ppc-percents span').html(medal+'\n'+percent+'%');
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
</style>

@endsection
