<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mis\lib\enumcommon\ActivityClickTypeEnum;
use common\service\DictdataService;
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
            <?php if (isset($model['data']['newsid'])) { ?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="newsid" id="newsidid" value="<?= $model['data']['newsid'] ?>" />
        <?php } ?>
             <input type ="hidden" name="lkidname" id="lkidname" value="<?php echo $lkid['lkid'];?>" />
        <tr>
            <td style="width: 20%">标题<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="title" style="width:99%" type="text" value="<?= $model['data']['title'] ?>" datatype="*1-200" nullmsg="请输入活动标题，最多200个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
           <tr>
            <td style="width: 20%">关键词<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="keywords" style="width:60%" type="text" value="<?= $model['data']['keywords'] ?>" datatype="*1-200" nullmsg="请输入活动关键词" sucmsg="&nbsp;"/>&nbsp;多关键词之间用“，”隔开
            </td>
        </tr>
             <tr>
                <td style="width: 150px">报名开始时间<span class='need'>*</span></td>
                <td>
                    <input type="text" name="start_time" id="start_time" value="<?php echo isset($model['data']['start_time'])?date('Y-m-d H:i:s',$model['data']['start_time']):"" ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
                    <script type="text/javascript">
                     Calendar.setup({
                        weekNumbers: true,
                        inputField : "start_time",
                        trigger    : "start_time",
                        dateFormat: "%Y-%m-%d %H:%M",
                        showTime: true,
                        minuteStep: 1,
                        onSelect   : function() {this.hide();}
                    });
                 </script>
             </td>
        </tr>
             <tr>
                <td style="width: 150px">报名开始截止<span class='need'>*</span></td>
                <td>
                    <input type="text" name="end_time" id="end_time" value="<?php echo isset($model['data']['end_time'])?date('Y-m-d H:i:s',$model['data']['end_time']):"" ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
                    <script type="text/javascript">
                     Calendar.setup({
                        weekNumbers: true,
                        inputField : "end_time",
                        trigger    : "end_time",
                        dateFormat: "%Y-%m-%d %H:%M",
                        showTime: true,
                        minuteStep: 1,
                        onSelect   : function() {this.hide();}
                    });
                 </script>
             </td>
        </tr>
         <tr>
            <td style="width: 20%">报名上限<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" id="signup_limit" name="signup_limit" style="width:99%" type="text" value="<?= $model['data']['signup_limit'] ?>" datatype="/^-?[1-9]\d*$/" nullmsg="报名上限不能为空" errormsg="必须为数字" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">打分老师<span class='need'>*</span></td>
            <td>
                <select style="height:30px;" id="teacher_id" name="teacher_id" class="valid" value="">
                    <option value="0" >请选择</option>
                    <?php
                    foreach ($admin['admin'] as $key => $val) {
                        ?>
                        <option value="<?php echo $val['mis_userid']?>" <?php if($model['data']['teacher_id']==$val['mis_userid']){ echo 'selected=selected';}?> ><?php echo $val['mis_username']?></option>
                        <?php
                         }
                      ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">封面概述<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="desc" style="width:99%" type="text" value="<?= $model['data']['desc'] ?>"  datatype="*1-200" nullmsg="概述不能为空" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr class="activity_content">
   <td>内容</td>
   <td>
       <script name='content' id="editor" type="text/plain" style="width:98%;height:500px;"></script>
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
               ue.setContent('<?= $model['data']['content']?>');
        });
        
  function del(i){
       $("#title_"+i).remove();
       var hiddenName = $("input[name=hiddenids]").val();
       var a = hiddenName.replace(i,"");
       $("input[name=hiddenids]").val(a);
       return false;
    }
</script>

<script type="text/javascript">
    //状元分享会
     $("#addAq").click(function(){
          var hiddenName = $("input[name=hiddenids]").val();
          var lkid = $("#lkid_id").val();
          var content = '/lkactivity/qa';
          var title = '选择问答列表';
             content = content + '?lkid='+ lkid+'&hiddenids='+hiddenName;
             layer.open({
             type: 2,
             title: title,
             maxmin: false,
             shadeClose: false, //点击遮罩关闭层
             area: ['60%', '80%'],
             content: content
        }); 
     });
     //名师大讲堂
        $("#addAqMs").click(function () {
                var hiddenName = $("input[name=hiddenMsIds]").val();
                var lkid = $("#lkid_id").val();
                var content = '/lkactivity/ms';
                var title = '选择问答列表';
                content = content + '?lkid='+ lkid+'&hiddenids='+hiddenName;
                layer.open({
                type: 2,
                title: title,
                maxmin: false,
                shadeClose: false, //点击遮罩关闭层
                area: ['60%', '80%'],
                content: content
            });
          });

      var index = parent.layer.getFrameIndex(window.name);
      //保存按钮
      $("#asave").click(function () {
          var start_time = $("#start_time").val();
          if(start_time==''){
                 layer.msg('必须选择开始时间!', {icon: 2});
                return false;
            }
          var end_time = $("#end_time").val();
          if(end_time==''){
                 layer.msg('必须选择结束时间!', {icon: 2});
                return false;
            }
            
           if($("#teacher_id").val()==0){
                 layer.msg('请您选择打分老师!', {icon: 2});
                return false;
            }
            
            
          $("form").submit();
          return false;
        });
        $("#cmsform").Validform({
          tiptype:3,
      }); 
      //关闭按钮,刷新父窗口
      $('#aclose').click(function(){
        //parent.location.reload(); 
        parent.layer.close(index);
      });
       
      //保存成功后自动关闭
      <?if(isset($model['isclose']) && $model['isclose']){ ?>
        parent.layer.msg('<?= $model['msg'] ?>');
        setTimeout(function (){
            parent.location.reload();
       }, 1000);
      <? } ?>

  //点击缩略图事件
      $("a[name=athumb]").click(function () {
              var content = '/activity/thumbupload';
              var title = '编辑缩略图';
              content = content + '?url='+ encodeURI($('#thumb').val());
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
                  shadeClose: false, //点击遮罩关闭层
                  area : ['600px' , '400px'],
                  content: content
              });
              return false;
          });

      //点击缩略图事件
      $("a[name=athumb1]").click(function () {
              var content = '/activity/cthumbupload';
              var title = '编辑缩略图';
              content = content + '?url='+ encodeURI($('#thumb1').val())+"&imgclass=imgthumb1&valclass=thumb1";
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
                  shadeClose: false, //点击遮罩关闭层
                  area : ['600px' , '400px'],
                  content: content
              });
              return false;
          });
        //删除模块
        function delModule(){
          alert('shanchu');
        }
       
</script>
