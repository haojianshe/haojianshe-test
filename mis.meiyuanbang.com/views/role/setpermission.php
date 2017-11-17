  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>   
   <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="3" >
        角色编号：<?= $rolemodel->roleid ?>&nbsp;&nbsp;&nbsp;&nbsp; 角色名称：<?= $rolemodel->rolename ?>
        </th>
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
      <tr class="tb_header">
        <th >是否授权</th>
        <th >资源id</th>
        <th >资源名称</th>
        <th >备注</th>
      </tr>
    </thead>
    <?php $form = ActiveForm::begin(['id' => 'roleform']); ?>    
    <input type="hidden" name='curroleid' value='<?= $rolemodel->roleid ?>' />
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td>
      	<input type="checkbox" <? if($model['ispermission']==1){ ?> checked="checked" <? } ?> value="<?= $model['resource']->resourceid ?>" name="selected[<?= $model['resource']->resourceid ?>]" />
      </td>
      <td><?= $model['resource']->resourceid ?></td>
      <td><?= $model['resource']->resourcename ?></td>
      <td><?= $model['resource']->desc ?></td>
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