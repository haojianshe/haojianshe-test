<?php

use common\widgets\MyLinkPager;
use mis\service\UserService;
use common\service\dict\LiveDictService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="2" >
                共有<?= $pages->totalCount ?>条记录
            </th>
            <th colspan="9" style="text-align: right;">
                <input type="submit"  name="search" class="button" value="添加推荐" onclick="addedit(0);">
                <!--<input type="submit"  name="search" class="button" value="返回列表" onclick="goback()">-->
            </th>
        </tr>
    <style type="text/css">
    </style>
    <tr class="tb_header">
        <th style="width: 100px;">直播编号</th>
        <th style="width: 100px;">标题</th>
        <th style="width: 100px;">用户名</th>
        <th style="width: 100px;">分类</th>
        <th style="width: 100px;">排序</th>
        <th style="width: 100px;">创建时间</th>
        <th style="width: 100px;">创建人</th>
        <th style="width: 100px;">操作</th>
    </tr>
</thead>
<?php
#print_r($models);
?>
<!-- 列表 -->
<? foreach ($models as $model) { ?>
<tr class="tb_list">
    <td><?= $model['liveid'] ?></td>
    <td><?= $model['live_title'] ?></td>
    <td><?php
        echo UserService::findOne(['uid' => $model['teacheruid']])->sname;
        ?></td>
    <td><?= LiveDictService::getCorrectMainTypeNameById($model['f_catalog_id']); ?>-<?php
        $catalogid = LiveDictService::getCorrectSubType($model['s_catalog_id']);
        foreach ($catalogid as $key => $val) {
            foreach ($val as $k => $v) {
                if ($model['s_catalog_id'] == $k) {
                    echo $v;
                }
            }
        }
        ?></td>
    <td><input type="text" value="<?php echo $model['sort_id']; ?>" name="sort_id" size="3" liverecid="<?php echo $model['liverecid'] ?>"/>  </td>
    <td><?php echo date("Y-m-d H:i:s", $model['ctime']); ?></td>
    <td><?php
        echo $model['username'];
        ?></td>
    <td>
        <a name='del' href="javascript:"   liveid="<?= $model['liverecid'] ?>" >删除</a>&nbsp;&nbsp;
    </td>
</tr>
<?}?>

<!-- 分页 -->
<tr class="operate">
    <td colspan="9">
        <div class="cuspages right">
            <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>      
    </td>
</tr>
</table>

<!-- 页面操作逻辑  开始-->
<script type="text/javascript">
    function goback() {
        window.location.href = "/live/index";
    }

    $("input[name=sort_id]").blur(function () {
        var liveid = $(this).attr("liverecid");
        var value = $(this).val();
        layer.confirm('确定要操作吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/live/editrem",
                data: "liveid=" + liveid+"&value="+value, //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("访问出错", {icon: 2});
                }
            });
        }, function () {
            //取消
        });
        return false;

    });

    //删除按钮绑定事件
    $("a[name=del]").click(function () {
        var liveid = $(this).attr("liveid");
        layer.confirm('删除后将不可恢复，确定删除吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/live/delrem",
                data: "liveid=" + liveid, //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("访问出错", {icon: 2});
                }
            });
        }, function () {
            //取消
        });
        return false;
    });

    //编辑
    function addedit(id) {
        var content = '/live/addrecommend';
        var title = '添加推荐';
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['90%', '90%'],
            content: content
        });
    }
</script>
<!-- 页面操作逻辑 结束-->