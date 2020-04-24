<div class="modal fade" id="enterGroupModalCenter" tabindex="-1" role="dialog" aria-labelledby="enterGroupModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGroupModalCenterTitle">加入群組</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="/" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <p>輸入群組 ID</p>
                        <input type="text" name="group-id" id="group-id" class="form-control" required>
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
