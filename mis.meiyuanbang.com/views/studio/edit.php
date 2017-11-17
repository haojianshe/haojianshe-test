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
            <?php if(isset($model->uid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="StudioService[uid]" value="<?= $model->uid ?>" />
            <?php } ?>
           
            <tr>
            	<td style="width: 30px">联系人<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" id="contact_user" name="StudioService[contact_user]" style="width:30%" type="text" value="<?= $model->contact_user ?>" datatype="*1-20" nullmsg="请输入联系人，最多20个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>
            <tr>
            	<td>联系电话</td>
                <td>
                    <input class="inputclass1" maxlength="11" id="studio_mobile"  name="StudioService[studio_mobile]" style="width:50%" type="text" value="<?= $model->studio_mobile ?>"  datatype="*1-20" nullmsg="请输入电话！" sucmsg="&nbsp;"  />
                </td>
            </tr>

            <tr>
                <td style="width: 80px">资讯电话<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" id="studio_tel"  maxlength="15" name="StudioService[studio_tel]" style="width:50%" type="text" value="<?= $model->studio_tel ?>" datatype="*1-20" nullmsg="请输入资讯电话！" sucmsg="&nbsp;"/>
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
    //选择批改老师
    $("#selbtn").click(function () {
            var content = '/course/teachersel';
            var title = '选择老师';
            content = content + '?uid='+ encodeURI($("#teacheruid").val());
            var search =layer.open({
                type: 2,
                title: title,
                maxmin: true,
                area : ['700px' , '600px'],
                content: content
              });
            layer.full(search);
      });

    //保存数据
    $("#asave").click(function () {
        var contact_user=$("#contact_user").val();
        var studio_mobile=$("#studio_mobile").val();
        var studio_tel=$("#studio_tel").val();
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