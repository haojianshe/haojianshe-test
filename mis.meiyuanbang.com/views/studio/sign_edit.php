<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\service\dict\CourseDictDataService;

?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.js?v=201605191725"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>

<!-- 时间选择框样式 -->
<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css"/>
<!-- 时间选择框js -->
<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
      
<div class="normaltable">
    <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
    <table style='width:100%;'>
     	<tbody>
            <?php if(isset($model->enrollid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="StudioEnrollService[enrollid]" value="<?= $model->enrollid ?>" />
            <?php } ?>
           
            <tr>
            	<td style="width: 80px">标题<span class='need'>*</span></td>
                <td>
             <input class="inputclass1" name="StudioEnrollService[enroll_title]" style="width:68%" type="text" value="<?= $model->enroll_title ?>" datatype="*1-50" nullmsg="请填标题！" sucmsg="&nbsp;" />
                </td>
            </tr>
            <tr>
            <tr>
                <td style="width: 80px">自费说明<span class='need'>*</span></td>
                <td>
                    <textarea name="StudioEnrollService[enroll_desc]" style="width:98%;height:100px;" datatype="*0-500" errormsg="摘要最多500个字符！" sucmsg="&nbsp;" ><?= $model->enroll_desc ?></textarea>
                    
                </td>
            </tr>
     
            <tr>
                <td style="width: 80px">排序<span class='need'>*</span></td>
                <td>
                     <input class="inputclass1" name="StudioEnrollService[listorder]" style="width:10%" type="text" value="<?= $model->listorder ?>" datatype="*1-100" nullmsg="排序字段！" sucmsg="&nbsp;"/>
                </td>
            </tr>
              <tr>
                <td style="width: 80px">现价<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" name="StudioEnrollService[discount_price]" style="width:20%" type="text" value="<?= $model->discount_price ?>" datatype="*1-30" nullmsg="请填写现价！" sucmsg="&nbsp;"/>
                </td>
            </tr>
            
         <tr>
                <td style="width: 80px">原价<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" name="StudioEnrollService[original_price]" style="width:20%" type="text" value="<?= $model->original_price ?>" datatype="*1-30" nullmsg="请填写原价！" sucmsg="&nbsp;"/>
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
    <input type="hidden" value="<?=$uid?>" id="uid" name="uid"/>
    <input type="hidden" value="<?=$classtypeid?>" id="classtypeid" name="classtypeid"/>
    <?php ActiveForm::end(); ?> 
</div>
<script>
	//父窗口句柄
	var index = parent.layer.getFrameIndex(window.name);
  	
    //保存按钮
    $("#asave").click(function () {
        //检查富文本框
        $("form").submit();
        return false;
    });

    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
    	//parent.location.reload(); 
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