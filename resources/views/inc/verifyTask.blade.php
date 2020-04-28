<script>
function verifyTask(id, name, report) {
    const data = {
        'id': id,
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
            const expiredAt = task.expired_at.split(" ")[0];
            $('#success-msg').empty();
            $('#success-msg').prepend(`${name} 完成的任務 ${task.name} 已審核通過`);
            $('#success-msg').slideToggle();
            if(typeof $('#verified-task').attr('id') == 'string'){
                $(`#verified-task thead`).after(`
                    <tbody id="category-${task.category.id}">
                        <tr class="table-success">
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
                `);
            } else {
                $('.card-body:last').empty().append(`
                    <table class="table table-striped" id="verified-task">
                        <thead class="thead-light">
                            <tr>
                                <td scope="col">任務名</td>
                                <td scope="col">敘述</td>
                                <td scope="col">分數</td>
                                <td scope="col">到期日</td>
                                <td scope="col">剩餘次數</td>
                                <td scope="col">完成任務者</td>
                            </tr>
                        </thead>
                        <tbody id="category-${task.category.id}">
                            <tr class="table-success">
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
            // solve the problem that the above tbody add an extra td.
            $(`#verifying-task-${task.id}`).parent().empty();
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
