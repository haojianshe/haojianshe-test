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
          <?php if(isset($model->activityid)){?>
          <input type ="hidden" name='isedit' value='1' />
          <input type ="hidden" name="DkActivityService[activityid]" value="<?= $model->activityid ?>" />
          <?php if(isset($sms->sid)){?>
           <input type ="hidden" name="DkPushSmsService[sid]" value="<?= $sms->sid ?>" />
         <?php } ?>
          <?php } ?>
      



      <tr>
           <td style="width: 20%">活动标题<span class='need'>*</span></td>
           <td>
               <input class="inputclass1" name="DkActivityService[title]" style="width:99%" type="text" value="<?= $model->title ?>" datatype="*1-200" nullmsg="请输入活动标题，最多200个字！" sucmsg="&nbsp;"/>
           </td>
       </tr>
      


<tr>
   <td>活动图片（700*480）<span class='need'>*</span></td>
   <td>
       <input type ="hidden" id="thumb" name="DkActivityService[activity_img]" value="<?= $model->activity_img ?>" />       
       <a name='athumb' id='athumb' thumbid='0' href='#'><img id='imgthumb' src="<? if($model->activity_img){echo $model->activity_img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' /></a>
   </td>
</tr>








<tr>
   <td style="width: 150px">活动起止日期<span class='need'>*</span></td>
   <td>
       <input type="text" name="DkActivityService[activity_stime]" id="activity_stime" value="<?if($model->activity_stime){echo date('Y-m-d H:i',$model->activity_stime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
       <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "activity_stime",
           trigger    : "activity_stime",
           dateFormat: "%Y-%m-%d %H:%M",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
    至
    <input type="text" name="DkActivityService[activity_etime]" id="activity_etime" value="<? if($model->activity_etime){echo date('Y-m-d H:i',$model->activity_etime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
    <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "activity_etime",
           trigger    : "activity_etime",
           dateFormat: "%Y-%m-%d %H:%M",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
</td>
</tr>





      <tr>
           <td style="width: 20%">直播入口
                <select style="height:30px;" id="keytype" name="DkActivityService[is_live]" class="valid" value="<?=$model->is_live?>">
                <option value="1"<? if($model->is_live==1){ echo 'selected="selected" ';}?>  > 开启</option>
                <option value="2" <? if($model->is_live==2){ echo 'selected="selected" ';}?> >关闭</option>
                </select>
           </td>
           <td>
               <input class="inputclass1" name="DkActivityService[live_url]" style="width:99%" type="text" value="<?= $model->live_url ?>" />
           </td>
       </tr>
      



<tr>
   <td style="width: 150px">直播起止日期</td>
   <td>
       <input type="text" name="DkActivityService[live_stime]" id="live_stime" value="<?if($model->live_stime){echo date('Y-m-d H:i',$model->live_stime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
       <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "live_stime",
           trigger    : "live_stime",
           dateFormat: "%Y-%m-%d %H:%M",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
    至
    <input type="text" name="DkActivityService[live_etime]" id="live_etime" value="<? if($model->live_etime){echo date('Y-m-d H:i',$model->live_etime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
    <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "live_etime",
           trigger    : "live_etime",
           dateFormat: "%Y-%m-%d %H:%M",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
</td>
</tr>


      <tr>
           <td style="width: 20%">活动结束视频
                <select style="height:30px;" id="keytype" name="DkActivityService[is_recording]" class="valid" value="<?=$model->is_recording?>">
                <option value="1"<? if($model->is_recording==1){ echo 'selected="selected" ';}?>  > 开启</option>
                <option value="2" <? if($model->is_recording==2){ echo 'selected="selected" ';}?> >关闭</option>
                </select>
           </td>
           <td>
               <input class="inputclass1" name="DkActivityService[recording_url]" style="width:99%" type="text" value="<?= $model->recording_url ?>"  sucmsg="&nbsp;"/>
           </td>
       </tr>
      




      <tr>
           <td style="width: 20%">参与人数上限<span class='need'>*</span></td>
           <td>
               <input class="inputclass1" name="DkActivityService[max_count]" style="width:99%" type="text" value="<?= $model->max_count ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
           </td>
       </tr>
      


      <tr>
           <td style="width: 20%">老师评画人数<span class='need'>*</span></td>
           <td>
               <input class="inputclass1" name="DkActivityService[correct_count]" style="width:99%" type="text" value="<?= $model->correct_count ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
           </td>
       </tr>
      



