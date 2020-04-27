@auth
<script>
$(document).ready(() => {
    $("#add-category-form").submit((event) => {
        event.preventDefault();
        addCategory();
    });
});
function addCategory(){
    const data = {
        'name': $('#category-name').val(),
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'authorization': `Bearer ${$('#api-token').val()}`
        }
    });
    $.ajax({
        type: "POST",
        contentType: "application/json",
        url: `/api/v1/category`,
        dataType: 'json',
        data: JSON.stringify(data),
        success: (result) => {
            const category = result.data
            $('#success-msg').empty();
            $('#success-msg').prepend(`任務種類 ${category.name} 已新增成功`);
            $('#success-msg').slideToggle();
            $('#category-name').val('');
            toggleCategory();
            if($('.custom-select').val() != undefined) {
                $('.custom-select').append(`<option value="${category.id}">${category.name}</option>
                `);
            }
            apiToken();
            setTimeout(()=>{
                $('#success-msg').slideToggle();
            }, 2000);

        },
        error: (e) => {
            console.log("ERROR: ", e);
            $('#error-msg').empty();
            $('#error-msg').prepend(`任務種類新增失敗`);
            $('#error-msg').slideToggle();
            setTimeout(()=>{
                $('#error-msg').slideToggle();
            }, 2000);
        },
    });
}
</script>
@endauth
