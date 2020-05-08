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

                    <form action="/setting/updateCycle" method="post" class="form-inline">
                        <p class="text-dark">
                            <span>週期：</span>
                            {{-- <div class="form-group"> --}}
                            <input type="text" name="cycle" class="form-control w-25 <?php echo (!empty($data['cycle_error'])) ? 'is-invalid' : ''; ?>" value="{{ $data['cycle'] }}" required>
                            {{-- </div> --}}
                            <span>&nbsp&nbsp天&nbsp&nbsp</span>
                            <input type="submit" class="btn btn-secondary btn-sm" value="確定">&nbsp
                            <a href="/setting" class="btn btn-outline-secondary btn-sm">返回</a>
                            <span class="invalid-feedback">{{ $data['cycle_error'] }}</span>
                        </p>
                        @method('PUT')
                        @csrf
                    </form>

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
                        @foreach ($data['flash_messages'] as $flash_message)
                            <tr>
                                <td>{{ $flash_message->content }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" @if($flash_message->flash_message_switch) checked @endif>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
