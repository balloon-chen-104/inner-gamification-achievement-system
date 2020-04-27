<div class="modal fade" id="editTaskModalCenter" tabindex="-1" role="dialog" aria-labelledby="editTaskModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalCenterTitle">修改任務</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
            </div>
            <form id="edit-task-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="text" name="task-id" id="task-id" style="display: none">
                    <div class="form-row mb-3">
                        <div class="col mr-3">
                            <label for="task-name">任務名稱</label>
                            <input type="text" class="form-control" id="task-name">
                        </div>
                        <div class="col">
                            <label for="task-name">任務種類</label>
                            <select class="custom-select" name="" id="task-category">
                                @if ($group->categories->count() > 0)
                                    <option value="-1" selected disabled>選擇任務種類</option>
                                    @foreach ($group->categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                @else
                                    <option value="0" selected>尚無任務種類</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="task-description">任務內容描述</label>
                        <textarea name="task-description" class="form-control" id="task-description" cols="30" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="task-expired-at">任務到期日</label>
                        <input type="text" class="form-control datepicker" id="task-expired-at" required>
                    </div>
                    <div class="form-row">
                        <div class="col mr-3">
                            <label for="task-score">分數</label>
                            <input type="number" id="task-score" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="task-remain">剩餘次數</label>
                            <input type="number" id="task-remain" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">確認修改</button>
                </div>
            </form>
        </div>
    </div>
</div>
@auth
<script>
    $(document).ready(() => {
        $("#edit-task-form").submit((event) => {
            event.preventDefault();
            editTask();
        });
    });
    function editTask(){
        const data = {
            'name': $('#task-name').val(),
            'category_id': $("#task-category").find(":selected").val(),
            'description': $('#task-description').val(),
            'expired_at': $('#task-expired-at').val(),
            'score': $('#task-score').val(),
            'remain_times': $('#task-remain').val()
        };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'authorization': `Bearer ${$('#api-token').val()}`
            }
        });
        $.ajax({
            type: "PUT",
            contentType: "application/json",
            url: `/api/v1/task/${$('#task-id').val()}`,
            dataType: 'json',
            data: JSON.stringify(data),
            success: (result) => {
                const task = result.data
                console.log(task);
                const expiredAt = new Date(task.expired_at);
                let expiredYear = expiredAt.getFullYear();
                let expiredMonth = (expiredAt.getMonth() + 1 < 10)? `0${expiredAt.getMonth() + 1}`: expiredAt.getMonth() + 1;
                let expiredDate = (expiredAt.getDate()< 10)? `0${expiredAt.getDate()}`: expiredAt.getDate();
                let param = {'id': task.id,'name': task.name,'description': task.description,'score':task.score,'expired_at': task.expiredAt,'remain_times': task.remain_times};
                $('#success-msg').empty();
                $('#success-msg').prepend(`任務已修改`);
                $('#success-msg').slideToggle();
                $(`#edit-task-${task.id}`).empty().append(`
                    <td>${task.name}</td>
                    <td>${task.description}</td>
                    <td>${task.score}</td>
                    <td>${expiredYear}-${expiredMonth}-${expiredDate}</td>
                    <td class="text-center">${task.remain_times}</td>
                `);
                $(`#edit-task-${task.id}`).append('<td><button class="btn btn-sm btn-primary"' +
                    'onclick="getTask(' + JSON.stringify(param) + ')">修改</button></td>');

                $('#editTaskModalCenter').modal('toggle');
                apiToken();
                setTimeout(()=>{
                    $('#success-msg').slideToggle();
                }, 2000);

            },
            error: (e) => {
                console.log("ERROR: ", e);
                $('#error-msg').empty();
                $('#error-msg').prepend(`任務修改失敗`);
                $('#error-msg').slideToggle();
                setTimeout(()=>{
                    $('#error-msg').slideToggle();
                }, 2000);
            },
        });
    }
</script>
@endauth
