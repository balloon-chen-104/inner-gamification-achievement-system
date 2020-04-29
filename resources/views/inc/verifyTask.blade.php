<script>
function verifyTask(e, task_id, user_id, name, report, confirmed) {
    const data = {
        'task_id': task_id,
        'user_id': user_id,
        'confirmed': confirmed
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
        url: `/api/v1/task/verify`,
        dataType: 'json',
        data: JSON.stringify(data),
        success: (result) => {
            const task = result.data;
            if(confirmed == 1) {

                const expiredAt = task.expired_at.split(" ")[0];
                $('#success-msg').empty();
                $('#success-msg').prepend(`${name} 完成的任務 ${task.name} 已審核通過`);
                $('#success-msg').slideToggle();
                if(typeof $('#verified-task').attr('id') == 'string'){
                    $(`#verified-task tbody`).prepend(`
                        <tr data-category="${task.category.id}" style="background-color:rgba(115, 134, 213,  0.2)">
                            <td>${task.name}</td>
                            <td>${task.description}</td>
                            <td>${task.score}</td>
                            <td>${expiredAt}</td>
                            <td class="text-center">${task.remain_times}</td>
                            <td>
                                <span style="font:bold; color:blue; cursor:pointer" onclick="getReport(this)">${name}</span>
                                <span style="display:none">${report}</span>
                            </td>
                        </tr>
                    `);
                } else {
                    $('.card-body:last').empty().append(`
                        <table class="table table-hover" id="verified-task">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">任務名</td>
                                    <th scope="col">敘述</td>
                                    <th scope="col">分數</td>
                                    <th scope="col">到期日</td>
                                    <th scope="col">剩餘次數</td>
                                    <th scope="col">完成任務者</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-category="${task.category.id}" style="background-color:rgba(115, 134, 213,  0.2)">
                                    <td>${task.name}</td>
                                    <td>${task.description}</td>
                                    <td>${task.score}</td>
                                    <td>${expiredAt}</td>
                                    <td class="text-center">${task.remain_times}</td>
                                    <td>
                                        <span style="font:bold; color:blue; cursor:pointer" onclick="getReport(this)">${name}</span>
                                        <span style="display:none">${report}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    `);
                }
            } else {
                $('#success-msg').empty();
                $('#success-msg').prepend(`${name} 完成的任務 ${task.name} 審核不通過，已經駁回`);
                $('#success-msg').slideToggle();
            }
            $(e).parent().parent().detach();
            if($('tbody:eq(0) tr:last').index() + 1 == 0) {
                $('table:eq(0)').detach();
                $('.card-body:eq(0)').append('目前沒有待審核任務');
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
