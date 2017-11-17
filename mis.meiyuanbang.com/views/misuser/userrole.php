  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>   
   <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="3" >
        用户编号：<?= $usermodel->mis_userid ?>&nbsp;&nbsp;&nbsp;&nbsp; 用户名：<?= $usermodel->mis_username ?>
        </th>
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
      <tr class="tb_header">
        <th >是否设置</th>
        <th >角色id</th>
        <th >角色名称</th>
        <th >备注</th>
      </tr>
    </thead>
    <?php $form = ActiveForm::begin(['id' => 'roleform']); ?>    
    <input type="hidden" name='curuserid' value='<?= $usermodel->mis_userid ?>' />
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td>
      	<input type="checkbox" <? if($model['isset']==1){ ?> checked="checked" <? } ?> value="<?= $model['role']->roleid ?>" name="selected[<?= $model['role']->roleid ?>]" />
      </td>
      <td><?= $model['role']->roleid ?></td>
      <td><?= $model['role']->rolename ?></td>
      <td><?= $model['role']->desc ?></td>
      </tr>
     <?}?>     
     <?php ActiveForm::end(); ?> 
     <tr class="operate">
        <th colspan="1" >
        </th>
        <th colspan="3">
        	<input type="button" id="btnsave" value="保存" class="button"/>
        	<input type="button" id="btnclose" value="取消" class="button"/>
        </th>
      </tr>
  </table>
<div id="_tips"></div>
<script>
//父窗口句柄
var index = parent.layer.getFrameIndex(window.name);
$(function () {
    //保存按钮
    $("#btnsave").click(function () {
        $("form").submit();
    });
    //关闭按钮,刷新父窗口
    $('#btnclose').click(function(){
    	//parent.location.reload(); 
    	parent.layer.close(index);
    });            
});

//保存成功后自动关闭
<?if(isset($msg) && $msg<>''){ ?>
	layer.msg('<?= $msg ?>', {icon: 1});
<?}?>
</script>