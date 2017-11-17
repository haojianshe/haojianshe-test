<?php
use common\widgets\MyLinkPager;
use mis\service\InvitationRecordService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="7" >
                领奖记录—活动编号：<?php
                echo $models[0]['invitation_id']?> &nbsp;&nbsp;&nbsp;&nbsp;共有<?= $pages->totalCount; ?>条记录
            </th>
            <th colspan="2" style='text-align:right;'>
                <!--<input type="button" id="newCreatePrize" value="新建奖品" class="button"/>-->
                <input type="button" id="prizeList" value="返回列表" class="button"/>
            </th>
        </tr>
        <tr class="tb_header">
            <th >领奖编号</th>
            <th >用户电话</th>
            <th >联系信息</th>
            <th >奖品名称</th>
            <th >申请时间</th>
            <th >处理状态</th>
            <th >处理时间</th>
            <th >备注</th>
            <th >处理人</th>
            <th >操作</th>
        </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
         <td><?= $model['award_id'] ?></td>
         <td><?= $model['umobile'] ?></td>
         <td><?=$model['information']?></td>
         <td><?=$model['prizes_title']?></td>
         <td><?= date('Y-m-d H:i:s', $model['ctime']); ?></td>
         <td><?php if($model['status']==1){ echo "申请中";}else echo "已发放"; ?></td>
         <td><?= date('Y-m-d H:i:s', $model['handle_time']); ?></td>
         <td><?php
         if($model['status']==2){
             echo $model['comment'];
         }
         ?></td>
         <td><?=$model['username']?></td>
           <td><?php if($model['status']==1){ ?>
               <a name='aedit' award_id='<?= $model['award_id'] ?>' > <span style='color:green;cursor:pointer;'>发奖</span></a>&nbsp;&nbsp;
           <?php }else{ echo "<span style='color:red;'>已发放</span>";}?>
           </td>
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
        
    //编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
        addedit($(this).attr("award_id"));
        return false;
    });

//编辑或新增用户页面
    function addedit(award_id) {
        var content = '/invitation/award_edit';
            content = content + '?award_id=' + award_id;
            title = '添加发奖备注:' + award_id;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['30%', '30%'],
            content: content
        });
    }
</script>