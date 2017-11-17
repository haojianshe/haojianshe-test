  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
      
  <div class="normaltable">
 <form id="cmsform" action="/lesson/thumbupload" method="post" role="form" enctype="multipart/form-data">
 <table>
 	<tbody>
    <input type ="hidden" name="id" value="<?= $model['id'] ?>" />
    <input type ="hidden" name="url" value="<?= $model['url'] ?>" />
    <tr>
    	<td style="width: 80px"></td>
        <td>
        	<img src="<? if($model['url']){echo $model['url'];}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;'  />
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
            	layer.msg('请选择图片', {icon: 2});
                return false;
            }
            else
            {
            	if(!/\.(gif|jpg|jpeg|png|GIF|JPG|JPEG|PNG)$/.test(f))
            	{
            		layer.msg('图片类型必须是.gif,jpeg,jpg,png中的一种', {icon: 2});
              		return false;
            	}
            }            
            $("form").submit();
            return false;
        });
        //关闭
        $('#aclose').click(function(){
        	//parent.location.reload(); 
        	parent.layer.close(index);
        });

        <? if($model['url'] && !isset($isclose) ) { ?>
        //删除
        $('#adel').click(function(){
        	//删除对应缩略图
        	var newurl = "/ueditor/dialogs/image/images/image.png";
   			var thimbid = '<?= $model['id'] ?>';
       		var imgid = 'img' + thimbid;
       		var textid = 'thumb' + thimbid;
        	parent.$("#"+imgid).attr("src",newurl+'?d='+ Date.parse(new Date()));
       		parent.$('#'+textid).val('');
        	parent.layer.close(index);
        });
        <? } ?>
        
        //保存成功后更新父页面的对应图片，自动关闭
       	<?if(isset($isclose) && $isclose==true){ ?>
       		var newurl = "<?= $model['url']?>";
   			var thimbid = '<?= $model['id'] ?>';
       		var imgid = 'img' + thimbid;
       		var textid = 'thumb' + thimbid;
       		parent.$("#"+imgid).attr("src",newurl+'?d='+ Date.parse(new Date()));
       		parent.$('#'+textid).val(newurl);
       		parent.layer.close(index);
	   	<? }?>
	   	<?if($msg<>''){ ?>
    	layer.msg('<?= $msg ?>', {icon: 2});
        <? } ?>
        
        $("#cmsform").Validform({
    		tiptype:3,
    	});	    
    </script>