  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
      
  <div class="normaltable">
 <form id="cmsform" action="/sound/soundupload" method="post" role="form" enctype="multipart/form-data">
 <table>
 	<tbody>
    <input type ="hidden" name="url" value="<?= $model['url'] ?>" />
    <input type ="hidden" name="name" value="<?= $model['name'] ?>" />

    <tr>
    	<td style="width: 80px"></td>
        <td>
        	<img src="<? if($model['url']){echo $model['url'];}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;max-width:400px;max-height: 100px;' />
        </td>
    </tr>
    <tr>
    	<td style='text-align:right;'><span class='need'>*</span></td>
        <td>
        	<input type="file" name="file_thumb" id="file_thumb" />
        </td>
    </tr>    
    <tr>
    	<td></td>
    	<td>
	        <div>
	        	<span class="normalbtn_l"><a id="asave" href="#">上传</a></span>
	        	<? if($model['url'] && !isset($isclose)) { ?>
	        	<span class="normalbtn_l"><a id="adel" href="#">删除</a></span>
	        	<? } ?>
	        	<span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>	        	
	        </div>
        </td>
    </tr>
    </tbody>
 </table>  
 </form>
 </div>
  <script>
  		//父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
        //保存按钮
        $("#asave").click(function () {
        	var f=$("#file_thumb").val();
            if(f==""){
            	layer.msg('请选择语音', {icon: 2});
                return false;
            }
            else
            {
            	if(!/\.(mp3)$/.test(f))
            	{
            		layer.msg('上传文件必须为MP3语音', {icon: 2});
              		return false;
            	}
            }            
            $("form").submit();
            return false;
        });
        //关闭
        $('#aclose').click(function(){
        	parent.layer.close(index);
        });

        <? if($model['url'] && !isset($isclose) ) { ?>
        //删除
        $('#adel').click(function(){
        	//删除对应缩略图
        	var newurl = "/ueditor/dialogs/image/images/image.png";
        	parent.$("#audio_<?= $model['name']?>").attr("src",newurl+'?d='+ Date.parse(new Date()));
       		parent.$('#audio_<?= $model['name']?>_val').val('');
          parent.$('#audio_filesize').val('');
          parent.$('#audio_filename').val('');
        	parent.layer.close(index);
        });
        <? } ?>
        
        //保存成功后更新父页面的对应图片，自动关闭
       	<?if(isset($isclose) && $isclose==true){ ?>
       		var newurl = "<?= $model['url']?>";
   			  parent.$("#audio_<?= $model['name']?>").attr("src",newurl+'?d='+ Date.parse(new Date()));
       		parent.$('#audio_<?= $model['name']?>_val').val(newurl);
          parent.$('#audio_filesize').val(<?= $model['filesize']?>);
          parent.$('#audio_filename').val("<?= $model['filename']?>");

       		parent.layer.close(index);
	   	<? }?>
	   	<?if(isset($msg) && $msg<>''){ ?>
    	layer.msg('<?= $msg ?>', {icon: 2});
        <? } ?>
 
    </script>