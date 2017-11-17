<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>mis登录 - 美院帮</title>
<script type="text/javascript" src="/static/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/js/validform_v5.3.2_min.js"></script>
<script type="text/javascript" src="/static/js/layer/layer.js"></script>
<meta name="keywords" content="myb,meiyuanbang" />
<meta name="description" content="美院帮mis" />
<meta name="author" content="meiyuanbang.com" />
<meta name="copyright" content="2013-2014 bagecms.com" />
<link rel="stylesheet" type="text/css" href="/static/css/validform.css">
<link rel="stylesheet" type="text/css" href="/static/css/login-style.css" />
<script type="text/javascript" language="javascript">
    // show login form in top frame
    if (top != self) {
        window.top.location.href = location;
    }
</script>
</head>
<body>
<div id="login">
  <div class="wrapper">
    <div class="alert error" >&nbsp;</div>
    <div class="logo"></div>
    <div class="form">
      <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>      
      <dl>
        <dt>用户名</dt>
        <dd>
        <input class="input-password" name="LoginForm[username]" id="LoginForm_username" type="text" value='<?= $model->username ?>' maxlength="50" datatype="s1-20" nullmsg="请输入用户名，最多20个字符！" sucmsg="&nbsp;"  />
        </dd>
        <dt>密&nbsp;&nbsp;&nbsp;&nbsp;码</dt>
        <dd> <input class="input-password" name="LoginForm[password]" id="LoginForm_password" type="password" maxlength="32" datatype="*1-50" nullmsg="请输入密码！" sucmsg="&nbsp;" /> </dd>
        <dt>验证码</dt>
        <dd> 
        	<input class="input-password verify-code" name="LoginForm[captcha]" id="LoginForm_captcha" type="text"  datatype="s4-4" errormsg="请输入4位验证码！" nullmsg="请输入4位验证码！" sucmsg="&nbsp;" />          
        	<a id="a_changevalid" href="#">
        		<img alt="点击换图" align="absmiddle" id="img_valid" src="/index/captcha" style='width:100px;height:40px'>
        	</a> 
        	<a id="a_changevalid1" href="#">换一张</a>
        </dd>
        <dd>
          <input type="submit" name="login" class="input-login" value=""/>
          <input type="reset" name="login" class="input-reset" value=""/>
        </dd>
        <dd>
        </dd>
      </dl>
      <?php ActiveForm::end(); ?> 
    </div>
    <br class="clear-fix"/>
    <div class="copyright">Copyright&copy; <a title="美院帮" target="_blank" href="http://www.meiyuanbang.com">meiyuanbang.com</a>. All Thrusts Reserved.</div>
  </div>
</div>
            
<script type="text/javascript">
$(function(){
	//验证表单
	$("#login-form").Validform({
		tiptype:3,
	});
	//更换验证码
	$("#a_changevalid").click(function(){
		$("#img_valid").attr('src', '/index/captcha?v=' + Date.parse(new Date())); 
		return false;
	});
	$("#a_changevalid1").click(function(){
		$("#img_valid").attr('src', '/index/captcha?v=' + Date.parse(new Date())); 
		return false;
	});
})
//判断是否有错误提示
<? if($msg<>''){ ?>
	layer.msg('<?= $msg ?>');
<? } ?>
</script>
</body>
</html>