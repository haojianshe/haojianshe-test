  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

  <div class="normaltable">
 <?php $form = ActiveForm::begin(['id' => 'resourceform']); ?>    
 <table>
 	<tbody>
    <tr>
    	<td style="width: 80px">资源编号</td>
        <td>
        	<?if(isset($isedit) && $isedit==1){?> 
        	<input type ="hidden" name='isedit' value='1' />
        	<? } ?>        	
        	<input class="inputclass1" name="MisResourceService[resourceid]" style="width:300px" type="text" value="<?= $model->resourceid ?>" <?if(isset($model->resourceid)){?> readonly='true' <? } ?>  datatype="s1-50" nullmsg="请输入资源编号，最多20个字符！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <tr>
    	<td style="width: 80px">资源名称</td>
        <td>
        	<input class="inputclass1" name="MisResourceService[resourcename]" style="width:300px" type="text" value="<?= $model->resourcename ?>" datatype="*1-20" nullmsg="请输入资源名称，最多20个字符！" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td>资源url</td>
        <td>
        	<input class="inputclass1" name="MisResourceService[url]" style="width:300px" type="text" value="<?= $model->url ?>" />
        </td>
    </tr>
    <tr>
    	<td>备注</td>
        <td>
        	<input class="inputclass1" name="MisResourceService[desc]" style="width:500px" type="text" value="<?= $model->desc ?>" />
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
        $(function () {
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
        $("#resourceform").Validform({
    		tiptype:3,
    	});
    </script>
  