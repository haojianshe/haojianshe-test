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
            <?php if (isset($model->lkid)) { ?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="lkid_id" id="lkid_id" value="<?= $model->lkid ?>" />
        <?php } ?>
        <tr>
            <td style="width: 20%">标题<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="title" style="width:35%" type="text" value="<?= $model->title ?>" datatype="*1-200" nullmsg="请输入活动标题，最多200个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">省份<span class='need'>*</span></td>
            <td>
                <select style="height:30px;" id="keytype" name="provinceid" class="valid" value="">
                    <option value="0" >请选择</option>
                    <?php
                    foreach (DictdataService::getProvince() as $key => $val) {
                        ?>
                        <option value="<?php echo $val['provinceid']?>" <?php if($model->provinceid==$val['provinceid']){ echo 'selected=selected';}?> ><?php echo $val['provincename']?></option>
                        <?php
                         }
                      ?>
                </select>
            </td>
        </tr>
         <tr>
                <td style="width: 150px">发布时间<span class='need'>*</span></td>
                <td>
                    <input type="text" name="btime" id="btime" value="<?php echo isset($model->btime)?date('Y-m-d H:i:s',$model->btime):"" ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
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
            <td style="width: 20%">分享标题<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="share_title" style="width:50%" type="text" value="<?= $model->share_title ?>" datatype="*1-50" nullmsg="请输入分享标题，最多50个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">分享描述<span class='need'>*</span></td>
            <td>
                <textarea name="share_desc" style="width: 99%; height: 189px; margin-top: 0px; margin-bottom: 0px;" value="<?= $model->share_desc ?>"  datatype="*1-100" nullmsg="请输入分享描述，最多100个字！" sucmsg="&nbsp;"><?= $model->share_desc ?></textarea>
            </td>
        </tr>
        <tr>
            <td>分享图片<span class='need'>*</span></td>
            <td>
                <input type ="hidden" id="thumb1" name="share_img" value="<?= $model->share_img ?>" />       
                <a name='athumb1' id='athumb1' thumbid='0' href='#'>
                    <img id='imgthumb1' src="<? if($model->share_img){echo $model->share_img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
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
            var keytype = $("#keytype").val();
            if(keytype==0){
                 layer.msg('请您选择省份', {icon: 2});
                return false;
            }
            //检查缩略图
             var t = $('#thumb1').val();
            if(t == ''){
              layer.msg('缩略图必须上传', {icon: 2});
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
       }, 1000);
      <? } ?>

  //点击缩略图事件
      $("a[name=athumb]").click(function () {
              var content = '/activity/thumbupload';
              var title = '编辑缩略图1';
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
