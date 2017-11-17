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
            <?php if(isset($model->couponid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="CouponService[couponid]" value="<?= $model->couponid ?>" />
            <?php } ?>
           
            <tr>
            	<td style="width: 80px">课程券名称<span class='need'>*</span></td>
                <td>
                	<input class="inputclass1" name="CouponService[coupon_name]" style="width:98%" type="text" value="<?= $model->coupon_name ?>" datatype="*1-100" nullmsg="请输入标题，最多100个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>
             
            <tr>
                <td width="80">课程券类型<span class="need">*</span></td>
                <td>
                <select name="CouponService[coupon_type]" id="coupon_type">
                        <option value="3" <? if($model->coupon_type==3){?>selected <?}?> >全部适用</option>
                        <option value="1" <? if($model->coupon_type==1){?>selected <?}?>>课程</option>
                        <option value="2"  <? if($model->coupon_type==2){?>selected <?}?>>直播</option>
                </select>
               </td>
            </tr>

          <tr>
              <td>价格限制<span class="need">*</span></td>
                <td>
                  <input id='min_price' class="inputclass1" name="CouponService[min_price]" style="width:100px" type="text" value="<?= $model->min_price ?>"  />
                  -
                  <input id='max_price' class="inputclass1" name="CouponService[max_price]" style="width:100px" type="text" value="<?= $model->max_price ?>"  />
                </td>
            </tr>     

             <tr>
                <td style="width: 80px">备注</td>
                <td>
                  <textarea name="CouponService[mark]" style="width:98%;height:100px;"  ><?= $model->mark ?></textarea>
                </td>
            </tr>

            <tr>
               <td style="width: 80px">起止日期<span class="need">*</span></td>
               <td>
                   <input type="text" name="CouponService[btime]" id="btime" value="<?if($model->etime){echo date('Y-m-d H:i',$model->btime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
                   <script type="text/javascript">
                    Calendar.setup({
                       weekNumbers: true,
                       inputField : "btime",
                       trigger    : "btime",
                       dateFormat: "%Y-%m-%d %H:%M",
                       showTime: true,
                       minuteStep: 1,
                       onSelect   : function() {this.hide();}
                   });
                </script>
                至
                <input type="text" name="CouponService[etime]" id="etime" value="<? if($model->etime){echo date('Y-m-d H:i',$model->etime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
                <script type="text/javascript">
                    Calendar.setup({
                       weekNumbers: true,
                       inputField : "etime",
                       trigger    : "etime",
                       dateFormat: "%Y-%m-%d %H:%M",
                       showTime: true,
                       minuteStep: 1,
                       onSelect   : function() {this.hide();}
                   });
                </script>
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
        var min_price=$("#min_price").val();
        var max_price=$("#max_price").val();
        var btime=$("#btime").val();
        var etime=$("#etime").val();

        if(!min_price){
          alert("请输入最小使用金额！");
          return false;
        }if(!max_price){
          alert("请输入最大使用金额！");
          return false;
        }if(!btime){
          alert("请选择开始时间！");
          return false;
        }if(!etime){
          alert("请选择结束时间！");
          return false;
        }

        if(min_price>=max_price){
          alert("最小金额不能大于最大金额！");
          return false;
        }
        if(btime>=etime){
          alert("开始时间不能大于结束时间");
          return false;
        }
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