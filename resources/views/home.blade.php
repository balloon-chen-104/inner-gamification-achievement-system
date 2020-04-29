@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header">建立群組</div>
                <div class="card-body">
                    <form action="/create-group" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="home-group-name">群組名稱</label>
                                <input type="text" name="name" id="home-group-name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="home-group-description">群組描述</label>
                                <input type="text" name="description" id="home-group-description" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary">新增</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">加入群組</div>
                <div class="card-body">
                    <form action="/enter-group" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <p>輸入群組 ID</p>
                                <input type="text" name="group-id" id="home-group-id" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary">新增</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
