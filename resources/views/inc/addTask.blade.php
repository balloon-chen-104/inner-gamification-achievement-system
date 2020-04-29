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
            'remain_times': $('#add-task-remain').val(),
            'confirmed': $("#add-task-form").data('confirmed')
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
                const expiredAt = task.expired_at.split(" ")[0];
                let param = {'id': task.id,'name': task.name,'description': task.description,'score':task.score,'expired_at': task.expiredAt,'remain_times': task.remain_times};
                $('#success-msg').empty();
                $('#success-msg').prepend(`任務 ${task.name} 新增成功`);
                $('#success-msg').slideToggle();
                if(typeof $('#current-task').attr('id') == 'string'){
                    let addRow = '';
                    if($("#add-task-form").data('confirmed') == 1) {
                        addRow += `<tr data-category="${task.category.id}" id="edit-task-${task.id}" style="background-color:rgba(115, 134, 213,  0.2)">
                            <td>${task.name}</td>
                            <td>${task.description}</td>
                            <td>${task.score}</td>
                            <td>${expiredAt}</td>
                            <td class="text-center">${task.remain_times}</td>
                            <td><button class="btn btn-sm btn-primary" onclick="getTaskInEdit(this, 1)">修改</button><td>
                        </tr>`;
                        $(`#current-task tbody`).prepend(addRow);
                        // solve the problem that the above tbody add an extra td.
                        $(`#edit-task-${task.id}`).children(':last').detach();
                    } else {
                        addRow += `<tr data-category="${task.category.id}" id="edit-task-${task.id}" style="background-color:rgba(115, 134, 213,  0.2)">
                            <td>${task.name}</td>
                            <td>${task.description}</td>
                            <td>${task.score}</td>
                            <td>${expiredAt}</td>
                            <td class="text-center">${task.remain_times}</td>
                            <td><span class="badge badge-primary">提案審核中</span></td>
                            <td><button class="btn btn-sm btn-secondary" disabled>修改</button></td>
                        </tr>`;
                        $(`#current-task tbody`).prepend(addRow);
                    }
                } else {
                    if($("#add-task-form").data('confirmed') == 1) {
                        $('.card-body:eq(3)').empty().append(`
                            <table class="table table-hover" id="current-task">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">任務名</td>
                                        <th scope="col">敘述</td>
                                        <th scope="col">分數</td>
                                        <th scope="col">到期日</td>
                                        <th scope="col">剩餘次數</td>
                                        <th scope="col">修改任務</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-category=${task.category.id} id="edit-task-${task.id}" style="background-color:rgba(115, 134, 213,  0.2)">
                                        <td>${task.name}</td>
                                        <td>${task.description}</td>
                                        <td>${task.score}</td>
                                        <td>${expiredAt}</td>
                                        <td class="text-center">${task.remain_times}</td>
                                        <td><button class="btn btn-sm btn-primary" onclick="getTaskInEdit(this, 1)">修改</button><td>
                                    </tr>
                                </tbody>
                            </table>
                        `)
                    } else {
                        $('.card-body:eq(1)').empty().append(`
                            <table class="table table-hover" id="current-task">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">任務名</th>
                                        <th scope="col">敘述</th>
                                        <th scope="col">分數</th>
                                        <th scope="col">到期日</th>
                                        <th scope="col">剩餘次數</th>
                                        <th scope="col">提案狀態</th>
                                        <th scope="col">修改再提交</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-category=${task.category.id} id="edit-task-${task.id}" style="background-color:rgba(115, 134, 213,  0.2)">
                                        <td>${task.name}</td>
                                        <td>${task.description}</td>
                                        <td>${task.score}</td>
                                        <td>${expiredAt}</td>
                                        <td class="text-center">${task.remain_times}</td>
                                        <td><span class="badge badge-primary">提案審核中</span></td>
                                        <td><button class="btn btn-sm btn-secondary" disabled>修改</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        `);
                    }
                }
                if($("#add-task-form").data('confirmed') == 1) {
                    $('.card-body:eq(3)').slideDown();
                } else {
                    $('.card-body:eq(1)').slideDown();

                }

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
