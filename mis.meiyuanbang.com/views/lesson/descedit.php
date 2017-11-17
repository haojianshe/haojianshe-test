<?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<div class="normaltable">
<?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
<table style='width:100%;'>
 	<tbody>
 	<?php if(isset($model->lessondescid)){?>
    <input type ="hidden" name='isedit' value='1' />
    <input type ="hidden" name="LessonDescService[lessondescid]" value="<?= $model->lessondescid ?>" />
    <?php } ?>
    <input type ="hidden" name="LessonDescService[lessonid]" value="<?= $model->lessonid ?>" />

    <tr>
        <td>描述图片<span class='need'>*</span></td>
        <td>
            <input type ="hidden" id="thumb0" name="LessonDescService[imgurl]" value='<?= $model->imgurl ?>' datatype="*" nullmsg="请上传图片！"/>
            <a name='athumb' id='athumb0' thumbid='0' href='#'>
                    <img id='img0' src="<? if($model->imgurl){echo json_decode($model->imgurl)->url; }else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
            </a>            
        </td>
    </tr>
    <tr>
        <td>节点音频<span class='need'>*</span></td>
        <td>
            <input hidden id="soundid"  class="inputclass1" name="LessonDescService[soundid]" style="width:70px" type="text" value="<?= $model->soundid ?>" datatype="n" nullmsg="请输入音频id！" sucmsg="&nbsp;"/>
            <audio id="audio_sound" src="<? if($sound){echo $sound['sourceurl'];} ?>" controls></audio>
            <span class="normalbtn_l"><a id="selbtn" href="javascript:;">选择</a></span>
        </td>
    </tr>
    <tr>
    	<td></td>
    	<td>
	        <div>
	        	<span class="normalbtn_l"><a id="asave" href="javascript:;">保存</a></span>
	        	<span class="normalbtn_l"><a id="aclose" href="javascript:;">关闭</a></span>	        	
	        </div>
        </td>
    </tr>
    </tbody>
 </table> 
<?php ActiveForm::end(); ?> 
</div>
<script>
    //点击缩略图事件
    $("a[name=athumb]").click(function () {
        var thumbid = $(this).attr("thumbid");
        var content = '/lesson/descthumbupload';
        var title = '编辑缩略图';
        content = content + '?id=' + thumbid +'&url='+ encodeURI($('#img'+thumbid).attr("src"));
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

		//父窗口句柄
		var index = parent.layer.getFrameIndex(window.name);
		//保存按钮
    $("#asave").click(function () {
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
                parent.location.reload();	        		/*parent.location.href='/lesson/descedit?lessonid=<?= $lessondescid ?>';*/
           }, 1000);
      	<? } else{ ?>
      		layer.msg('<?= $msg ?>', {icon: 2});
      	<? } ?>
    <? } ?>
	//表单验证
    $("#cmsform").Validform({
		tiptype:3,
	});	
      //选择节点语音
    $("#selbtn").click(function () {
        var content = '/sound/sel';
        var title = '选择音频';
        content = content + '?soundid='+ encodeURI($("#soundid").val())+"&sound_type=2";
        var search =layer.open({
            type: 2,
            title: title,
            maxmin: true,
            area : ['700px' , '600px'],
            content: content
          });
        layer.full(search);
    });
</script>