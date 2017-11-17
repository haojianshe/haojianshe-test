  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

  <div class="normaltable">
 <?php $form = ActiveForm::begin(['id' => 'userform']); ?>    
 <table>
 	<tbody>
 	<?php if(isset($model->mis_userid)){?>
    <tr>
    	<td style="width: 80px">用户编号</td>
        <td style="width: 500px">
        	<?= $model->mis_userid ?>
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td style="width: 80px">用户名</td>
        <td>
        	<?= $model->mis_username ?>
        </td>
    </tr>
    <tr>
    	<td>真实姓名</td>
        <td>
        	<?= $model->mis_realname ?>
        </td>
    </tr>
    <tr>
    	<td>旧密码<span class="need">*</span></td>
        <td>
        	<input class="inputclass1" name="oldpwd" style="width:200px" type="password" value="" datatype="*6-20" nullmsg="请输入老密码！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <tr>
    	<td>新密码<span class="need">*</span></td>
        <td>
        	<input class="inputclass1" name="newpwd" style="width:200px" type="text" value="<?= $newpwd ?>" datatype="*6-20" nullmsg="请输入6-20位新密码！" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td></td>
    	<td>
	        <div>
	        	<span class="normalbtn_l"><a id="asave" href="#">修改密码</a></span>
	        </div>
        </td>
    </tr>
    </tbody>
 </table> 
  <?php ActiveForm::end(); ?> 
  </div>
  <script>
  		//父窗口句柄
  		$(function () {
            //保存按钮
            $("#asave").click(function () {
                $("form").submit();
                return false;
            });           
        });
        //保存成功后自动关闭
        <? if($msg<>''){ ?>
        	layer.msg('<?= $msg ?>', {icon: <?= $msgicon ?>});
        <? } ?>
		//表单验证
        $("#userform").Validform({
    		tiptype:3,
    	});
    </script>
  