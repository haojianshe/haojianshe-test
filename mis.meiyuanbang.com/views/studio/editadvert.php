<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
            <?php if (isset($model->posidid)) { ?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="posidid" id="posidid" value="<?= $model->posidid ?>" />
        <?php } ?>
         <tr>
            <td style="width: 20%">排序字段<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="listorder" style="width:10%" type="text" value="<?= $model->listorder ?>" datatype="/^-?[0-9]\d*$/"  errormsg="必须为数字" sucmsg="&nbsp;"/>&nbsp;&nbsp;为空请填0
            </td>
        </tr>
        <tr>
            <td style="width: 20%">参数</td>
            <td>
                <input class="inputclass1" name="url" style="width:100%" type="text" value="<?= $model->url ?>"  nullmsg="请输入地址参数！" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td>广告图片<span class='need'>*</span></td>
            <td>
                <input type ="hidden" id="thumb1" name="img" value="<?= $model->img ?>" />       
                <a name='athumb1' id='athumb1' thumbid='0' href='#'>
                    <img id='imgthumb1' src="<? if($model->img){echo $model->img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
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
    <input type="hidden" id="uidid" name="uidid" value="<?= $uid ?>"/>
    <?php ActiveForm::end(); ?> 
</div>


<script type="text/javascript">

      var index = parent.layer.getFrameIndex(window.name);
       //保存按钮
        $("#asave").click(function () {
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
