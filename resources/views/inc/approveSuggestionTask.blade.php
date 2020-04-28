@auth
<script>
function approveSuggestionTask(id, confirmed){
    const data = {
        'id': id,
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
        url: `/api/v1/task/approve`,
        dataType: 'json',
        data: JSON.stringify(data),
        success: (result) => {
            const task = result.data;
            if(confirmed == 1) {
                const expiredAt = task.expired_at.split(" ")[0];
                $('#success-msg').empty();
                $('#success-msg').prepend(`任務提案 ${task.name} 已新增`);
                $('#success-msg').slideToggle();
                if(typeof $('#current-task').attr('id') == 'string'){
                    $(`#current-task thead`).after(`
                        <tbody id="category-${task.category.id}">
                            <tr class="table-success" id="edit-task-${task.id}">
                                <td>${task.name}</td>
                                <td>${task.description}</td>
                                <td>${task.score}</td>
                                <td>${expiredAt}</td>
                                <td class="text-center">${task.remain_times}</td>
                                <td><button class="btn btn-sm btn-primary" onclick="getTask(this)">修改</button><td>
                            </tr>
                        </tbody>
                    `);
                    // solve the problem that the above tbody add an extra td.
                    $(`#edit-task-${task.id}`).children(':last').detach();
                } else {
                    $('.card-body:eq(3)').empty().append(`
                        <table class="table table-striped" id="current-task">
                            <thead class="thead-light">
                                <tr>
                                    <td scope="col">任務名</td>
                                    <td scope="col">敘述</td>
                                    <td scope="col">分數</td>
                                    <td scope="col">到期日</td>
                                    <td scope="col">剩餘次數</td>
                                    <td scope="col">修改任務</td>
                                </tr>
                            </thead>
                            <tbody id="category-${task.category.id}">
                                <tr class="table-success" id="edit-task-${task.id}">
                                    <td>${task.name}</td>
                                    <td>${task.description}</td>
                                    <td>${task.score}</td>
                                    <td>${expiredAt}</td>
                                    <td class="text-center">${task.remain_times}</td>
                                    <td><button class="btn btn-sm btn-primary" onclick="getTask(this)">修改</button><td>
                                </tr>
                            </tbody>
                        </table>
                    `)
                }
                $('.card-body:eq(3)').slideDown();
            } else {
                $('#success-msg').empty();
                $('#success-msg').prepend(`任務提案已駁回`);
                $('#success-msg').slideToggle();
            }
            $(`#approve-task-${task.id}`).parent().empty();
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
