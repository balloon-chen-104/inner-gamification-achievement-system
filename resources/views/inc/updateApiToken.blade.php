<script>
function apiToken(){
    const data = {
        'id': {{ Auth::user()->id }},
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });
    $.ajax({
        type: "POST",
        contentType: "application/json",
        url: `/api/v1/token`,
        dataType: 'json',
        data: JSON.stringify(data),
        success: (result) => {
            $('#api-token').val(result.api_token);
        },
        error: (e) => {
            console.log("ERROR: ", e);
        },
    });
}
</script>
