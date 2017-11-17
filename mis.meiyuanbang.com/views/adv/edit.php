  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <div class="normaltable">
 <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
 <table style='width:100%;'>
 	<tbody>
 	<?php if(isset($model->advid)){?>
    <input type ="hidden" name='isedit' value='1' />
    <input type ="hidden" name="AdvResourceService[advid]" value="<?= $model->advid ?>" />
    <?php } ?>
     <tr>
    	<td>广告标题 <span class='need'>*</span></td>
        <td>
			<input class="inputclass1" id="title" name="AdvResourceService[title]" style="width:50%" type="text" value="<?= $model->title ?>"  datatype="*1-300" nullmsg='广告标题' sucmsg="&nbsp;" />
        </td>
    </tr>     
    <tr>
    	<td style="width: 80px">类型<span class='need'>*</span></td>
        <td>
        	<select id="typeid" name="AdvResourceService[typeid]" class="valid"  datatype="n1-16" nullmsg="请选择推荐类型" errormsg="请选择推荐类型" sucmsg="&nbsp;">
                <? foreach ($typemodel as $typeitem) { ?>
                <option value="<?= $typeitem['typeid'] ?>"><?= $typeitem['typename'] ?></option>
                <?}?>
            </select>
            <script>
				//选中类型
            	$('#typeid').val('<?=$model->typeid ?>');
            </script>
        </td>
    </tr>

   

     <tr>
                   <td style="width: 80px;">缩略图1<span class='need'>* </span>375*117pt</td>
                   <td>
                     <input type ="hidden" id="thumb_topimage1" name="AdvResourceService[topimage1]" value="<?= $model->topimage1 ?>" />     
                    <a name='athumb' id='athumb_topimage1' data-name="topimage1" thumbid='0' href='#'><img id='imgthumb_topimage1' src="<? if($model->topimage1){echo $model->topimage1;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;' /></a>
                   </td>
                 </tr>
            <tr>



 		<tr>
                   <td style="width: 80px;">缩略图2<span class='need'>* </span>357*75pt</td>
                   <td>
                     <input type ="hidden" id="thumb_topimage2" name="AdvResourceService[topimage2]" value="<?= $model->topimage2 ?>" />     
                    <a name='athumb' id='athumb_topimage2' data-name="topimage2" thumbid='0' href='#'><img id='imgthumb_topimage2' src="<? if($model->topimage2){echo $model->topimage2;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;' /></a>
                   </td>
                 </tr>
            <tr>

   <!--  <tr>
    	<td>缩略图<span class='need'>*</span></td>
        <td>  
        	<input type ="hidden" id="thumb" name="AdvResourceService[topimage1]" value="<?= $model->topimage1 ?>" />      	
        	<a name='athumb' id='athumb' href='#'><img id='imgthumb' src="<? if($model->topimage1){echo $model->topimage1;}else echo '/ueditor/dialogs/image/images/image.png'; ?>"
                                                           style='height:100px;' /></a><span class='need'>缩略图图片尺寸为750*370</span>
        </td>
    </tr>  -->
    <tr>
    	<td>参数1<span class='need'>*</span></td>
        <td>
        	<input id='param1' class="inputclass1" name="AdvResourceService[param1]" style="width:50%" type="text" value="<?= $model->param1 ?>"/>
        </td>
    </tr>
    <tr>
    	<td>参数2</td>
        <td>
        	<input id='param2' class="inputclass1" name="AdvResourceService[param2]" style="width:50%" type="text" value="<?= $model->param2 ?>" />

          <div id='param2_img' style="display: none;">
            <input type ="hidden" id="thumb_param2"  value="<?= $model->param2 ?>" />   

            <a name='athumb' id='athumb_param2' data-name="param2" thumbid='0' href='#'><img id='imgthumb_param2' src="<? if($model->param2){echo $model->param2;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;' /></a>
          </div>

        </td>
    </tr>
    <tr>
    	<td>参数3</td>
        <td>
        	<input id='param3' class="inputclass1" name="AdvResourceService[param3]" style="width:50%" type="text" value="<?= $model->param3 ?>" />
        </td>
    </tr>
    <tr>
    	<td>参数4</td>
        <td>
        	<input id='param4' class="inputclass1" name="AdvResourceService[param4]" style="width:50%" type="text" value="<?= $model->param4 ?>" />
        </td>
    </tr>
    <tr>
    	<td>参数5</td>
        <td>
			<input id='param5' class="inputclass1" name="AdvResourceService[param5]" style="width:50%" type="text" value="<?= $model->param5 ?>" />
        </td>
    </tr>       
    <tr>
    	<td>备注</td>
        <td>
        	<textarea name="AdvResourceService[desc]" style="width:70%;height:30px;" datatype="*0-100" errormsg="摘要最多100个字符！" sucmsg="&nbsp;" ><?= $model->desc ?></textarea>
        </td>
    </tr>    
     <input type="hidden" name="AdvResourceService[channelid]"  />
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
    	<td>
    	<span >规则说明</span>
    	</td>
    	<td>
	        <div>
	        <span  class='need'>选择不同类型时，请根据如下规则填写参数1-5，请谨慎填写，否则会引起客户端错误</span></br>
	        <span >html页类型:参数1请填写url 参数2请填写广告分享缩略图 参数3请填写广告分享标题 参数4请填写广告分享描述 若不填写分享信息则无分享</span></br>
	        <span >考点类型:参数1请填写考点编号</span></br>
	        <span >活动类型:参数1请填写活动url地址,参数2请填写活动编号</span></br>
	        <span >精讲类型:参数1请填写精讲url</span></br>
	        <span >个人主页类型:参数1请填写用户编号</span></br> 	
          <span >专题类型:参数1请填写专题编号</span></br> 
          <span >直播类型:参数1请填写直播编号,参数2请填写直播连接地址</span></br> 
          <span >课程类型:参数1请填写课程编号</span></br> 
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
  		 //上传图片
    	$("a[name=athumb]").click(function () {
                var content = '/adv/thumbupload';
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
$("#typeid").click(function(){
  
  switch(parseInt($(this).val())){
     case 1:
      $("#param2_img").show();
      $("#param2").hide();
      $("#param2").removeAttr("name");
      $("#thumb_param2").attr("name","AdvResourceService[param2]");
     break;
     default:
      $("#param2_img").hide();
      $("#param2").show();
      $("#param2").attr("name","AdvResourceService[param2]");
      $("#thumb_param2").removeAttr("name");
     break;
  }
  
});

function showMsg(value){
 layer.open({
      content: value,
      style: 'background-color:#fff; color:#000; border:none; font-size: 0.373333rem; border-radius:0.15rem; line-height:0.8rem; width:8.0rem;',
  });
}
        //保存按钮
        $("#asave").click(function () {
          if(!$("#title").val() ){
            showMsg("请输入标题！");
            return false;
          }
          if(!$("#typeid").val() ){
            showMsg("请选择类型！");
            return false;
          }

          if(!$("#thumb_topimage1").val() ){
            showMsg("请上传缩略图1！");
            return false;
          }

          if(!$("#thumb_topimage2").val() ){
            showMsg("请上传缩略图2！");
            return false;
          }

          switch(parseInt($("#typeid").val()))
          {
              case 1:
                if(!$("#param1").val() ){
                    showMsg("参数1请输入html连接地址！");
                    return false;
                }
               
              // html页
              break;

              case 2:
              if(isNaN($("#param1").val())){
                    showMsg("参数1请输入考点id！");
                    return false;
              }
              // 考点
              break;

              case 3:
              // 活动
              if(!($("#param1").val())){
                    showMsg("参数1请输入活动连接！");
                    return false;
              }
              if(isNaN($("#param2").val())){
                    showMsg("参数1请输入活动编号！");
                    return false;
              }
              break;

              case 4:
              // 精讲
              if(!($("#param1").val())){
                    showMsg("参数1请输入精讲连接！");
                    return false;
              }
              break;

              case 5:
              // 个人主页
              if(isNaN($("#param1").val())){
                    showMsg("参数1请输入个人编号！");
                    return false;
              }
              break;
              case 6:
              // 专题
              if(isNaN($("#param1").val())){
                    showMsg("参数1请输入专题编号！");
                    return false;
              }
              break;
              case 7:
              // 直播
              if(isNaN($("#param1").val())){
                    showMsg("参数1请输入直播编号！");
                    return false;
              }
              if(!($("#param2").val())){
                    showMsg("参数2请输入直播连接！");
                    return false;
              }
              break;
              case 8:
              // 课程
              if(isNaN($("#param1").val())){
                    showMsg("参数1请输入课程编号！");
                    return false;
              }
              break;
          }

          $("form").submit();
          return false;
        });
        //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	parent.layer.close(index);
        });
        //保存成功后自动关闭
        <? if(isset($msg) && $msg<>''){ ?>
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