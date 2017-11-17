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
            <?php if(isset($model->addrid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="StudioAddressService[addrid]" value="<?= $model->addrid ?>" />
            <?php } ?>
           
            <tr>
            	<td style="width: 50px">标题<span class='need'>*</span></td>
                <td>
                <input class="inputclass1" name="StudioAddressService[addr_title]" style="width:60%" type="text" value="<?= $model->addr_title ?>" 
                               datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>

            <tr>
                   <td style="width: 100px;">内景图</td> 
                   <td>
                     <input type ="hidden" id="thumb_thumb_url" name="StudioAddressService[addr_img]" value="<?= $model->addr_img ?>" />     
                    <a name='athumb' id='athumb_thumb_url' data-name="addr_img" thumbid='0' href='#'>
                        <img id='imgthumb_thumb_url' src="<? if($model->addr_img){echo $model->addr_img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:100px;' /></a>
                   </td>
                 </tr>
            <tr>
                  <tr>
                <td style="width: 80px">联系方式<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" maxlength="15" name="StudioAddressService[tel]" style="width:30%" type="text" value="<?= $model->tel ?>" datatype="*1-30" nullmsg="请咨询电话！" sucmsg="&nbsp;"/>
                </td>
            </tr>
  
            <tr>
            	<td>位置名称</td>
                <td>
        	<input class="inputclass1" name="StudioAddressService[addr_detail]" style="width:50%"  type="text" value="<?= $model->addr_detail ?>"  />
                </td>
            </tr>
             <tr>
            	<td>位置参数</td>
                <td>
        	<input class="inputclass1" name="StudioAddressService[addr_url]" style="width:50%" type="text" value="<?= $model->addr_url ?>"  />
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
    <?php ActiveForm::end(); ?> 
</div>
<script>
	//父窗口句柄
	var index = parent.layer.getFrameIndex(window.name);

    //上传图片
    $("a[name=athumb]").click(function () {
                var content = '/studio/thumbupload';
                var title = '编辑缩略图';
                content = content + '?url='+ encodeURI($("#thumb_thumb_url").val());
                layer.open({
                    type: 2,
                    title: title,
                    maxmin: false,
              shadeClose: false, //点击遮罩关闭层
              area : ['550px' , '300px'],
              content: content
          });
            return false;
        });
        
        
  	
    //保存按钮
    $("#asave").click(function () {
        
      
        //检查富文本框
        var thumb_share_img=$("#thumb_share_img").val();
        var thumb_thumb_url=$("#thumb_thumb_url").val();
        if(thumb_thumb_url == null || thumb_thumb_url == undefined || thumb_thumb_url == ''){
          layer.msg('请上内景图片', {icon: 2});
          return false;
        }
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