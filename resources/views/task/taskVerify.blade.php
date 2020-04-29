@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="alert alert-my-color" id="success-msg" role="alert" style="display:none"></div>
            <div class="alert alert-danger" id="error-msg" role="alert" style="display:none"></div>
            <div class="card mb-3">
                <div class="card-header">待審核任務欄</div>
                <div class="card-body">
                    @if ($tasks->get('notConfirmedTasks')->count() > 0)
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">任務名</td>
                                    <th scope="col">敘述</td>
                                    <th scope="col">分數</td>
                                    <th scope="col">到期日</td>
                                    <th scope="col">剩餘次數</td>
                                    <th scope="col">待審核者</td>
                                    <th scope="col">是否通過</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks->get('notConfirmedTasks') as $task)
                                    @foreach ($task->users as $user)
                                        @if($user->pivot->confirmed == 0)
                                        <tr id="verifying-task-{{$task->id}}" data-category="{{$task->category_id}}">
                                            <td>{{ $task->name }}</td>
                                            <td>{{ $task->description }}</td>
                                            <td>{{ $task->score }}</td>
                                            <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                                ->tz('Europe/London')
                                                ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                                ->toDateString()
                                                }}</td>
                                            <td class="text-center">{{ $task->remain_times }}</td>
                                            <td>
                                                <span style="font:bold; color:blue; cursor:pointer" onclick="getReport(this)">{{$user->name}}</span>
                                                <span style="display:none">{{$user->pivot->report}}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary"
                                                    onclick="verifyTask(this, {{$task->id}}, {{$user->id}}, '{{$user->name}}', '{{$user->pivot->report}}', 1)">
                                                    通過
                                                </button>
                                                <button class="btn btn-sm btn-secondary"
                                                    onclick="verifyTask(this, {{$task->id}}, {{$user->id}}, '{{$user->name}}', '{{$user->pivot->report}}', -1)">
                                                    駁回
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @else
                    目前沒有待審核任務
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header">已審核任務欄</div>
                <div class="card-body">
                    @if ($tasks->get('confirmedTasks')->count() > 0)
                        <table class="table table-hover" id="verified-task">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">任務名</td>
                                    <th scope="col">敘述</td>
                                    <th scope="col">分數</td>
                                    <th scope="col">到期日</td>
                                    <th scope="col">剩餘次數</td>
                                    <th scope="col">完成任務者</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks->get('confirmedTasks') as $task)
                                    @foreach ($task->users as $user)
                                        @if($user->pivot->confirmed == 1)
                                        <tr data-category="{{$task->category_id}}">
                                            <td>{{ $task->name }}</td>
                                            <td>{{ $task->description }}</td>
                                            <td>{{ $task->score }}</td>
                                            <td>{{ \Carbon\Carbon::parse($task->expired_at)
                                                ->tz('Europe/London')
                                                ->setTimeZone('Asia/Taipei')->locale('zh_TW')
                                                ->toDateString()
                                                }}</td>
                                            <td class="text-center">{{ $task->remain_times }}</td>
                                            <td>
                                                <span style="font:bold; color:blue; cursor:pointer" onclick="getReport(this)">{{$user->name}}</span>
                                                <span style="display:none">{{$user->pivot->report}}</span>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @else
                    目前沒有人完成任務
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function getReport(e){
        const td = $(e).parent();
        const tr = td.parent()
        const report = td.children('span:last').text();
        $('#verifyReportModalCenterTitle').text(`${$(e).text()} ${tr.children('td:nth-child(1)').text()} 的任務回報`)
        $('#report').text(report);
        $('#verifyReportModalCenter').modal('toggle');
    }
</script>
@include('inc.viewReport')
@include('inc.verifyTask')
@endsection
