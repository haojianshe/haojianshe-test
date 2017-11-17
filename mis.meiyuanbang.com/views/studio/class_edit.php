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
            <?php if(isset($model->classtypeid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="StudioClasstypeService[classtypeid]" value="<?= $model->classtypeid ?>" />
            <?php } ?>
           
            <tr>
            	<td style="width: 80px">标题<span class='need'>*</span></td>
                <td>
                	<input class="inputclass1" name="StudioClasstypeService[classtype_title]" style="width:98%" type="text" value="<?= $model->classtype_title ?>" 
                               datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>

            <tr>
                   <td style="width: 100px;">封面图</td> 
                   <!--<span class='need'>* </span>250*140px-->
                   <td>
                     <input type ="hidden" id="thumb_thumb_url" name="StudioClasstypeService[classtype_img]" value="<?= $model->classtype_img ?>" />     
                    <a name='athumb' id='athumb_thumb_url' data-name="classtype_img" thumbid='0' href='#'>
                        <img id='imgthumb_thumb_url' src="<? if($model->classtype_img){echo $model->classtype_img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:100px;' /></a>
                   </td>
                 </tr>
            <tr>

            <tr>
                <td style="width: 80px">简介<span class='need'>*</span></td>
                <td>
                    <textarea name="StudioClasstypeService[class_desc]" style="width:98%;height:100px;" datatype="*0-500" errormsg="摘要最多500个字符！" sucmsg="&nbsp;" ><?= $model->class_desc ?></textarea>
                    
                </td>
            </tr>
     
            <tr>
                <td style="width: 80px">排序<span class='need'>*</span></td>
                <td>
                     <input class="inputclass1" name="StudioClasstypeService[listorder]" style="width:30%" type="text" value="<?= $model->listorder ?>" datatype="*1-100" nullmsg="排序字段！" sucmsg="&nbsp;"/>
                </td>
            </tr>
            <tr>
                <td style="width: 80px">咨询人<span class='need'>*</span></td>
                <td>
                     <input class="inputclass1" name="StudioClasstypeService[classtype_consultant]" style="width:30%" type="text" value="<?= $model->classtype_consultant ?>" datatype="*1-30" nullmsg="请输入咨询人，最多30个字！"  sucmsg="&nbsp;"/>
                </td>
            </tr>
              <tr>
                <td style="width: 80px">咨询电话<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" name="StudioClasstypeService[tel]" style="width:30%" type="text" value="<?= $model->tel ?>" datatype="*1-30" nullmsg="请咨询电话！" sucmsg="&nbsp;"/>
                </td>
            </tr>
            
            <tr>
            	<td>报名人数</td>
                <td>
        	<input class="inputclass1" name="StudioClasstypeService[classtype_sum]" style="width:100px" type="text" value="<?= $model->classtype_sum ?>"  />
                </td>
            </tr>
            

<!--             <tr>
               <td >报名方式<span class="need">*</span></td>
               <td>
                 <input type ="hidden" class="inputclass1" id="teacheruid" name="StudioClasstypeService[classtype_sum]" style="width:80px" type="text" value="<?= $model->classtype_sum ?>" />
                  <div>  
                      <span class="userinfo"><?PHP //if($usersinfo){echo $usersinfo[0]['sname'];}?></span>
                      <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
                  </div>
               </td>
             </tr> 

       -->

  
            <tr>
              <td>内容介绍<span class='need'>*</span></td>
                <td>
                  <script name='StudioClasstypeService[classtype_content]' id="editor" type="text/plain" style="width:770px;height:500px;"></script>
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
	//显示富文本框内容
	  var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
	ue.ready(function() {
		ue.setContent('<?= $model->classtype_content ?>');   
	});
        
        
        
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
        
        
        
        
//        
//    //选择批改老师
//    $("#selbtn").click(function () {
//            var content = '/course/teachersel';
//            var title = '选择老师';
//            content = content + '?uid='+ encodeURI($("#teacheruid").val());
//            var search =layer.open({
//                type: 2,
//                title: title,
//                maxmin: true,
//                area : ['700px' , '600px'],
//                content: content
//              });
//            layer.full(search);
//      });
   
  	
    //保存按钮
    $("#asave").click(function () {
        //检查富文本框
        var html = $.trim(ue.getContent());
        var thumb_share_img=$("#thumb_share_img").val();
        var thumb_thumb_url=$("#thumb_thumb_url").val();
        if(thumb_thumb_url == null || thumb_thumb_url == undefined || thumb_thumb_url == ''){
          layer.msg('请上传课程图片', {icon: 2});
          return false;
        }

        if(html ==''){
          layer.msg('请输入文章内容', {icon: 2});
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