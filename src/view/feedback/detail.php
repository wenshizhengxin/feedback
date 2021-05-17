<form role="form" class="epii" method="post" data-form="1" action="">

    <div class="form-group">
        <label>标题：</label>
        <input type="text" class="form-control" value="{$feedback['feedback_title'] ? ""}" readonly>
    </div>

    <div class="form-group">
        <label>类型：</label>
        <input type="text" class="form-control" value="{$feedback['type_desc'] ? ""}" readonly>
    </div>

    <div class="form-group">
        <label>级别：</label>
        <input type="text" class="form-control" value="{$feedback['level_desc'] ? ""}" readonly>
    </div>

    <div class="form-group">
        <label>状态：</label>
        <input type="text" class="form-control" value="{$feedback['status_desc'] ? ""}" readonly>
    </div>

    <div class="form-group">
        <label>描述：</label>
        <div class="form-group custom-date">
            <textarea name="description" cols="125" rows="5" style="width: 90%;" placeholder="请输入反馈描述" readonly>{$feedback['description'] ? ""}</textarea>
        </div>
    </div>

    <div class="form-group" id="图片" style="text-align: center; ">
        <label for="class">图片：</label>
        <div data-upload-preview="1"
             data-input-id="img"
             data-multiple="0"
             data-mimetype="pdf,jpg,png,jpeg,gif"
             style="width: 70%; margin: 0 0"></div>
        <input type="hidden" name="img" id="img" value="{? $feedback['img']}" data-src="{? $feedback['show_url']}">
    </div>


    <div class="form-footer">
        <button type="button" class="btn btn-primary" onclick="changeStatus(<?php echo \wenshizhengxin\feedback\libs\Constant::STATUS_FINISHED ?>)">
            标记为已解决
        </button>
        <button type="button" class="btn btn-danger" onclick="changeStatus(<?php echo \wenshizhengxin\feedback\libs\Constant::STATUS_RETURNED ?>)">
            退回
        </button>
    </div>
</form>

<script src="http://res.cmq2080.top/index.php?dir=js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
    function changeStatus(status) {
        if (!confirm("确定吗？")) {
            return;
        }
        $.ajax({
            type: "POST",
            url: "?app=feedback@change_status&__addons1={$__addons}",
            dataType: "json",
            async: false,
            data: {
                "id": "{$feedback['id']}",
                "status": status
            },
            success: function (res) {
                alert(res.msg);
                if (res.code === 1) {
                    window.location.reload();
                }
            }
        });
    }
</script>