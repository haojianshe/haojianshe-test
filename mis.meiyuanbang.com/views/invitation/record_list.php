<?php
use common\widgets\MyLinkPager;
use mis\service\InvitationRecordService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="4" >
                邀请记录—活动编号：<?php
                echo $models[0]['invitation_id']?> &nbsp;&nbsp;&nbsp;&nbsp;共有<?= $pages->totalCount; ?>条记录
            </th>
            <th colspan="2" style='text-align:right;'>
                <!--<input type="button" id="newCreatePrize" value="新建奖品" class="button"/>-->
                <input type="button" id="prizeList" value="返回列表" class="button"/>
            </th>
        </tr>
        <tr class="tb_header">
            <th >邀请编号</th>
            <th >邀请人电话</th>
            <th >被邀请人电话</th>
            <th >创建时间</th>
            <th >邀请状态</th>
            <th >生效时间</th>
            <!--<th >操作</th>-->
        </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
         <td><?= $model['record_id'] ?></td>
         <td><?= $model['umobile'] ?></td>
         <td><?=$model['invitee_phone']?></td>
         <td><?= date('Y-m-d H:i:s', $model['ctime']); ?></td>
         <td><?php 
             if(InvitationRecordService::getTakeTime($model['invitation_id'], $model['invitee_uid']) && $model['invitee_uid']){
                  echo "<span style='color:green'>已生效</span>";
             }else{
                  echo "<span style='color:red'>未生效</span>";
               }
             
             ?></td>
         <td><?php // InvitationRecordService::getTakeTime($model['invitation_id'], $model['invitation_uid']);
         if($model['invitee_uid']){
             if(InvitationRecordService::getTakeTime($model['invitation_id'], $model['invitee_uid'])){
               echo date('Y-m-d H:i:s',InvitationRecordService::getTakeTime($model['invitation_id'], $model['invitee_uid']));
            } 
         }
         ?></td>
         <!--<td><a name='aedit' prizes_id='<?//= $model['record_id'] ?>' >编辑</a>&nbsp;&nbsp;</td>-->
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
        $('#prizeList').on('click', function () {
            prize();
        });
        function prize(){
           window.location.href='/invitation';
        }
  //新建按钮绑定事件
    $('#newCreatePrize').on('click', function () {
        addedit(0);
    });
  //编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
       addedit($(this).attr("prizes_id"));
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
    function addedit(prizes_id) {
        var content = '/invitation/prize_edit';
        var title = '添加活动';
        if (prizes_id > 0) {
            content = content + '?prizes_id=' + prizes_id;
            title = '编辑活动--编号:' + prizes_id;
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['70%', '90%'],
            content: content
        });
    }
</script>