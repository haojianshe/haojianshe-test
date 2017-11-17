<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<div class="normaltable">
    <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
    <table style='width:100%;'>
     	<tbody>
            <?php if(isset($model->advuid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="AdvUserService[advuid]" value="<?= $model->advuid ?>" />
            <?php } ?>
            <tr>
            	<td style="width: 80px">广告主名称<span class='need'>*</span></td>
                <td>
                	<input class="inputclass1" name="AdvUserService[name]" style="width:56%" type="text" value="<?= $model->name ?>" datatype="*1-30" nullmsg="请输入广告主名称，最多30个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>
            
            <tr>
            	<td>负责人<span class='need'>*</span></td>
                <td>
        			<input class="inputclass1" name="AdvUserService[adminuser]" style="width:56%" type="text" value="<?= $model->adminuser ?>"  datatype="*1-30" nullmsg="请输入负责人！" sucmsg="&nbsp;" />
                </td>
            </tr>

            <tr>
            	<td>手机号<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" name="AdvUserService[mobile]" maxlength="11"  style="width:56%" type="text" value="<?= $model->mobile ?>" datatype="*1-20" nullmsg="请输入手机号！" sucmsg="&nbsp;" />
                </td>
            </tr>

 			<tr>
            	<td>地址</td>
                <td>
        			<input class="inputclass1" name="AdvUserService[address]" style="width:98%" type="text" value="<?= $model->address ?>"  sucmsg="&nbsp;" />
                </td>
            </tr>

 			<tr>
            	
              <td>备注</td>
              <td>
                   <textarea name="AdvUserService[marks]" style="width:99%;height:100px;" value="<?= $model->marks ?>"  id="marks" sucmsg="&nbsp;"><?= $model->marks ?></textarea>
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
    //保存按钮
    $("#asave").click(function () {
        $("form").submit();
        return false;
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
    	parent.layer.close(index);
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
    $("#cmsform").Validform({
		tiptype:3,
	});
</script>