<section class="content" style="padding: 10px">
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">搜索</h3>
                </div>
                <div class="card-body">
                    <form role="form" data-form="1" data-search-table-id="1" data-title="自定义标题">
                        <div class="form-inline">
                            <div class="form-group">
                                <label>标题：</label>
                                <input type="text" class="form-control" name="feedback_title" placeholder="请输入反馈消息标题">
                            </div>
                            <div class="form-group">
                                <label>类型：</label>
                                <select class="selectpicker" name="feedback_type" id="feedback_type">
                                    {:options,$typeOptions}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>级别：</label>
                                <select class="selectpicker" name="level" id="level">
                                    {:options,$levelOptions}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>状态：</label>
                                <select class="selectpicker" name="status" id="status">
                                    {:options,$statusOptions}
                                </select>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-group" style="margin-left: 10px">
                                <button type="submit" class="btn btn-primary">提交</button>
                                <button type="reset" class="btn btn-default">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="content">
    <div class="card-body table-responsive" style="padding-top: 0px">
        <table data-table="1" data-url="?app=feedback@ajax_data&__addons={$__addons}" id="table1" class="table table-hover">
            <thead>
            <tr>

                <th data-field="feedback_title">标题</th>
                <th data-field="type_desc">类型</th>
                <th data-field="level_desc">级别</th>
                <th data-field="from_uname">提交人</th>
                <th data-field="status_desc">状态</th>
                <th data-field="create_time">添加时间</th>
                <th data-formatter="epiiFormatter.btns"
                    data-intop="1"
                    data-area="50%,70%"
                    data-btns="myDetail,del"
                    data-del-url="?app=feedback@del&id={id}&__addons={$__addons}"
                    data-del-title="删除"
                >操作
                </th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    function example(field_value, row, index, field_name) {
        return '<a class="btn btn-outline-primary btn-sm" data-url="?app=feedback@detail&id=' + row.wxid + '">示例</a>';
    }

    function myDetail(field_value, row, index, field_name) {
        return '<a class="btn btn-outline-primary btn-sm btn-dialog" data-intop="1" data-area="50%,70%" href="?app=feedback@detail&id=' + row.id + '&__addons={$__addons}">详情</a>';
    }
</script>