  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <div class="normaltable">
 <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
 <table style='width:100%;'>
 	<tbody>
 	<?php if(isset($model->posidid)){?>
    <input type ="hidden" name='isedit' value='1' />
    <input type ="hidden" name="PosidHomeService[posidid]" value="<?= $model->posidid ?>" />
    <?php } ?>
    <tr>
    	<td style="width: 80px">类型<span class='need'>*</span></td>
        <td>
        	<select id="typeid" name="PosidHomeService[typeid]" class="valid"  datatype="n1-16" nullmsg="请选择推荐类型" errormsg="请选择推荐类型" sucmsg="&nbsp;">
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


    <tr style="display:none;">
        <td style="width: 80px">类型<span class='need'>*</span></td>
        <td>
            <select id="typeid" name="PosidHomeService[channelid]" class="valid"  datatype="n1-16" nullmsg="请选择推荐类型" errormsg="请选择推荐类型" sucmsg="&nbsp;">
                <option value="1" <?if ($channelid==1) {?>selected="selected"<?}?>>首页</option>
                <option value="2" <?if ($channelid==2) {?>selected="selected"<?}?> >素材（专题）</option>
                <option value="3" <?if ($channelid==3) {?>selected="selected"<?}?> >批改列表</option>
            </select>
           
        </td>
    </tr>
    <tr>
    	<td>排序字段<span class='need'>*</span></td>
        <td>
        	<input class="inputclass1" name="PosidHomeService[listorder]" style="width:20%" type="text" value="<?= $model->listorder ?>" datatype="n" nullmsg="请输入排序字段！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <tr>
    	<td>缩略图<span class='need'>*</span></td>
        <td>  
        	<input type ="hidden" id="thumb" name="PosidHomeService[topimage]" value="<?= $model->topimage ?>" />      	
        	<a name='athumb' id='athumb' href='#'><img id='imgthumb' src="<? if($model->topimage){echo $model->topimage;}else echo '/ueditor/dialogs/image/images/image.png'; ?>"
                                                           style='height:100px;' /></a><span class='need'>缩略图图片尺寸为750*370</span>
        </td>
    </tr> 
    <tr>
    	<td>参数1<span class='need'>*</span></td>
        <td>
        	<input class="inputclass1" name="PosidHomeService[param1]" style="width:50%" type="text" value="<?= $model->param1 ?>" datatype="*1-300" nullmsg='参数1必须输入' sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td>参数2</td>
        <td>
        	<input class="inputclass1" name="PosidHomeService[param2]" style="width:50%" type="text" value="<?= $model->param2 ?>" />
        </td>
    </tr>
    <tr>
    	<td>参数3</td>
        <td>
        	<input class="inputclass1" name="PosidHomeService[param3]" style="width:50%" type="text" value="<?= $model->param3 ?>" />
        </td>
    </tr>
    <tr>
    	<td>参数4</td>
        <td>
        	<input class="inputclass1" name="PosidHomeService[param4]" style="width:50%" type="text" value="<?= $model->param4 ?>" />
        </td>
    </tr>
    <tr>
    	<td>参数5</td>
        <td>
			<input class="inputclass1" name="PosidHomeService[param5]" style="width:50%" type="text" value="<?= $model->param5 ?>" />
        </td>
    </tr>       
    <tr>
    	<td>备注</td>
        <td>
        	<textarea name="PosidHomeService[desc]" style="width:70%;height:30px;" datatype="*0-100" errormsg="摘要最多100个字符！" sucmsg="&nbsp;" ><?= $model->desc ?></textarea>
        </td>
    </tr>    
     <input type="hidden" name="PosidHomeService[channelid]" value="<?php echo $channelid;?>" />
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
	        <span class='need'>选择不同类型时，请根据如下规则填写参数1-5，请谨慎填写，否则会引起客户端错误</span></br>
	        <span >html页类型:参数1请填写url</span></br>
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
  		//点击缩略图事件
  		$("a[name=athumb]").click(function () {
		    var content = '/posid/thumbupload';
			var title = '选择图片';
			content = content + '?url='+ encodeURI($('#thumb').val());
			layer.open({
		        type: 2,
		        title: title,
		        maxmin: false,
		        shadeClose: false, //点击遮罩关闭层
		        area : ['600px' , '400px'],
		        content: content
		    });
			return false;
		});
        //保存按钮
        $("#asave").click(function () {
            //检查缩略图
            t = $('#thumb').val();
            if(t == ''){
            	layer.msg('缩略图必须上传', {icon: 2});
          		return false;
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