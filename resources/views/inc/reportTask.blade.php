<div class="modal fade" id="reportTaskModalCenter" tabindex="-1" role="dialog" aria-labelledby="reportTaskModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportTaskModalCenterTitle">回報任務完成</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
            </div>
            <form action="/" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="text" name="task-id" id="task-id" style="display: none">
                    <div class="form-group">
                        <p>任務內容：<strong id="task-name"></strong></p>
                    </div>
                    <div class="form-group">
                        <label for="task-report">心得回報</label>
                        <textarea name="task-report" class="form-control" id="task-report" cols="30" rows="5" placeholder="可選填"></textarea>
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
