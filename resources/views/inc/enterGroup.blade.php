@auth
<div class="modal fade" id="enterGroupModalCenter" tabindex="-1" role="dialog" aria-labelledby="enterGroupModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGroupModalCenterTitle">加入群組</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
            </div>
            <form id="enter-group-form" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <p>輸入群組 ID</p>
                        <input type="text" name="group-id" id="group-id" class="form-control" required>
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
    $("#enter-group-form").submit((event) => {
        event.preventDefault();
        addGroup();
    });
});
function addGroup(){
    const data = {
        'group_token': $('#group-id').val(),
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'authorization': `Bearer {{Auth::user()->api_token}}`
        }
    });
    $.ajax({
        type: "POST",
        contentType: "application/json",
        url: `/api/v1/group/enter`,
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
                    <form id="active-group-form-${group.id}" action="users/{{Auth::user()->id}}" method="POST" style="display: none;">
                        <input type="text" name="active_group" value="${group.id}">
                        @method('PUT')
                        @csrf
                    </form>
                </li>`);
            $('#enterGroupModalCenter').modal('toggle');
        },
        error: (e) => {
            console.log("ERROR: ", e);
            $('#enterGroupModalCenter').modal('toggle');
        },
    });
}
</script>
@endauth
