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
        <td>
        	<input type ="hidden" name='isedit' value='1' />
        	<input class="inputclass1" name="MisUserService[mis_userid]" style="width:50px" type="text" value="<?= $model->mis_userid ?>" readonly='true' />
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td style="width: 80px">用户名</td>
        <td>
        	<input class="inputclass1" name="MisUserService[mis_username]" style="width:300px" type="text" value="<?= $model->mis_username ?>" datatype="s1-20" nullmsg="请输入用户名，最多20个字符！" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td>真实姓名</td>
        <td>
        	<input class="inputclass1" name="MisUserService[mis_realname]" style="width:300px" type="text" value="<?= $model->mis_realname ?>" datatype="s1-20" nullmsg="请输入真实姓名，最多20个字符！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <?php if(!isset($model->mis_userid)){?>
    <tr>
    	<td>密码</td>
        <td>
        	<input class="inputclass1" name="MisUserService[password]" style="width:300px" type="text" value="<?= $model->password ?>" datatype="*6-20" nullmsg="请输入6-20位密码！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td>邮件</td>
        <td>
        	<input class="inputclass1" name="MisUserService[email]" style="width:300px" type="text" value="<?= $model->email ?>" />
        </td>
    </tr>
    <tr>
    	<td>部门</td>
        <td>
        	<input class="inputclass1" name="MisUserService[department]" style="width:300px" type="text" value="<?= $model->department ?>" />
        </td>
    </tr>
    <tr>
    	<td></td>
    	<td>
	        <div>
	        	<span class="normalbtn_l"><a id="asave" href="#">保存</a></span>
	        	<span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>
	        	
	        </div>
        </td>
    </tr>
    </tbody>
 </table> 
  <?php ActiveForm::end(); ?> 
  </div>
  <script>
  		//父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
        $(function () {
            //保存按钮
            $("#asave").click(function () {
                $("form").submit();
                return false;
            });
            //关闭按钮,刷新父窗口
            $('#aclose').click(function(){
            	//parent.location.reload(); 
            	parent.layer.close(index);
            });            
        });
        //保存成功后自动关闭
        <? if($msg<>''){ ?>
        	<?if(isset($isclose) && $isclose){ ?>
        		layer.msg('<?= $msg ?>', {icon: 1});
	        	setTimeout(function (){
	        		parent.location.reload();
	           }, 1000);
	      	<? } else{ ?>
	      		layer.msg('<?= $msg ?>', {icon: 2});
	      	<? } ?>
        <? } ?>
		//表单验证
        $("#userform").Validform({
    		tiptype:3,
    	});
    </script>
  