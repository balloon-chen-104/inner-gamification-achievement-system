@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">新增快訊</div>
                <div class="card-body">
                    
                    <form id="add_flash_message">
                        <input type="text" id="bulletin_content" class="form-control mb-2" required>
                        <input type="hidden" id="bulletin_type" value="flash_message">
                        <input type="submit" class="btn btn-secondary btn-sm" value="送出">
                        <a href="/setting" class="btn btn-outline-secondary btn-sm">返回</a>
                        @CSRF
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript">

    function ajaxPost(){
        const data = {
            'content': $('#bulletin_content').val(),
            'type': $('#bulletin_type').val()
        };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                "Authorization": "Bearer {{ Auth::user()->api_token }}"
            }
        });
        $.ajax({
            type: "POST",
            contentType: "application/json",
            url: '/api/v1/flashMessage',
            dataType: 'json',
            data: JSON.stringify(data),
            success: (bulletin) => {
                console.log(
                    "資料建立成功！"+"\n"+
                    "content: "+bulletin.content+"\n"+
                    "type: "+bulletin.type+"\n"+
                    "group_id: "+bulletin.group_id+"\n"+
                    "user_id: "+bulletin.user_id
                );
                window.location.replace("/setting");
            },
            error: (e) => {
                console.log("ERROR: ", e);
            },
        });
    }

    $(document).ready(function () {
        $("#add_flash_message").submit((event) => {
            event.preventDefault();
            ajaxPost();
        });
    });
    
</script>

@endsection
