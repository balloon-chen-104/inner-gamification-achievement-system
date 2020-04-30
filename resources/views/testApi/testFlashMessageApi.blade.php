@extends('layouts.app')

@section('content')
<div class="container">
    
    <p>Flash Message API 測試</p>
    <form id="add_flash_message">
        <input type="text" id="bulletin_content">
        <input type="hidden" id="bulletin_type" value="flash_message">
        <input type="submit" value="送出">
        @CSRF
    </form>

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
                // "Authorization": "Bearer {{ Auth::user()->api_token }}"
                "Authorization": "Bearer " + $("#api-token").val()
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
                apiToken();
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
