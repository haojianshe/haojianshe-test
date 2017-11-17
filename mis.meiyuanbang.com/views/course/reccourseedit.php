<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
            <?php if(isset($model->courserecid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="CourseRecommendService[courserecid]" value="<?= $model->courserecid ?>" />
            <?php } ?>

           
            
             <tr>
               <td >课程<span class="need">*</span></td>
               <td>
                 <input type ="hidden" class="inputclass1" id="courseid" name="CourseRecommendService[courseid]" style="width:150px" type="text" value="<?= $model->courseid ?>" />
                  <div>  
                      <span class="courseinfo"></span>
                      <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
                  </div>
               </td>
             </tr> 


            <tr>
            	<td>排序id<span class='need'>*</span></td>
                <td>
        			<input class="inputclass1" name="CourseRecommendService[sort_id]" style="width:100px" type="text" value="<?= $model->sort_id ?>"  datatype="n" nullmsg="请输入排序数！" sucmsg="&nbsp;" />
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
            var content = '/course/coursesel';
            var title = '选择';
            content = content + '?recommendid=<?=$recommendid?>';
            var search =layer.open({
                type: 2,
                title: title,
                maxmin: true,
                area : ['700px' , '600px'],
                content: content
              });
            layer.full(search);
      });
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