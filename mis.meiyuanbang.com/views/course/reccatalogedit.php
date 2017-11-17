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
            <?php if(isset($model->recommendid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="CourseRecommendCatalogService[recommendid]" value="<?= $model->recommendid ?>" />
            <?php } ?>

         
            <tr>
                <td width="80">分类<span class="need">*</span></td>
                <td>
                <select name="CourseRecommendCatalogService[f_catalog_id]" id="f_catalog_id">
                    <?if(empty($model->f_catalog_id)){ ?>
                        <option value="" selected="">一级分类</option>
                    <?}?>

                    <? foreach ($catalog['imgmgr_level_1'] as $key => $value) {?>            
                        <option value="<?=$key?>" key="<?=$key?>" <?if ($key==$model->f_catalog_id) {?>selected<?} ?>>
                            <?=$value?>
                        </option>
                    <?}?>
                </select>
                <select name="CourseRecommendCatalogService[s_catalog_id]" id="s_catalog_id">
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
                <td>排序id<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" name="CourseRecommendCatalogService[sort_id]" style="width:100px" type="text" value="<?= $model->sort_id ?>"  />
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