<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\service\dict\CourseDictDataService;

?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

<div class="normaltable">
    <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
    <table style='width:100%;'>
     	<tbody>
            <?php if(isset($model->soundid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="SoundResourceService[soundid]" value="<?= $model->soundid ?>" />
            <?php } ?>
           
            <tr>
            	<td style="width: 80px">类型<span class='need'>*</span></td>
                <td>
                	<select name="SoundResourceService[sound_type]" id="sound_type">
                        <option value="1" <? if($model->sound_type==1){ echo "selected";}?> >精讲文章</option>
                        <option value="2"  <? if($model->sound_type==2){ echo "selected";}?> >跟着画</option>
                </select>
                </td>
            </tr>

 			<tr>
                <td style="width: 80px">声音描述<span class='need'>*</span></td>
                <td>
                    <textarea name="SoundResourceService[desc]" style="width:98%;height:100px;" datatype="*0-500" errormsg="摘要最多500个字符！" sucmsg="&nbsp;" ><?= $model->desc ?></textarea>
                    
                </td>
            </tr>
 <tr>
                   <td style="width: 80px;">封面图</td>
                   <td>
                     <input type ="hidden" id="thumb_imgurl" name="SoundResourceService[imgurl]" value="<?= $model->imgurl ?>" />     
                    <a name='athumb' id='athumb_imgurl' data-name="imgurl" thumbid='0' href='#'><img id='imgthumb_imgurl' src="<? if($model->imgurl){echo $model->imgurl;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;' /></a>
                   </td>
                 </tr>
            <tr>


            <tr>
                   <td style="width: 80px;">音频文件<span class='need'>* </span></td>
                   <td>

                     <input type ="hidden" id="audio_sourceurl_val" name="SoundResourceService[sourceurl]" value="<?= $model->sourceurl ?>" />    

                     <input type ="hidden" id="audio_filesize" name="SoundResourceService[size]" value="<?= $model->size ?>" />    
                     <input type ="hidden" id="audio_duration" name="SoundResourceService[duration]" value="<?= $model->duration ?>" />    
                     <input type ="hidden" id="audio_filename" name="SoundResourceService[filename]" value="<?= $model->filename ?>" />    


	                   <audio id="audio_sourceurl"  src="<? if($model->sourceurl){echo $model->sourceurl;}else echo ''; ?>" controls="controls">
						</audio>
					<span class="normalbtn_l"><a name='eaudio' data-name="sourceurl"    href="#">上传</a></span>
                   </td>
                 </tr>
            <tr>

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
	    var index = parent.layer.getFrameIndex(window.name);


    //上传图片
    $("a[name=athumb]").click(function () {
                var content = '/sound/thumbupload';
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
    

    //上传语音
    $("a[name=eaudio]").click(function () {
                var content = '/sound/soundupload';
                var title = '编辑音频';
                content = content + '?url='+ encodeURI($('#audio_'+$(this).data('name')+"_val").val())+'&name='+ $(this).data('name');
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
    	console.log();
    	$("#audio_duration").val(parseInt($("#audio_sourceurl")[0].duration));
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