  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>  
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
  
  <div class="normaltable">
 <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
 <table>
 	<tbody>
 	
    <tr>
    	<td style="width: 80px">角色编号</td>
        <td>
        	<input type ="hidden" name='isedit' value='1' />
        	<input class="inputclass1" name="MisRoleService[roleid]" style="width:50px" type="text" value="" readonly='true' />
        </td>
    </tr>
    <tr>
    	<td style="width: 80px">角色名称</td>
        <td>
        	<input class="inputclass1" name="MisRoleService[rolename]" style="width:300px" type="text" value="" datatype="s1-20" nullmsg="请输入角色名称，最多20个字符！" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td>上级角色id</td>
        <td>
        	<input class="inputclass1" name="MisRoleService[parent_roleid]" style="width:300px" type="text" value="" datatype="n1-3" nullmsg="请填写正确的上级角色编号！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <tr>
    	<td>备注</td>
        <td>
        	<script id="editor" type="text/plain" style="width:1024px;height:500px;"></script>
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
  var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
</script>
  