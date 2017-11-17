  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.js?v=201605191725"> </script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
  <!--添加乐视视频按钮-->
<!--   <script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/letv/levedio.js"></script>  
 -->
  <script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/alivideo/alivideo.js"></script>  
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
 	<?php if(isset($model['lecture_tagid'])){?>
    <input type ="hidden" name='isedit' value='1' />
    <input type ="hidden" name="LectureTagService[lecture_tagid]" value="<?=$model['lecture_tagid'] ?>" />
    <?php } ?>
      <tr>
       <td >选择精讲文章<span class="need">*</span></td>
       <td>
            <?php
    if($lecture_tagid){
    ?>
       <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
         <input type ="hidden" class="inputclass1" id="rids" newsid="<?=$model['newsid']?>" name="MatreialSubjectService[rids]" style="width:150px" type="text" value="" />
    <?php
     }else{
         echo "<span style='color:red'>先添加副标题,再次编辑时选择文章</span>";
     }
    ?>
       </td>
     </tr>
         <?php
   
     if(!empty($news_data)){
     ?>
     <tr>
         <td>
             关联精讲文章
         </td>
         <td style=" height: 90px;">
             <?php
             foreach($news_data as $k=>$v){
             ?>
             <div id="div_<?=$v['newsid']?>" name="<?=$v['newsid']?>" class="divclass" style="cursor:pointer;border: 1px solid  graytext; background-color: #d3d3d3;  margin: 5px; padding: 4px;width:500px; height:auto; float: left"><?php echo $v['title'];?></div>
             <?php
             }
            ?>
         </td>
     </tr>
     <?php
     }
     ?>
        <tr>
    	<td style="width: 80px">标题<span class='need'>*</span></td>
        <td>
            <input class="inputclass1" name="LectureTagService[tag_title]" style="width:30%" maxlength="20" type="text" value="<?= $model['tag_title'] ?>" datatype="*1-200" nullmsg="标题不能为空" sucmsg="&nbsp;"/>
        </td>
      </tr>
       <tr>
    	<td style="width: 80px">排序<span class='need'>*</span></td>
        <td>
            <input class="inputclass1" name="LectureTagService[listorder]" style="width:30%" type="text" value="<?= $model['listorder'] ?>"   datatype="/^-?[1-9]\d*$/" errormsg="必须为数字" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <input type="hidden" value="<?php echo $newsid?>" name="newsid" />
    <input type="hidden" value="<?php echo $lecture_tagid?>" name="lecture_tagid" id='lecture_tagid'/>
      <tr>
    	<td></td>
    	<td >
	        <div>
	        	<span class="normalbtn_l"><a id="asave" href="#">保存</a></span>
	        	<span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>	        	
	        </div>
        </td>
    </tr>
    </tbody>
     <?php
    $arr = [];
    foreach($news_data as $key=>$val){
        $arr[] = $val['newsid'];
    }
    ?>
    <input type="hidden" value="<?= implode(',', $arr)?>" id="news_data" name="news_data"/>
 </table> 
  <?php ActiveForm::end(); ?> 
  </div>
    
  <script>
          //点击隐藏
        $('.divclass').click(function(){
            $(this).hide();
            var name = $(this).attr('name');
            var news_data = $("#news_data").val();
            $("#news_data").val(news_data.replace(name,""));
         });
  		
    //父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
                  var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
  		//显示富文本框内容
//  		ue.ready(function() {
//  			ue.setContent('<?//= $newsdatamodel->content ?>');   
//  		});
        //保存按钮
        $("#asave").click(function () {
            $("form").submit();
            return false;
            
        });
        //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	<?php
              unset($_SESSION['chkval']);
                ?>
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
        
       //点击缩略图事件
        $("#selbtn").click(function () {
                var content = '/lecture/sel';
                var title = '选择精讲文章';
                var ss = $("[name='MatreialSubjectService[rids]']").attr('newsid');
                content = content + '?rids='+ encodeURI($("[name='MatreialSubjectService[rids]']").val())+"&newsid="
                        +$("[name='MatreialSubjectService[rids]']").attr('newsid')
                        +'&lecture_tagid='+$("#lecture_tagid").val()+'&news_data='+$("#news_data").val();
                layer.open({
                    type: 2,
                    title: title,
                    maxmin: true,
                    area : ['95%' , '95%'],
                    content: content
                  });
                 return false;
          });
    </script>
   