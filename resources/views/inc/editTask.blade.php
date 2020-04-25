<div class="modal fade" id="editTaskModalCenter" tabindex="-1" role="dialog" aria-labelledby="editTaskModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalCenterTitle">修改任務</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
            </div>
            <form action="/" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="text" name="task-id" id="task-id" style="display: none">
                    <div class="form-group">
                        <label for="task-name">任務名稱</label>
                        <input type="text" class="form-control" id="task-name">
                    </div>
                    <div class="form-group">
                        <label for="task-report">任務內容描述</label>
                        <textarea name="task-report" class="form-control" id="task-description" cols="30" rows="2" placeholder="可選填"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="task-expired-at">任務到期日</label>
                        <input type="text" class="form-control datepicker" id="task-expired-at">
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
