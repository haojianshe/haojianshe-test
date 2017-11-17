  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

  <div class="normaltable">
 <?php $form = ActiveForm::begin(['id' => 'roleform']); ?>    
 <table>
 	<tbody>
 	<?php if(isset($model->roleid)){?>
    <tr>
    	<td style="width: 80px">角色编号</td>
        <td>
        	<input type ="hidden" name='isedit' value='1' />
        	<input class="inputclass1" name="MisRoleService[roleid]" style="width:50px" type="text" value="<?= $model->roleid ?>" readonly='true' />
        </td>
    </tr>
    <?php } ?>
    <tr>
    	<td style="width: 80px">角色名称</td>
        <td>
        	<input class="inputclass1" name="MisRoleService[rolename]" style="width:300px" type="text" value="<?= $model->rolename ?>" datatype="s1-20" nullmsg="请输入角色名称，最多20个字符！" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td>上级角色id</td>
        <td>
        	<input class="inputclass1" name="MisRoleService[parent_roleid]" style="width:300px" type="text" value="<?= $model->parent_roleid ?>" datatype="n1-3" nullmsg="请填写正确的上级角色编号！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <tr>
    	<td>备注</td>
        <td>
        	<input class="inputclass1" name="MisRoleService[desc]" style="width:300px" type="text" value="<?= $model->desc ?>" />
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
        $("#roleform").Validform({
    		tiptype:3,
    	});
    </script>
  