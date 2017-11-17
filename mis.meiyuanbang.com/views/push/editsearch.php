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
   <?php $form = ActiveForm::begin(['id' => 'wapform']); ?>    
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
<tr class="device_token"  style="display:none;"><td>用户设备号</td><td><input class="inputclass1" name="MisXingePushService[device_token]"  style='width:200px' type='text' value="<?= $model->device_token?>"/></td></tr>
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
      <td width="80">一级分类</td>
      <td>
       <select name="MisXingePushService[f_catalog]" id="f_catalog">
         <option value="" selected="">一级分类</option>
         <? foreach ($catalog['imgmgr_level_1'] as $key => $value) {?>            
         <option value="<?=$value?>" key="<?=$key?>">
          <?=$value?>
        </option>
        <?}?>
      </select>
      <select name="MisXingePushService[s_catalog]" id="s_catalog">
        <option value="" selected="">二级分类</option>
      </select>
    </td>
  </tr>
  <tr>
    <td width="80">标签</td>
    <td id='tags'>
      </td>
      <input type='hidden' id='tags_value' class="inputclass1" id='tags_hidden' name="MisXingePushService[tags]"  value="" />
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
  $(function () {        
      $("#f_catalog").click(function() {
       var key=$("#f_catalog  option:selected").attr("key");
       var catalog_json=<?= json_encode($catalog)?>;              
       var content='';
       var s_catalogs=catalog_json.imgmgr_level_2[key];
       for(var item in s_catalogs) {
              if(s_catalog==s_catalogs){
                content+="<option selected value="+s_catalogs[item]+">"+s_catalogs[item]+"</option>";
              }else{
                content+="<option value="+s_catalogs[item]+">"+s_catalogs[item]+"</option>";               
              }
          $("#s_catalog").html(content);
      }      
    });       
   
  $("#s_catalog").click(function() {
        $.ajax({
             type: "post",
             url: "/tweet/gettags",
             data: "s_catalog="+$("#s_catalog  option:selected").attr("value")+"&f_catalog="+$("#f_catalog  option:selected").attr("value"),
             dataType: "json",
             success: function(data){
                $('#tags').empty();   //清空tags里面的所有内容
                var html = ''; 
                if(data.errno==0){
                  for (value in data.data){
                      html +='<select  name="tags" >';
                      html +='<option  selected>请选择</option>';
                            //console.log(data.data[value]['tag']);
                        for (value1 in data.data[value]['tag']){
                            html +='<option value='+data.data[value]['tag'][value1]+'>'+data.data[value]['tag'][value1]+'</option>';
                        }
                            html +="</select>";
                      }
                  $('#tags').html(html);
                   $("#tags_value").val('');
                       $("[name='tags']").click(function() {
                        var tagsval='';
                        $("select[name='tags']").each(function(){     
                        if($(this).val()!='请选择'){
                             tagsval = tagsval + $(this).val()+",";
                        }
                         });
                        tagsval=tagsval.substring(0,tagsval.length-1);
                        $("#tags_value").val(tagsval);
                      });
                }else{
                }
            }
          });
      });
    });
      //保存按钮
  $("#asave").click(function () {
      $("form").submit();
        return false;
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
    	parent.layer.close(index);
    });
   <?if(isset($isclose) && $isclose){ ?>
     parent.layer.msg('<?= $msg ?>');
      setTimeout(function (){
      parent.location.reload();
   }, 1000);
    <? } ?>
  $("#wapform").Validform({
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
        