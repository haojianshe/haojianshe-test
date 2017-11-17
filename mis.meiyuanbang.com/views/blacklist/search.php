  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>   
   <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="5" >
        	<?php $form = ActiveForm::begin(['id' => 'searchform']); ?>   
        	用户昵称:
        	<input class="inputclass1" name="keyword" style="width:100px" type="text" value="<?=$keyword ?>" datatype="*1-20" nullmsg="请输入2-20个字符！" sucmsg="&nbsp;" />
      		<input type="button" id="btnsearch" value="搜索" class="button"/>
      		<?php ActiveForm::end(); ?> 
        </th>
        <th colspan="2" style='text-align:right;'>
        	共有<?= count($models) ?>条记录
        </th>
      </tr>
      <tr class="tb_header">
        <th style="width:10%">头像</th>
        <th style="width:15%">用户编号</th>
        <th style="width:15%">昵称</th>
        <th style="width:8%">用户手机号</th>
        <th style="width:8%">登录方式</th>
        <th style="width:15%">注册日期</th>
        <th style="width:15%">操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><img src='<?= $model['avatars']?>' style='height:80px;width:80px;'/> </td>
      <td><?= $model['uid'] ?></td>
      <td><?= $model['sname'] ?></td>
      <td><?= $model['umobile'] ?></td>
      <td><?= $model['oauth_type'] ?></td>
      <td><?= date('Y-m-d H:i:s',$model['create_time']); ?></td>
      <td>
      	<a name='aadd' uid='<?= $model['uid'] ?>' href='#'>加入黑名单</a> 
      </td>
      </tr>  
     <?}?>
  </table>
<script>
//父窗口句柄
var index = parent.layer.getFrameIndex(window.name);
//搜索按钮
$("#btnsearch").click(function () {
    $("form").submit();
});

//加入黑名单
$("a[name=aadd]").click(function () {
	var userid = $(this).attr("uid");
    layer.confirm('确定将用户加入黑名单吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/blacklist/add",
            data: "userid=" + userid,//要发送的数据
            success: function (data) {
                if (data.errno == 0) {
                	layer.msg('已加入黑名单', {icon: 1});
    	        	setTimeout(function (){
    	        		parent.location.reload();
    	           }, 1000);
                }
                else {
                	layer.msg(data.msg,{icon: 2});
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            	layer.msg("访问出错",{icon: 2});
            }
        });
    }, function(){
        //取消
    });
    return false;
});

$("#searchform").Validform({
	tiptype:3,
});
</script>