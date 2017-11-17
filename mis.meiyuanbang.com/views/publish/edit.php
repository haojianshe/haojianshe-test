<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mis\lib\enumcommon\ActivityClickTypeEnum;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
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
          <?php if(isset($usermodel['uid'])){?>
          <input type ="hidden" name='isedit' value='1' />
          <input type ="hidden" name="uid" id="uidid" value="<?= $usermodel['uid'] ?>" />
          <?php } ?>
          <tr>
           <td style="width: 80px">昵称<span class='need'>*</span></td>
           <td>
               <input class="inputclass1" name="sname"  id="sname" style="width:30%"  type="text" value="<?= $usermodel['sname'] ?>" datatype="*1-20" nullmsg="请输入用户昵称，最多20个字！" sucmsg="&nbsp;"/>
           </td>
       </tr>
       
         <tr>
             <td>介绍</td>
            <td>
<textarea name="intro" style="width:99%;height:100px;" datatype="*1-120" nullmsg="请输入介绍，最多120个字！" id="intro" sucmsg="&nbsp;"><?= $usermodel['intro'] ?></textarea>
       <span class="Validform_checktip"></span></td>
        </tr>
        <tr >
            <td style="width: 80px">手机号</td>
            <td>
<input class="" name="umobile" id="umobile" maxlength="11" style="width:30%" type="text" value="<?= $usermodel['umobile'] ?>" datatype="/^-?[0-9]\d*$/"  errormsg="必须为数字" sucmsg="&nbsp;"/>
            </td>
        </tr>
  <tr>
   <td>密码</td>
   <td>
       <input class="" name="pass_word" id="pass_word" style="width:30%" type="password" value="" />
   </td>
</tr>

   <td>缩略图<span class='need'>*</span></td>
   <td>
       <input type ="hidden" id="thumb" name="thumb" value="<?= $usermodel['avatar'] ?>" />      	
       <input type ="hidden" id="rcid" name="rid" value="<?= $usermodel['rid'] ?>" />      	
       <!--<input type ="hidden" id="jsonMode" name="jsonMode" value="" />-->      	
       <a name='athumb' id='athumb' thumbid='0' href='#'>
           <img id='imgthumb' src="<? if($usermodel['avatar']){echo $usermodel['avatar'];}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
       </a>
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
             //清空浏览器默认记录
                var uid = $("#rcid").val();
                if(uid==''){
                    $("#umobile").val('');
                    $("#pass_word").val('');
                }
                
  		//父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
  		//点击缩略图事件
  		$("a[name=athumb]").click(function () {
                var content = '/publish/thumbupload';
                var title = '编辑缩略图';
               content = content + '?url='+ encodeURI($('#thumb').val());
                layer.open({
                        type: 2,
                        title: title,
                        maxmin: false,
		        shadeClose: false, //点击遮罩关闭层
		        area : ['60%' , '60%'],
		        content: content
		    });
              return false;
          });
          
         
        //保存按钮
        $("#asave").click(function () {
           var sname =$("#sname").val();//用户昵称
           var intro = $("#intro").val();//用户介绍
           var umobile = $("#umobile").val();//用户手机号
           var pass_word = $("#pass_word").val();//用户密码
           var thumb = $("#rcid").val();//用户头像
           var uid = $("#uidid").val();//用户编号
           if(uid==undefined){
             uid = 0;
           }
           //检查用户手机号
            if($.trim(sname) == ''){
            	layer.msg('用户昵称不能为空!', {icon: 2});
                $("#sname").val('');
                return false;
            }
            //检查用户手机号
            if($.trim(umobile) == ''){
            	layer.msg('手机号必须上传', {icon: 2});
                $("#umobile").val('');
                return false;
            }
             var t = /^0?(13[0-9]|15[012356789]|18[0-9]|17[0-9])[0-9]{8}$/;
             if(!t.test(umobile)){
                layer.msg('手机号不符合规则', {icon: 2});
                $("#umobile").val('');
                $("#umobile").focus();
                return false;
             }
            //检查用户密码
            if(pass_word == ''&& uid==0){
                    if(pass_word.length<8 || pass_word.length>16){
                    layer.msg('密码长度限制在8-16位之间!', {icon: 2});
                    return false ; 
                }
            }
             //检查用户密码
            if(pass_word != ''&& uid !=0){
                    if(pass_word.length<8 || pass_word.length>16){
                    layer.msg('密码长度限制在8-16位之间!', {icon: 2});
                    return false ; 
                }
            }
            
            t = $("#rcid").val();
            if(t ==''){
                  layer.msg('头像不能为空!', {icon: 2});
                    return false ;
            }
            //提交添加的用户数据
            var data = {
                sname : sname,
                intro : intro,
                umobile : umobile,
                pass_word : pass_word,
                uid : uid,
                thumb : thumb
            }
            var url = '/publish/set_user';
            $.post(url,data,function(m){
                if(m==803 || m==8031){
                layer.msg('手机号已经存在', {icon: 2});
                return false;
                }else if(m==802 || m==8022){
                layer.msg('昵称已经存在', {icon: 2});
                return false;
                }else{
                     layer.msg('录入成功', {icon: 1});
                     parent.location.reload();
                }
            },'json');
            //$("form").submit();
           // return false;
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