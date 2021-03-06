<div class="modal fade" id="addGroupModalCenter" tabindex="-1" role="dialog" aria-labelledby="addGroupModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGroupModalCenterTitle">新增群組</h5>
            <button type="button" id="addGroupModal-close" class="close" data-dismiss="modal" aria-label="Close">
            </button>
            </div>
            <form id="add-group-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="group-name">群組名稱</label>
                        <input type="text" name="name" id="group-name" class="form-control" required>
                        <span id="add-group-error-msg"></span>
                    </div>
                    <div class="form-group">
                        <label for="group-description">群組描述</label>
                        <input type="text" name="name" id="group-description" class="form-control" required>
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
<script>
$(document).ready(() => {
    $("#add-group-form").submit((event) => {
        event.preventDefault();
        addGroup();
    });
});
function addGroup(){
    const data = {
        'name': $('#group-name').val(),
        'description': $('#group-description').val(),
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
        url: `/api/v1/group`,
        dataType: 'json',
        data: JSON.stringify(data),
        success: (result) => {
            const group = result.data
            $('.components').append(`
                <li>
                    <a id="group_${group.id}" href="#"
                    onclick="event.preventDefault();
                        document.getElementById('active-group-form-${group.id}').submit();">
                        ${group.name}
                    </a>
                    <form id="active-group-form-${group.id}" action="/users/{{Auth::user()->id}}" method="POST" style="display: none;">
                        <input type="text" name="active_group" value="${group.id}">
                        @method('PUT')
                        @csrf
                    </form>
                </li>`);
            $('#addGroupModalCenter').modal('toggle');
            apiToken();
        },
        error: (e) => {
            console.log("ERROR: ", e.responseJSON.message);
            $('#add-group-error-msg').css('color', 'red').html(e.responseJSON.message).show();
        },
    });
}
</script>
