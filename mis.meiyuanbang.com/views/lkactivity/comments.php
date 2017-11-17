<?php

use common\widgets\MyLinkPager;
use common\service\DictdataService;
use common\service\CommonFuncService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>

<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- 图片浏览 引入结束-->
<style type="text/css">
    td{
        max-width: 300px;
    }
</style>
<table cellspacing="0" cellpadding="0" class="content_list">
    <!--标题  -->
    <thead>
        <tr class="operate">
            <th colspan="7" >
                共有<?= count($models) ?>条记录
            </th>
        </tr>
        <tr class="tb_header">
            <th >评论编号</th>
            <th >用户</th>
            <th >类型</th>
            <th >内容</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <?php
    $array = [
        0 => "帖子",
        1 => "专家动态评论",
        2 => "小组讨论",
        3 => "文章",
        4 => "考点",
        5 => "活动",
        6 => "正能文章"
    ];
    ?>
    <!-- 列表 -->
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['cid'] ?></td>
        <td><?= $model['sname'] ?></td>
        <td><?php
            foreach ($array as $key => $val) {
                if ($model['subjecttype'] == $key) {
                    echo $val;
                }
            }
            ?></td>
        <td><?= $model['content'] ?></td>
        <td><?= date('Y-m-d H:i', $model['ctime']) ?></td>
        <td>
            <a name='adel' onclick='del(<?= $model['cid'] ?>,1)' href='#'>删除</a>
        </td>
    </tr>
    <?}?>
    <!-- 分页 -->
    <tr class="operate">
        <td colspan="6">
            <div class="cuspages right">
<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
            </div>      
        </td>
    </tr>
</table>

<!-- 页面操作逻辑  开始-->
<script type="text/javascript">


    //删除能力素材
    function del(cid, is_del) {
        var msg = '';
        if (is_del == 1) {
            msg = "是否删除？";
        }
        layer.confirm(msg, {
            btn: ['确定', '否'] //按钮
        }, function () {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/lkactivity/delcomment",
                data: "cid=" + cid + "&is_del=" + is_del, //要发送的数据                    
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

        });
    }
</script>

