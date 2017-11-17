  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" src="/static/js/validform_v5.3.2_min.js"></script>
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">
  <script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
  <script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'form']); ?>    
   <table>
    <tbody> 
      
         <input type ="hidden" name='isedit' value='1' />
         <input type ="hidden" class="inputclass1" name="MisXingePushService[id]" style="width:300px" type="text" value="<?= $model->id ?>" />
         <?= $model->id ?>
       
    <tr><td style="width: 100px">标题</td><td style="width: 700px"><input class="inputclass1" name="MisXingePushService[title]"  style='width:200px' type='text' value="<?= $model->title?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/></td></tr>
    <tr><td>内容</td><td><textarea name="MisXingePushService[content]" style="width:70%;height:60px;" datatype="*0-100" errormsg="摘要最多200个字符！" sucmsg="&nbsp;"><?= $model->content?></textarea></td></tr>
    <tr><td>发送设备</td><td>
      <select id="push_device" name="MisXingePushService[push_device]" class="valid" value="1" >              
        <option value="1">android</option>
        <option value="2">ios</option>
        <option value="3">所有</option>
      </select>
    </td></tr>
  <tr><td>人群</td><td>
      <select id="push_person" name="MisXingePushService[push_person]" class="valid" value="1" >              
        <option value="1">群发</option>
        <option value="2">个人</option>
      </select>
    </td></tr>
  <tr>
    <td>直播编号</td>
    <td><input class="inputclass1" name="MisXingePushService[url_params_id]"  style='width:200px' type='text' value="" datatype="*1-300" nullmsg="直播id必须输入" sucmsg="&nbsp;"/>
    </td>
  </tr>
  <tr>
    <td>直播连接</td>
    <td><input class="inputclass1" name="MisXingePushService[url_params]"  style='width:200px' type='text' value="<?= $model->url_params?>" datatype="*1-300" nullmsg="直播id必须输入" sucmsg="&nbsp;"/>
    </td>
  </tr>

   
  <tr class="device_token"  style="display:none;">
    <td>用户设备号</td><td><input class="inputclass1" name="MisXingePushService[device_token]"  style='width:200px' type='text' value="<?= $model->device_token?>"/></td>
  </tr>
 <tr>
      <td style="width: 80px">发送日期</td>
      <td>
        <input type="text" name="MisXingePushService[send_time]" id="send_time" value="" class="inputclass1" readonly="readonly" style="width:140px">&nbsp;
        <script type="text/javascript">
          Calendar.setup({
            weekNumbers: true,
            inputField : "send_time",
            trigger    : "send_time",
            dateFormat: "%Y-%m-%d %H:%M:%S",
            showTime: true,
            minuteStep: 1,
            onSelect   : function() {this.hide();}
          });
          </script>         
      </td>
    </tr>
    <tr>
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
         var index = parent.layer.getFrameIndex(window.name);
            //保存按钮
          $("#asave").click(function () {
              $("form").submit();
                return false;
            });
            //关闭按钮,刷新父窗口
            $('#aclose').click(function(){
              //parent.location.reload(); 
              parent.layer.close(index);
            });
           <?if(isset($isclose) && $isclose){ ?>
             parent.layer.msg('<?= $msg ?>');
              setTimeout(function (){
              parent.location.reload();
           }, 1000);
            <? } ?>
            $("#form").Validform({
            tiptype:3,
          });

            $('#push_person').on('change',function(){
            if(this.value==2){
                $('.device_token').show();
            }else{
              $('.device_token').hide();
            }
          });
</script>
        