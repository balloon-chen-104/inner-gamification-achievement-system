@auth
<script>
    $(document).ready(() => {
        $("#add-task-form").submit((event) => {
            event.preventDefault();
            addTask();
        });
    });
    function addTask(){
        const data = {
            'name': $('#add-task-name').val(),
            'category_id': $("#add-task-category").find(":selected").val(),
            'description': $('#add-task-description').val(),
            'expired_at': $('#add-task-expired-at').val(),
            'score': $('#add-task-score').val(),
            'remain_times': $('#add-task-remain').val()
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
            url: `/api/v1/task`,
            dataType: 'json',
            data: JSON.stringify(data),
            success: (result) => {
                const task = result.data
                console.log(task);
                const expiredAt = task.expired_at.split(" ")[0];
                let param = {'id': task.id,'name': task.name,'description': task.description,'score':task.score,'expired_at': task.expiredAt,'remain_times': task.remain_times};
                $('#success-msg').empty();
                $('#success-msg').prepend(`任務 ${task.name} 新增成功`);
                $('#success-msg').slideToggle();
                $(`#current-task thead`).after(`
                    <tbody class="table-success" id="category-${task.category.id}">
                        <td>${task.name}</td>
                        <td>${task.description}</td>
                        <td>${task.score}</td>
                        <td>${expiredAt}</td>
                        <td class="text-center">${task.remain_times}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="getTask(${JSON.stringify(task)})">
                                修改
                            </button>
                        <td>
                    </tbody>
                `);
                $('#edit-task').slideDown();

                apiToken();
                setTimeout(()=>{
                    $('#success-msg').slideToggle();
                }, 2000);

            },
            error: (e) => {
                console.log("ERROR: ", e);
                $('#error-msg').empty();
                $('#error-msg').prepend(`任務新增失敗`);
                $('#error-msg').slideToggle();
                setTimeout(()=>{
                    $('#error-msg').slideToggle();
                }, 2000);
            },
        });
    }
</script>
@endauth
