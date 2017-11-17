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
   <table style="width:100%;">
    <tbody>
      <tr >
       <td style="width: 150px">活动编号</td>
       <td>
       <?php if(isset($model->fastcorrectid )){?>
         <input type ="hidden" name='isedit' value='1' />
         <input type ="hidden" class="inputclass1" name="FastCorrectService[fastcorrectid]" style="width:300px" type="text" value="<?= $model->fastcorrectid ?>" />
          <?php } ?>
         <?= $model->fastcorrectid ?>
       </td>
     </tr>
     
     <tr>
       <td >活动标题<span class="need">*</span></td>
       <td>
         <input  class="inputclass1" name="FastCorrectService[activity_name]" style="width:500px" type="text"  value="<?= $model->activity_name ?>" datatype="*1-40" errormsg="最多40个字符！" sucmsg="&nbsp;" />
        
       </td>
     </tr>   

     <tr>
       <td >等待批改分享标题<span class="need">*</span></td>
       <td>
         <input  class="inputclass1" name="FastCorrectService[wait_title]" style="width:500px" type="text"  value="<?= $model->wait_title ?>"  datatype="*1-40" errormsg="最多40个字符！" sucmsg="&nbsp;"/>
        
       </td>
     </tr> 

      <tr>
       <td >正在批改分享标题<span class="need">*</span></td>
       <td>
         <input  class="inputclass1" name="FastCorrectService[start_title]" style="width:500px" type="text" value="<?= $model->start_title ?>" datatype="*1-40" errormsg="最多40个字符！" sucmsg="&nbsp;" />
        
       </td>
     </tr> 




        <tr>
           <td>老师头像(分享图片)<span class='need'>*</span></td>
           <td>
               <input type ="hidden" id="thumb" name="FastCorrectService[teacher_avatar]" value="<?= $model->teacher_avatar ?>" />       
               <a name='athumb' id='athumb' thumbid='0' href='#'><img id='imgthumb' src="<? if($model->teacher_avatar){echo $model->teacher_avatar;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' /></a>
           </td>
        </tr>

       
      <tr>
       <td >老师标题<span class="need">*</span></td>
       <td>
         <input  class="inputclass1" name="FastCorrectService[teacher_name]" style="width:500px" type="text"  value="<?= $model->teacher_name ?>"  datatype="*1-40" errormsg="最多40个字符！" sucmsg="&nbsp;"/>
        
       </td>
     </tr> 

      <tr>
       <td >老师简介（分享内容）<span class="need">*</span></td>
       <td>
         <input  class="inputclass1" name="FastCorrectService[teacher_desc]" style="width:500px" type="text"  value="<?= $model->teacher_desc ?>" datatype="*1-80" errormsg="最多80个字符！" sucmsg="&nbsp;"/>
        
       </td>
     </tr>     

   <tr>
   <td >起止日期<span class="need">*</span></td>
   <td>
       <input type="text" name="FastCorrectService[starttime]" id="starttime" value="<?if($model->starttime){echo date('Y-m-d H:i',$model->starttime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
       <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "starttime",
           trigger    : "starttime",
           dateFormat: "%Y-%m-%d %H:%M",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
    至
    <input  type="text" name="FastCorrectService[endtime]" id="endtime" value="<? if($model->endtime){echo date('Y-m-d H:i',$model->endtime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
    <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "endtime",
           trigger    : "endtime",
           dateFormat: "%Y-%m-%d %H:%M",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
</td>
</tr>
  <tr>
       <td >批改老师<span class="need">*</span></td>
       <td>
         <input type ="hidden" class="inputclass1" name="FastCorrectService[correct_teacheruids]" style="width:150px" type="text" value="<?= $model->correct_teacheruids ?>" />
          <div>  
              <span class="userinfo"><?php if(($usersinfo)){
                $snames=[];
                foreach ($usersinfo as $key => $value) {
                    $snames[]= $value['sname'];
                }
                echo implode(",",$snames);
                }?></span>          
              <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
          </div>
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


    		//父窗口句柄
    		var index = parent.layer.getFrameIndex(window.name); 

//点击缩略图事件
        $("#selbtn").click(function () {
                var content = '/fastcorrect/teachersel';
                var title = '选择批改老师';
                content = content + '?uids='+ encodeURI($("[name='FastCorrectService[correct_teacheruids]']").val());
                var search =layer.open({
                    type: 2,
                    title: title,
                    maxmin: true,
                    area : ['700px' , '600px'],
                    content: content
                  });
                layer.full(search);
          });

            //保存按钮
          $("#asave").click(function () {

              //判断开始结束时间
              if(Date.parse(new Date(($("[name='FastCorrectService[endtime]']").val())))-Date.parse(new Date(($("[name='FastCorrectService[starttime]']").val())))<0){
                 layer.msg('结束时间应该大于开始时间！！！', {icon: 2});
                return;
              } 
              if(!$("[name='FastCorrectService[activity_name]']").val()){
                  layer.msg('请输入活动名称！！！', {icon: 2});
                return;
              }
              if(!$("[name='FastCorrectService[starttime]']").val()){
                layer.msg('请选择开始时间！！！', {icon: 2});
                return;
              }
              if(!$("[name='FastCorrectService[endtime]']").val()){
                  layer.msg('请选择结束时间！！！', {icon: 2});
                return;
              }
              if(!$("[name='FastCorrectService[correct_teacheruids]']").val()){
                layer.msg('请选择批改老师！！！', {icon: 2});
                return;
              }      
              //检查缩略图
              t = $('#thumb').val();
              if(t == ''){
                layer.msg('老师头像必须上传', {icon: 2});
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
          <?if(isset($isclose) && $isclose){ ?>
          	parent.layer.msg('<?= $msg ?>');
          	setTimeout(function (){
          		parent.location.reload();
           }, 1000);
            <? } ?>        
          //表单验证
              $("#cmsform").Validform({
                tiptype:3,
            });
                
</script>
        