@extends('layouts.app')

@section('content')

@foreach ($data['flash_messages'] as $bulletin)
    <div class="alert ml-5 mr-5 alert-my-color">{{ $bulletin->content }}
        @if ($data['autority'])
            <a href="#" class="close" onclick="event.preventDefault();document.getElementById('flash_message_switch-form-{{ $bulletin->id }}').submit();" dusk='close-flash-message-btn'></a>
        @endif
    </div>

    <form id="flash_message_switch-form-{{ $bulletin->id }}" action="/setting/{{$bulletin->id}}/updateFlashMessage" method="post">
        <input type="hidden" name="flash_message_switch" value="bulletin">
        @method('PUT')
        @csrf
    </form>
@endforeach

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">公告</div>
                <div class="card-body">
                    
                    @if (count($data['announcements']) == 0)
                        <p>尚無公告</p>
                    @else
                        @foreach ($data['announcements'] as $announcement)
                            <div class="card card-body mb-2">
                                <p class="text-dark">{!! $announcement->content !!}</p>
                                @if ($data['autority'])
                                    <form action="/bulletin/{{ $announcement->id }}" method="post">
                                        <a href="/bulletin/{{ $announcement->id }}/edit" class="btn btn-secondary btn-sm">編輯</a>&nbsp
                                        <input type="submit" class="btn btn-outline-danger btn-sm" value="刪除">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    
                    <br>
                    @if ($data['autority'])
                        <div class="d-flex justify-content-center">
                            <a href="/bulletin/create" class="btn btn-secondary btn-sm">新增公告</a>
                        </div>
                    @endif

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
                <div class="card-header">最新任務</div>
                <div class="card-body">

                    @if (count($data['latestTasks']) > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">任務名</th>
                                    <th scope="col">敘述</th>
                                    <th scope="col">分數</th>
                                    <th scope="col">到期日</th>
                                    <th scope="col">剩餘次數</th>
                                    <th scope="col">完成回報</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // 顯示的最大筆數
                                    $count = 5;
                                @endphp
                                @foreach ($data['latestTasks'] as $latestTask)
                                    @php
                                        $confirmed = 0;
                                        $isReport = false;
                                        foreach($latestTask->users as $user) {
                                            if($user->pivot->user_id == Auth::user()->id && $user->pivot->task_id == $latestTask->id) {
                                                $isReport = true;
                                                $confirmed = $user->pivot->confirmed;
                                            }
                                        }
                                    @endphp
                                    @if($confirmed == 0 && $count > 0)
                                        @php
                                            $count--;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $latestTask->name }}
                                                @if ($latestTask->updated_at->format('Y-m-d') == $data['todayTimeString'])
                                                    <span class="badge badge-danger" style="font-size: 10px">
                                                        今日新增
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $latestTask->description }}</td>
                                            <td>{{ $latestTask->score }}</td>
                                            <td>{{
                                                \Carbon\Carbon::parse($latestTask->expired_at)
                                                    ->tz('Europe/London')
                                                    ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                                    ->diffForHumans()
                                            }}</td>
                                            <td class="text-center">{{ $latestTask->remain_times }}</td>
                                            @if ($isReport)
                                                <td><button class="btn btn-sm btn-secondary" disabled>待審核</button></td>
                                            @else
                                                <td><button class="btn btn-sm btn-primary" id="report-{{$latestTask->id}}" onclick="getTask({{ $latestTask->id }}, '{{$latestTask->name}}')">回報</button></td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        目前沒有任何任務
                    @endif

                    <div class="d-flex justify-content-center">
                        <a href="/task" class="btn btn-secondary btn-sm">所有任務</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('inc.reportTask')
<script>
    function getTask(id, name) {
        $('#task-name').empty();
        $('#task-report').empty();
        $('#task-name').append(name);
        $('#task-id').val(id);
        $('#reportTaskModalCenter').modal('toggle');
    }
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




<style>
    p {
        color: black;
    }
    .alert-my-color {
        background-color: rgba(115, 134, 213,  0.2);
    }
</style>

@endsection
