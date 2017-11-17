<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<div class="normaltable">
    <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
    <table style='width:100%;'>
     	<tbody>
            <?php if(isset($model->sectionid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="CourseSectionService[sectionid]" value="<?= $model->sectionid ?>" />
            <?php } ?>
            <tr>
            	<td style="width: 80px">标题<span class='need'>*</span></td>
                <td>
                	<input class="inputclass1" name="CourseSectionService[title]" style="width:30%" type="text" value="<?= $model->title ?>" datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>
            <tr>
            	<td>章序号<span class='need'>*</span></td>
                <td>
        			<input class="inputclass1" name="CourseSectionService[section_num]" style="width:100px" type="text" value="<?= $model->section_num ?>"  datatype="n" nullmsg="请输入浏览数！" sucmsg="&nbsp;" />
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
            <tr>
                <td colspan="2">
                温馨提示：请在填写章序号时保证前后两章的章序号为连续的阿拉伯数字，初始第一章章序号为“1”、以此类推第二章章序号为“2”；不要出现章序号不连续，章序号不连续造成前端展示缺少某一章。例如章序号为“1345”课程将缺少第二章。
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