<tr>
 <td style="width: 150px">报名截至时间<span class='need'>*</span></td>
   <td>
       <input  type="text" name="DkActivityService[reg_etime]" id="reg_etime" value="<?if($model->reg_etime){echo date('Y-m-d H:i',$model->reg_etime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
       <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "reg_etime",
           trigger    : "reg_etime",
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
         <input type ="hidden" class="inputclass1" name="DkActivityService[teacheruid]" style="width:150px" type="text" value="<?= $model->teacheruid ?>" />
          <div>  
              <span class="userinfo"><?if($usersinfo){echo $usersinfo[0]['sname'];}?></span>          
              <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
          </div>
       </td>
     </tr> 



      <tr>
           <td style="width: 20%">抽奖活动id<span class='need'>*</span></td>
           <td>
               <input class="inputclass1" name="DkActivityService[gameid]" style="width:99%" type="text" value="<?= $model->gameid ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
           </td>
       </tr>




      <tr>
           <td style="width: 20%">分享标题<span class='need'>*</span></td>
           <td>
               <input class="inputclass1" name="DkActivityService[share_title]" style="width:99%" type="text" value="<?= $model->share_title ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
           </td>
       </tr>

      <tr>
           <td style="width: 20%">分享描述<span class='need'>*</span></td>
           <td>
             <textarea name="DkActivityService[share_desc]" style="width:99%;height:100px;" value="<?= $model->share_desc ?>"  datatype="*1-200" nullmsg="请输入分享描述，最多200个字！" sucmsg="&nbsp;"><?= $model->share_desc ?></textarea>
           </td>
       </tr>

  

      <tr>
         <td>分享图片<span class='need'>*</span></td>
         <td>
             <input type ="hidden" id="thumb1" name="DkActivityService[share_img]" value="<?= $model->share_img ?>" />       
             <a name='athumb1' id='athumb1' thumbid='0' href='#'><img id='imgthumb1' src="<? if($model->share_img){echo $model->share_img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' /></a>
         </td>
      </tr>


      <tr>
           <td style="width: 20%">学生作品分享标题<span class='need'>*</span>
           </br>用户名称用<span class='need'>{user}</span>替代</td>
           <td>
               <input class="inputclass1" name="DkActivityService[submit_stitle]" style="width:99%" type="text" value="<?= $model->submit_stitle ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
           </td>
       </tr>

      <tr>
           <td style="width: 20%">学生作品分享描述<span class='need'>*</span></td>
           <td>
            <textarea name="DkActivityService[submit_sdesc]" style="width:99%;height:100px;" value="<?= $model->submit_sdesc ?>"  datatype="*1-100" nullmsg="请输入学生作品分享描述，最多100个字！"><?= $model->submit_sdesc ?></textarea>
               
           </td>
       </tr>


<? if($sms->status==3){ ?>
<tr>
 <td>短信已群发  </td>
 <td>群发时间 :<?=date('Y-m-d H:i',$sms->ptime)?></td>
</tr>


<tr>
 <td> 群发内容</td>
 <td>群发内容 <?=$sms->content?></td>
</tr>


  <?}else{?>


<tr>
 <td style="width: 150px">短信群发信息
   <select style="height:30px;" id="keytype" name="DkPushSmsService[status]" class="valid" value="<?=$sms->status?>">
                <option value="1"<? if($sms->status==1){ echo 'selected="selected" ';}?>  > 关闭</option>
                <option value="2" <? if($sms->status==2){ echo 'selected="selected" ';}?> >开启</option>
                </select></td>
 <td>

       群发时间<input  type="text" name="DkPushSmsService[ptime]" id="ptime" value="<?if($sms->ptime){echo date('Y-m-d H:i',$sms->ptime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
       <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "ptime",
           trigger    : "ptime",
           dateFormat: "%Y-%m-%d %H:%M",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
    </td>
</tr>
      <tr>
           <td style="width: 20%">群发内容<span class='need'>*(内容中必须包含【美院帮】)</span></td>
           <td>
           <textarea name="DkPushSmsService[content]" style="width:99%;height:100px;" value="<?= $sms->content ?>" ><?= $sms->content ?></textarea>
           </td>
       </tr>
    <? }?> 
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



<script type="text/javascript">
     //选择批改老师
        $("#selbtn").click(function () {
                var content = '/dkactivity/teachersel';
                var title = '选择批改老师';
                content = content + '?uid='+ encodeURI($("#teacheruid").val());
                var search =layer.open({
                    type: 2,
                    title: title,
                    maxmin: true,
                    area : ['700px' , '600px'],
                    content: content
                  });
                layer.full(search);
          });



      var index = parent.layer.getFrameIndex(window.name);
      //保存按钮
      $("#asave").click(function () {
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
      <?if(isset($isclose) && $isclose){ ?>
        parent.layer.msg('<?= $msg ?>');
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
      //保存按钮
        $("#asave").click(function () {
            
            //检查缩略图
            t = $('#thumb').val();
            if(t == ''){
              layer.msg('缩略图必须上传', {icon: 2});
                return false;
            }
            $("form").submit();
            return false;
        });

       
        //删除模块
        function delModule(){
          alert('shanchu');
        }
       
</script>
