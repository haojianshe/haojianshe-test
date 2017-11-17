  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
  <!--添加乐视视频按钮-->
  <script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/letv/levedio.js"></script>  
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
 	<?php if(isset($UserModel->uid)){?>
    <input type ="hidden" name='isedit' value='1' />
    <input type ="hidden" name="uid" value="<?= $UserModel->uid ?>" />
    <?php } ?>
    <tr>
    	<td style="width: 80px">姓名<span class='need'>*</span></td>
        <td>
        	<input class="inputclass1" name="user_name" style="width:130px" type="text" value="<?= $UserModel->user_name ?>" datatype="*1-30" nullmsg="请输入姓名，最多30个字！" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td>年龄<span class='need'>*</span></td>
        <td>
	<input class="inputclass1" name="user_age" style="width:130px" type="text" value="<?= $UserModel->user_age ?>"   sucmsg="&nbsp;" />
        </td>
    </tr>
     <tr>
    	<td style="width: 80px">联系电话<span class='need'>*</span></td>
        <td>
        	<input class="inputclass1" name="umobile" style="width:20%" type="text" value="<?= $UserModel->umobile ?>" 
                       datatype="/^-?[1-9]\d*$/" nullmsg="不能为空"  errormsg="必须为数字" maxlength="11"  sucmsg="&nbsp;" sucmsg="&nbsp;"/>
        </td>
    </tr>
     <tr>
    	<td>是否上体验课</td>
        <td>   
            <input type="radio" value="1" <?php if($UserModel->is_expe==1 || $UserModel->is_expe==''){ echo 'checked=checked';} ?>  name="is_expe" />是
            <input type="radio" value="2"  <?php if($UserModel->is_expe==2){ echo 'checked=checked';} ?> name="is_expe" />否
        </td>
    </tr>
      <tr>
               <td style="width: 20%">上体验课时间<span class='need'>*</span></td>
            <td>
                    <input type="text" name="expe_time" id="expe_time" value="<?php echo isset($UserModel->expe_time)?date('Y-m-d H:i:s',$UserModel->expe_time):"" ?>" class="inputclass1" readonly="readonly" style="width:240px" />&nbsp;
                    <script type="text/javascript">
                     Calendar.setup({
                        weekNumbers: true,
                        inputField : "expe_time",
                        trigger    : "expe_time",
                        dateFormat: "%Y-%m-%d %H:%M",
                        showTime: true,
                        minuteStep: 1,
                        onSelect   : function() {this.hide();}
                    });
                 </script>
             </td>
        </tr>
          <tr>
    	<td>是否报名</td>
        <td>   
            <input type="radio" value="1" <?php if($UserModel->is_sign==1 || $UserModel->is_sign==''){ echo 'checked=checked';} ?>  name="is_sign" />是
            <input type="radio" value="2"  <?php if($UserModel->is_sign==2){ echo 'checked=checked';} ?> name="is_sign" />否
        </td>
         </tr>
             <tr>
               <td style="width: 20%">报名时间<span class='need'>*</span></td>
            <td>
                    <input type="text" name="sign_time" id="sign_time" value="<?php echo isset($UserModel->sign_time)?date('Y-m-d H:i:s',$UserModel->sign_time):"" ?>" class="inputclass1" readonly="readonly" style="width:240px" />&nbsp;
                    <script type="text/javascript">
                     Calendar.setup({
                        weekNumbers: true,
                        inputField : "sign_time",
                        trigger    : "sign_time",
                        dateFormat: "%Y-%m-%d %H:%M",
                        showTime: true,
                        minuteStep: 1,
                        onSelect   : function() {this.hide();}
                    });
                 </script>
             </td>
        </tr>
    <tr>
    	<td>报名方式</td>
        <td>
        	<input class="inputclass1" name="sign_type" style="width:400px" type="text" value="<?= $UserModel->sign_type ?>"/>
        </td>
    </tr>
    <tr>
    	<td>用户地址</td>
        <td>
        	<input class="inputclass1" name="user_address" style="width:400px" type="text" value="<?= $UserModel->user_address ?>" />
        </td>
    </tr>

    <tr>
    	<td>备注信息<span class='need'>*</span></td>
        <td>
        	<script name='mark' id="editor" type="text/plain" style="width:98%;height:500px;"></script>
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
  		//显示富文本框内容
  		  var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
  		ue.ready(function() {
  			ue.setContent('<?= $UserModel->mark ?>');   
  		});
  		//保存按钮
        $("#asave").click(function () {
            //检查富文本框
            $("form").submit();
            return false;
        });
        //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	//parent.location.reload(); 
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