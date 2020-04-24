@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">新增公告</div>
                <div class="card-body">
                    
                    <form action="/bulletin" method="post">
                        <textarea id ="bulletin-ckeditor" name="content" class="form-control form-control-lg"></textarea>
                        <span class="invalid-feedback"></span>
                        <br>
                        <div class="d-flex justify-content-center">
                            <input type="submit" class="btn btn-secondary btn-sm" value="送出">&nbsp&nbsp
                            <a href="/bulletin" class="btn btn-outline-secondary btn-sm">返回</a>
                        </div>
                        <input type="hidden" name="type" value="announcement">
                        @csrf
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
