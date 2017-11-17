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
            <?php if(isset($model->courseid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="CourseService[courseid]" value="<?= $model->courseid ?>" />
            <?php } ?>
           
            <tr>
            	<td style="width: 80px">标题<span class='need'>*</span></td>
                <td>
                	<input class="inputclass1" name="CourseService[title]" style="width:98%" type="text" value="<?= $model->title ?>" datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>

        

             <tr>
               <td >老师<span class="need">*</span></td>
               <td>
                 <input type ="hidden" class="inputclass1" id="teacheruid" name="CourseService[teacheruid]" style="width:80px" type="text" value="<?= $model->teacheruid ?>" />
                  <div>  
                      <span class="userinfo"><?if($usersinfo){echo $usersinfo[0]['sname'];}?></span>
                      <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
                  </div>
               </td>
             </tr> 




            <tr>
                <td width="80">分类<span class="need">*</span></td>
                <td>
                <select name="CourseService[f_catalog_id]" id="f_catalog_id">
                    <?if(empty($model->f_catalog_id)){ ?>
                        <option value="" selected="">一级分类</option>
                    <?}?>

                    <? foreach ($catalog['imgmgr_level_1'] as $key => $value) {?>            
                        <option value="<?=$key?>" key="<?=$key?>" <?if ($key==$model->f_catalog_id) {?>selected<?} ?>>
                            <?=$value?>
                        </option>
                    <?}?>
                </select>
                <select name="CourseService[s_catalog_id]" id="s_catalog_id">
                    <?if(empty($model->s_catalog_id)){ ?>
                        <option value="" selected="">二级分类</option>
                    <?}else{?>
                        <option value="<?=$model->s_catalog_id?>" selected=""><?= CourseDictDataService::getCourseSubTypeById($model->f_catalog_id,$model->s_catalog_id); ?>
                        </option>
                    <?}?>
                </select>
                </td>
            </tr>
            <tr>
                   <td style="width: 80px;">课程图<span class='need'>* </span>250*140px</td>
                   <td>
                     <input type ="hidden" id="thumb_thumb_url" name="CourseService[thumb_url]" value="<?= $model->thumb_url ?>" />     
                    <a name='athumb' id='athumb_thumb_url' data-name="thumb_url" thumbid='0' href='#'><img id='imgthumb_thumb_url' src="<? if($model->thumb_url){echo $model->thumb_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;' /></a>
                   </td>
                 </tr>
            <tr>

            <tr>
                <td style="width: 80px">老师描述<span class='need'>*</span></td>
                <td>
                    <textarea name="CourseService[teacher_desc]" style="width:98%;height:100px;" datatype="*0-500" errormsg="摘要最多500个字符！" sucmsg="&nbsp;" ><?= $model->teacher_desc ?></textarea>
                    
                </td>
            </tr>
     
            <tr>
            	<td>浏览数</td>
                <td>
        			<input class="inputclass1" name="CourseService[hits_basic]" style="width:100px" type="text" value="<?= $model->hits_basic ?>"  />
                </td>
            </tr>

       

            <tr>
                <td style="width: 80px">分享标题<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" name="CourseService[share_title]" style="width:30%" type="text" value="<?= $model->share_title ?>" datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>
            <tr>
                <td style="width: 80px">分享描述<span class='need'>*</span></td>
                <td>
                  <textarea name="CourseService[share_desc]" style="width:98%;height:100px;" datatype="*0-500" errormsg="摘要最多500个字符！" sucmsg="&nbsp;" ><?= $model->share_desc ?></textarea>
                </td>
            </tr>

            <tr>
                   <td style="width: 80px;">分享图片<span class='need'>*</span></br>100*100px</td>
                   <td >
                     <input type ="hidden" id="thumb_share_img" name="CourseService[share_img]" value="<?= $model->share_img ?>" />     
                    <a name='athumb' id='athumb_share_img' data-name="share_img" thumbid='0' href='#'><img id='imgthumb_share_img' src="<? if($model->share_img){echo $model->share_img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;' /></a>
                   </td>
                 </tr>
             <tr>
  
            <tr>
              <td>内容介绍<span class='need'>*</span></td>
                <td>
                  <script name='CourseService[content]' id="editor" type="text/plain" style="width:99%;height:500px;"></script>
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
	//显示富文本框内容
	  var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
	ue.ready(function() {
		ue.setContent('<?= $model->content ?>');   
	});
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
    $("#f_catalog_id").click(function() {
           var key=$("#f_catalog_id  option:selected").attr("key");
           var catalog_json=<?= json_encode($catalog)?>;
           var s_catalog_id="<?= $model->s_catalog_id?>";
           var content='';
           var s_catalogs=catalog_json.imgmgr_level_2[key];
           for(var item in s_catalogs) {
                  if(s_catalog_id==item){
                    content+="<option selected value="+item+">"+s_catalogs[item]+"</option>";
                  }else{
                    content+="<option value="+item+">"+s_catalogs[item]+"</option>";
                  }
              $("#s_catalog_id").html(content);
          }    
      });    

    //上传图片
    $("a[name=athumb]").click(function () {
                var content = '/course/thumbupload';
                var title = '编辑缩略图';
                content = content + '?url='+ encodeURI($('#thumb_'+$(this).data('name')).val())+'&name='+ $(this).data('name');
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
        var html = $.trim(ue.getContent());
        
        var teacheruid=$("#teacheruid").val();
        var f_catalog_id=$("#f_catalog_id").val();
        var s_catalog_id=$("#s_catalog_id").val();
        var thumb_share_img=$("#thumb_share_img").val();
        var thumb_thumb_url=$("#thumb_thumb_url").val();
        if(teacheruid == null || teacheruid == undefined || teacheruid == ''){
          layer.msg('请选择老师', {icon: 2});
          return false;
        }
        if(f_catalog_id == null || f_catalog_id == undefined || f_catalog_id == ''){
          layer.msg('请选择一级分类', {icon: 2});
          return false;
        }
        if(s_catalog_id == null || s_catalog_id == undefined || s_catalog_id == ''){
          layer.msg('请选择二级分类', {icon: 2});
          return false;
        }
        if(thumb_thumb_url == null || thumb_thumb_url == undefined || thumb_thumb_url == ''){
          layer.msg('请上传课程图片', {icon: 2});
          return false;
        }

        if(html ==''){
          layer.msg('请输入文章内容', {icon: 2});
          return false;
        }
        if(thumb_share_img == null || thumb_share_img == undefined || thumb_share_img == ''){
          layer.msg('请上传分享图片', {icon: 2});
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