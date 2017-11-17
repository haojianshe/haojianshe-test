<?php

use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="5" >
                共有<?= $pages->totalCount; ?>条记录
            </th>
            <th colspan="2" style='text-align:right;'>
                <input type="button" id="prizeid" value="奖品管理" class="button"/>
                <input type="button" id="btnnew" value="新建活动" class="button"/>
            </th>
        </tr>
        <tr class="tb_header">
            <th >活动编号</th>
            <th >开始时间</th>
            <th >邀请截止时间</th>
            <th >领奖截止时间</th>
            <th >发布人</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['invitation_id'] ?></td>
        <td><?= date('Y-m-d H:i:s', $model['btime']); ?></td>
        <td><?= date('Y-m-d H:i:s', $model['etime']); ?></td>
        <td><?= date('Y-m-d H:i:s', $model['award_time']); ?></td>
        <td><?= $model['username'] ?></td>
        <td><?= date('Y-m-d H:i:s', $model['ctime']); ?></td>
        <td>
            <a href='<?= Yii::$app->params['msiteurl'] . '/mactivity/invitation/invitation_list?status=1' ?>' target='_blank'>预览</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a name='aedit' invitation_id='<?= $model['invitation_id'] ?>'   style="cursor:pointer;">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a name='invitation_record' invitation_id='<?= $model['invitation_id'] ?>' style="cursor:pointer;">邀请记录</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a name='award_record' invitation_id='<?= $model['invitation_id'] ?>' style="cursor:pointer;">领奖记录</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a name='adel' invitation_id='<?= $model['invitation_id'] ?>' style="cursor:pointer;" >删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
    <?}?>
    <tr class="operate">
        <td colspan="8">
            <div class="cuspages right">
<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
            </div>      
        </td>
    </tr>
</table>
<div id="_tips"></div>
<script>
    //奖品管理
        $('#prizeid').on('click', function () {
            prize();
        });
        function prize(){
           window.location.href='/invitation/prize_list';
        }
   //新建按钮绑定事件
    $('#btnnew').on('click', function () {
        addedit(0);
    });
    //邀请记录
    $("a[name=invitation_record]").click(function () {
        var invitation_id = $(this).attr("invitation_id");
          window.location.href='/invitation/invitation_record?invitation_id='+invitation_id;
    });
    
    //领奖记录
    $("a[name=award_record]").click(function () {
        var invitation_id = $(this).attr("invitation_id");
          window.location.href='/invitation/award_record?invitation_id='+invitation_id;
    });

   //编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
        addedit($(this).attr("invitation_id"));
        return false;
    });
 
   //删除按钮绑定事件
    $("a[name=adel]").click(function () {
        var invitation_id = $(this).attr("invitation_id");
        layer.confirm('删除后将不可恢复，确定删除吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/invitation/del",
                data: "invitation_id=" + invitation_id, //要发送的数据                    
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

//编辑或新增用户页面
    function addedit(invitation_id) {
        var content = '/invitation/edit';
        var title = '添加活动';
        if (invitation_id > 0) {
            content = content + '?invitation_id=' + invitation_id;
            title = '编辑活动--编号:' + invitation_id;
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['70%', '100%'],
            content: content
        });
    }
</script>