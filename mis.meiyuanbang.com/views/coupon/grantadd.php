<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>


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
<!--             <input type ="hidden" name='isedit' value='1' />
 -->       <input type ="hidden" name="CouponGrantService[couponid]" value="<?= $model->couponid ?>" />
           
            <tr>
            	<td style="width: 80px">投放计划名称<span class='need'>*</span></td>
                <td>
                	<input class="inputclass1" name="CouponGrantService[title]" style="width:70%" type="text" value="<?= $model->title ?>" datatype="*1-100" nullmsg="请输入标题，最多100个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>
             <tr>
              <td>发放方式<span class='need'>*</span></td>
              <td>
                <select id="granttype" name="granttype">
                    <option value="0" <?php if($model->granttype==0){ echo 'selected=selected';}?> >实时发放</option>
                    <option value="1" <?php if($model->granttype==1){ echo 'selected=selected';}?>>预发放</option>
                </select>
              </td>
            </tr>
             <tr>
                <td style="width: 80px"><span id='spantitle'>用户id(英文,隔开)</span><span class='need'>*</span></td>
                <td>
                  <textarea name="ids" style="width:98%;height:100px;" datatype="*1-10000" errormsg="您输入的用户过多！" sucmsg="&nbsp;" ><?php if($model->granttype==0){ echo $model->uids;}else{echo $model->mobiles;} ?></textarea>
                </td>
            </tr>
            <tr>
		    	<td>发放数量<span class='need'>*</span></td>
		        <td>
					<input class="inputclass1" name="CouponGrantService[num]" style="width:100px" type="text" value="<?= $model->num ?>"  datatype="n" nullmsg="请输入发放数量！" sucmsg="&nbsp;" />
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
	
    //保存按钮
    $("#asave").click(function () {
       //检查
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

    $("#granttype").change(function () {
    	var grantypeval = $(this).val();
        if(grantypeval==0){
             $("#spantitle").html('用户id(英文,隔开)');
        } 
        else{
            $("#spantitle").html('手机号(英文,隔开)');
        }
    });
</script>