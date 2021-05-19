<form role="form" class="epii" method="post" data-form="1" action="">

    <div class="form-group">
        <label>标题：</label>
        <input type="text" class="form-control" name="feedback_title" placeholder="请输入反馈消息标题" value="{$feedback['feedback_title'] ? ""}">
    </div>

    <div class="form-group">
        <label>类型：</label>
        <select class="selectpicker" name="feedback_type" id="feedback_type">
            {:options,$typeOptions,$feedback['feedback_type']?}
        </select>
    </div>

    <div class="form-group">
        <label>级别：</label>
        <select class="selectpicker" name="level" id="level">
            {:options,$levelOptions,$feedback['level']?wenshizhengxin\feedback\libs\Constant::LEVEL_NORMAL}
        </select>
    </div>

    <div class="form-group" id="图片" style="text-align: center; ">
        <label for="class">logo：</label>
        <div data-upload-preview="1"
             data-input-id="img"
             data-multiple="0"
             data-mimetype="pdf,jpg,png,jpeg,gif"
             data-maxcount=1 style="width: 70%; margin: 0 0"></div>
        <input type="hidden"
               name="img"
               id="img"
               value="{? $feedback['url']}"
               data-src="{? $feedback['show_url']}">
    </div>


    <div class="form-group">
        <label>描述：</label>
        <textarea name="description" class="form-control" rows="5" placeholder="请输入反馈描述">{$feedback['description'] ? ""}</textarea>
    </div>


    <div class="form-footer" style="margin-bottom: 2rem">
        <input type="hidden" name="id" value="{$feedback['id'] ? 0}">
        <button type="submit" class="btn btn-primary">提交</button>
    </div>
</form>