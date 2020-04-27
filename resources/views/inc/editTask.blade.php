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
                    <div class="form-row mb-3">
                        <div class="col mr-3">
                            <label for="task-name">任務名稱</label>
                            <input type="text" class="form-control" id="task-name">
                        </div>
                        <div class="col">
                            <label for="task-name">任務種類</label>
                            <select class="custom-select" name="" id="task-category">
                                @if ($group->categories->count() > 0)
                                    <option value="-1" selected disabled>選擇任務種類</option>
                                    @foreach ($group->categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                @else
                                    <option value="0" selected>尚無任務種類</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="task-description">任務內容描述</label>
                        <textarea name="task-description" class="form-control" id="task-description" cols="30" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="task-expired-at">任務到期日</label>
                        <input type="text" class="form-control datepicker" id="task-expired-at" required>
                    </div>
                    <div class="form-row">
                        <div class="col mr-3">
                            <label for="task-score">分數</label>
                            <input type="number" id="task-score" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="task-remain">剩餘次數</label>
                            <input type="number" id="task-remain" class="form-control" required>
                        </div>
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
