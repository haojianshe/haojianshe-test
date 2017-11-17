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
            <?php if (isset($model->invitation_id)) { ?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="invitation_id" id="invitation_id" value="<?= $model->invitation_id ?>" />
        <?php } ?>
        <tr>
            
            <td style="width: 20%">开始时间<span class='need'>*</span></td>
            <td>
                    <input type="text" name="btime" id="btime" value="<?php echo isset($model->btime)?date('Y-m-d H:i:s',$model->btime):"" ?>" class="inputclass1" readonly="readonly" style="width:240px" />&nbsp;
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
             </td>
        </tr>
          <tr>
               <td style="width: 20%">邀请截止时间<span class='need'>*</span></td>
            <td>
                    <input type="text" name="etime" id="etime" value="<?php echo isset($model->etime)?date('Y-m-d H:i:s',$model->etime):"" ?>" class="inputclass1" readonly="readonly" style="width:240px" />&nbsp;
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
               <td style="width: 20%">领奖截止时间<span class='need'>*</span></td>
            <td>
                    <input type="text" name="award_time" id="award_time" value="<?php echo isset($model->award_time)?date('Y-m-d H:i:s',$model->award_time):"" ?>" class="inputclass1" readonly="readonly" style="width:240px" />&nbsp;
                    <script type="text/javascript">
                     Calendar.setup({
                        weekNumbers: true,
                        inputField : "award_time",
                        trigger    : "award_time",
                        dateFormat: "%Y-%m-%d %H:%M",
                        showTime: true,
                        minuteStep: 1,
                        onSelect   : function() {this.hide();}
                    });
                 </script>
             </td>
        </tr>
         <tr>
            <td>邀请主图<span class='need'>*</span></td>
            <td>
                <input type ="hidden" id="activity_url" name="activity_url" value="<?= $model->activity_url ?>" />       
                <a name='athumbUrl' id='athumbUrl' thumbid='0' href='#'>
                    <img id='imgthumbUrl' src="<? if($model->activity_url){echo $model->activity_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
                    
                </a>&nbsp;&nbsp;&nbsp;
                <span class='need'>宽度为750，高度建议不超过900</span>
            </td>
        </tr>
         <tr>
            <td>被邀请人主图<span class='need'>*</span></td>
            <td>
                <input type ="hidden" id="thumb1" name="activity_invitee_url" value="<?= $model->activity_invitee_url ?>" />       
                <a name='athumb1' id='athumb1' thumbid='0' href='#'>
                    <img id='imgthumb1' src="<? if($model->activity_invitee_url){echo $model->activity_invitee_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
                </a>&nbsp;&nbsp;&nbsp;
                <span class='need'>宽度为750，高度建议不超过900</span>
            </td>
        </tr>
         
        <tr>
            <td style="width: 20%">领奖人信息<span class='need'>*</span></td>
            <td>
                <textarea name="honorees_instruction" style="width: 90%; height: 120px; margin-top: 0px; margin-bottom: 0px;" value="<?= $model->honorees_instruction ?>"  datatype="*1-200" nullmsg="领奖人信息！" sucmsg="&nbsp;"><?= $model->honorees_instruction ?></textarea>
            </td>
        </tr>
        
         <tr>
            <td style="width: 20%">被邀请奖品<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="invited_id" style="width:50%" type="text" value="<?= $model->invited_id ?>" datatype="/^-?[1-9]\d*$/" nullmsg="被邀请奖品id不能为空" errormsg="必须为数字" sucmsg="&nbsp;"/>&nbsp;&nbsp;<span class='need'>填写奖品id</span>
            </td>
        </tr>
        
        <tr>
            <td style="width: 20%">邀请奖品<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="prizes_ids" style="width:50%" type="text" value="<?= $model->prizes_ids ?>" datatype="*1-50" nullmsg="请输邀请奖品！" sucmsg="&nbsp;"/>&nbsp;&nbsp;<span class='need'>多奖品id请用英文","隔开</span>
            </td>
        </tr>
        
        <tr>
            <td style="width: 20%">分享标题<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="share_title" style="width:50%" type="text" value="<?= $model->share_title ?>" datatype="*1-50" nullmsg="请输入分享标题，最多50个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
            <tr>
            <td>分享主图<span class='need'>*</span></td>
            <td>
                <input type ="hidden" id="thumb11" name="share_img" value="<?= $model->share_img ?>" />       
                <a name='athumb11' id='athumb11' thumbid='0' href='#'>
                    <img id='imgthumb11' src="<? if($model->share_img){echo $model->share_img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
                </a>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">分享描述<span class='need'>*</span></td>
            <td>
                <textarea name="share_desc" style="width: 99%; height: 100px; margin-top: 0px; margin-bottom: 0px;" value="<?= $model->share_desc ?>"  datatype="*1-100" nullmsg="请输入分享描述，最多100个字！" sucmsg="&nbsp;"><?= $model->share_desc ?></textarea>
            </td>
        </tr>
        
         <tr>
            <td style="width: 20%">短信邀请及复制链接文案<span class='need'>*</span></td>
            <td>
                <textarea name="sms_copy" style="width: 99%; height: 100px; margin-top: 0px; margin-bottom: 0px;" value="<?= $model->sms_copy ?>"  datatype="*1-200" nullmsg="请输入短信邀请及复制链接文案，最多200个字！" sucmsg="&nbsp;"><?= $model->sms_copy ?></textarea>
            </td>
        </tr>
           <tr>
        <td>活动说明</td>
            <td>
                <script name='activity_rules' id="editor" type="text/plain" style="width:770px;height:500px;"></script>
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
       //点击缩略图事件
      $("a[name=athumbUrl]").click(function () {
             // var content = '/invitation/athumburl';
              var content = '/activity/cthumbupload';
              var title = '编辑缩略图';
              content = content + '?url='+ encodeURI($('#activity_url').val())+"&imgclass=imgthumbUrl&valclass=activity_url";
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
              //点击缩略图事件
      $("a[name=athumb11]").click(function () {
              var content = '/activity/cthumbupload';
              var title = '编辑缩略图';
              content = content + '?url='+ encodeURI($('#thumb11').val())+"&imgclass=imgthumb11&valclass=thumb11";
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
</script>
        <script>
                      //父窗口句柄
                        var index = parent.layer.getFrameIndex(window.name);
                        //显示富文本框内容
                         var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
                        ue.ready(function() {
                       ue.setContent('<?= $model['activity_rules']?>');
                       });
                        function del(i){
                             $("#title_"+i).remove();
                             var hiddenName = $("input[name=hiddenids]").val();
                             var a = hiddenName.replace(i,"");
                             $("input[name=hiddenids]").val(a);
                             return false;
                          }
        </script>
        <script>
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
            var btime = $("#btime").val();
            if(btime==''){
                 layer.msg('必须选择开始时间!', {icon: 2});
                return false;
            }
            var etime = $("#etime").val();
              if(etime==''){
                 layer.msg('必须选择邀请截止时间!', {icon: 2});
                return false;
            }
            var award_time = $("#award_time").val();
              if(award_time==''){
                 layer.msg('必须选择领奖截止时间!', {icon: 2});
                return false;
            }
            
            if(btime>etime){
                layer.msg('活动开始时间不能大于活动结束时间!', {icon: 2});
                return false;
            }
            
            if(etime>award_time){
                layer.msg('活动结束时间不能大于活动领奖截止时间!', {icon: 2});
                return false;
            }
            
         
            //邀请主图
             var tt = $('#activity_url').val();
            if(tt == ''){
              layer.msg('邀请主图必须上传', {icon: 2});
                return false;
            }
             //被邀请人主图
             var tc = $('#thumb1').val();
            if(tc == ''){
              layer.msg('被邀请人主图必须上传', {icon: 2});
                return false;
            }
               //检查缩略图
             var ta = $('#thumb11').val();
            if(ta == ''){
              layer.msg('分享主图必须上传', {icon: 2});
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
      <?if(isset($isclose) && $isclose){ ?>
        parent.layer.msg('<?= $msg ?>');
        setTimeout(function (){
            parent.location.reload();
       }, 2000);
      <? } ?>

       
        //删除模块
        function delModule(){
          alert('shanchu');
        }
       
</script>
