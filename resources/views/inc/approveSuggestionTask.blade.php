@auth
<script>
function approveSuggestionTask(id){
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
        url: `/api/v1/task/approve`,
        dataType: 'json',
        data: JSON.stringify(data),
        success: (result) => {
            const task = result.data
            $('#success-msg').empty();
            $('#success-msg').prepend(`任務提案 ${category.name} 已新增成功`);
            $('#success-msg').slideToggle();

            apiToken();
            $(`#current-task thead`).after(`
                <tbody>
                    <tr class="table-success" id="approve-task-${task.id}">
                        <td>${task.name}</td>
                        <td>${task.description}</td>
                        <td>${task.score}</td>
                        <td>${expiredAt}</td>
                        <td class="text-center">${task.remain_times}</td>
                        <td><button class="btn btn-sm btn-primary" onclick="getTask()">修改</button><td>
                    </tr>
                </tbody>
            `);
            // solve the problem that the above tbody add an extra td.
            $(`#edit-task-${task.id}`).children(':last').detach();;
            $('#edit-task').slideDown();
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
