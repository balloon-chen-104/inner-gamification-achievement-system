<div class="modal fade" id="reportTaskModalCenter" tabindex="-1" role="dialog" aria-labelledby="reportTaskModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportTaskModalCenterTitle">回報任務完成</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
            </div>
            <form id="report-task-form" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="text" name="task-id" id="task-id" style="display: none">
                    <input type="text" name="is-report" id="is-report" style="display: none">
                    <div class="form-group">
                        <p>任務內容：<strong id="task-name"></strong></p>
                    </div>
                    <div class="form-group">
                        <label for="task-report">心得回報</label>
                        <textarea name="report" class="form-control" id="task-report" cols="30" rows="5" placeholder="可選填"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">回報</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(() => {
        $("#report-task-form").submit((event) => {
            event.preventDefault();
            reportTask();
        });
    });
    function reportTask(){
        const data = {
            'id': $('#task-id').val(),
            'report': $('#task-report').val()
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
            url: `/api/v1/task/report`,
            dataType: 'json',
            data: JSON.stringify(data),
            success: (result) => {
                const taskId = result.task_id
                $('#success-msg').empty();
                $('#success-msg').append(`任務 ${$('#task-name').text()} 已回報成功`);
                $('#success-msg').slideToggle();
                $('#reportTaskModalCenter').modal('toggle');
                if($('#is-report').val() == 1){
                    $(`#report-${taskId}`).removeClass( 'btn-primary' ).addClass( 'btn-secondary' ).attr('disabled', true);
                    $(`#report-${taskId}`).parent().parent().children('td:eq(5)').empty().append('<span class="badge badge-primary">任務審核中</span>');
                } else {
                    let taskName = $(`#report-${taskId}`).parent().parent().children('td:eq(0)');
                    if(taskName.children('span').text()){
                        taskName.children('span').detach();
                    }

                    $('tbody:eq(1)').prepend(`
                        <tr data-category="${$(`#report-${taskId}`).parent().parent().data('category')}"  style="background-color:rgba(115, 134, 213,  0.2)">
                            <td>${taskName.text()}</td>
                            <td>${$(`#report-${taskId}`).parent().parent().children('td:eq(1)').text()}</td>
                            <td>${$(`#report-${taskId}`).parent().parent().children('td:eq(2)').text()}</td>
                            <td>${$(`#report-${taskId}`).parent().parent().children('td:eq(3)').text()}</td>
                            <td class="text-center">${$(`#report-${taskId}`).parent().parent().children('td:eq(4)').text()}</td>
                            <td><span class="badge badge-primary">任務審核中</span></td>
                            <td><button class="btn btn-sm btn-secondary" disabled>再回報</button></td>
                        </tr>
                    `);
                    $(`#report-${taskId}`).parent().parent().detach();
                }
                apiToken();
                setTimeout(()=>{
                    $('#success-msg').slideToggle();
                }, 2000);

            },
            error: (e) => {
                console.log("ERROR: ", e);
                $('#error-msg').empty();
                $('#error-msg').prepend(`任務回報失敗`);
                $('#error-msg').slideToggle();
                setTimeout(()=>{
                    $('#error-msg').slideToggle();
                }, 2000);
            },
        });
    }
</script>
