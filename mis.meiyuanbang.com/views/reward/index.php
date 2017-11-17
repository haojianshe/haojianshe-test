<?php

use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
 <link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
         <th colspan="12" >
          <div class="button-group" >
          <a href="/reward/prizegame" class="button button-small ">抽奖管理</a>
            <a href="/reward/index" class="button  button-primary  button-small">奖品列表</a>
             <!--<a href="/reward/userlist" class="button  button-small">获奖名单</a>-->
          </div>
        </th>
        <tr class="operate">
            <th colspan="2" >
                共有<?php echo $pages->totalCount ?>条记录
            </th>
            <th colspan="4" style='text-align:right;'>
                <input type="button" id="rewardNew" value="新建奖品" class="button button-primary  button-small"/>
                <a  href="/dkactivity/index"><input type="button" id="btnnew" value="返回大卡" class="button button-primary  button-small "/></a>
            </th>
        </tr>
        <tr class="tb_header">
            <th >活动编号</th>
            <th >标题</th>
            <th >图片</th>
            <th >类型</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <?php foreach ($models as $model) { ?>
        <tr class="tb_list">
            <td><?php echo isset($model['prizesid'])?$model['prizesid']:""; ?></td>
            <td><?php echo isset($model['title'])?$model['title']:""; ?></td>
            <td>
                <img style="width:40px;height:40px;padding:3px;" src="<?php echo isset($model['img'])?$model['img']:""; ?>">
            </td>
            <td><?php
                if (@$model['type'] == 1) {
                    echo '金币';
                } elseif (@$model['type'] == 2) {
                    echo '虚拟物品';
                } elseif (@$model['type'] == 3) {
                    echo '实物';
                }
                ?></td>
            <td><?php
                if (@$model['ctime']) {
                    echo date('Y-m-d H:i', $model['ctime']);
                }
                ?> 
            </td>
            <td>
                <a name='aedit' prizesid='<?= @$model['prizesid'] ?>'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a name='adel' prizesid='<?= @$model['prizesid'] ?>' >删除</a>&nbsp;&nbsp;&nbsp;&nbsp;

            </td>
        </tr>
        <?php
    }
    ?>
    <tr class="operate">
        <td colspan="6">
            <div class="cuspages right">
                <?= MyLinkPager::widget(['pagination' => $pages]); ?>
            </div>      
        </td>
    </tr>
</table>
<div id="_tips"></div>
<script>
//新建按钮绑定事件
    $('#rewardNew').on('click', function () {
        addedit(0);
    });
    $('a[name=aedit]').on('click', function () {
        addedit($(this).attr("prizesid"));
    });
//编辑或新增用户页面
    function addedit(id) {
        var content = '/reward/edit';
        var title = '添加奖品';
        content = content + '?prizesid=' + id;
        title = '添加奖品';
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['40%', '60%'],
            content: content
        });
    }
//删除按钮绑定事件
    $("a[name=adel]").click(function () {
        var prizesid = $(this).attr("prizesid");
        layer.confirm('删除后将不可恢复，确定删除吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/reward/del",
                data: "prizesid=" + prizesid, //要发送的数据                    
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


</script>