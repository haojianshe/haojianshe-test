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
            <?php if (isset($model->prizes_id)) { ?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="prizes_id" id="prizes_id" value="<?= $model->prizes_id ?>" />
        <?php } ?> 
      <tr>
            <td style="width: 20%">奖品名<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="title" style="width:50%" type="text" value="<?= $model->title ?>" datatype="*1-50" nullmsg="请输入奖品名，最多50个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
         <tr>
            <td>奖品主图<span class='need'>*</span></td>
            <td>
                <input type ="hidden" id="activity_url" name="img" value="<?= $model->img ?>" />       
                <a name='athumbUrl' id='athumbUrl' thumbid='0' rel="group" href="<?php echo $model->img ?>">
                    <img id='imgthumbUrl' src="<? if($model->img){echo $model->img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
                </a>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">领取权限<span class='need'>*</span></td>
            <td>
                <!--checked='checked'-->
                 <input type="radio"  name="prizes_type" value="1" <?php if($model['prizes_type']==1){ echo "checked='checked'";}?> class="radioclass">被邀请人
                 <input type="radio"  name="prizes_type" value="2" <?php if($model['prizes_type']==2){ echo "checked='checked'";}?> class="radioclass">邀请人
            </td>
        </tr>
   
          <tr>
            <td style="width: 20%">次数限制<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="number" style="width:50%" type="text" value="<?= $model->number ?>" nullmsg="请输入领奖次数！" datatype="/^-?[1-9]\d*$/" errormsg="必须为数字" sucmsg="&nbsp;"/>
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
<script type="text/javascript">
        //父窗口句柄
        var index = parent.layer.getFrameIndex(window.name);
                 //点击缩略图事件
                $("a[name=athumbUrl]").click(function () {
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
       //保存按钮
        $("#asave").click(function () {
            //邀请主图
             var tt = $('#activity_url').val();
            if(tt == ''){
              layer.msg('邀请主图必须上传', {icon: 2});
                return false;
            }
          if(!$('input[name=prizes_type]').is(':checked')) {
              layer.msg('请您填写领取权限', {icon: 2});
              return false;
           }
          var prizes_type =$('input:radio:checked').val();
          if(prizes_type==1){
             var number = $('input[name=number]').val();
             if(number !=1){
                layer.msg('被邀请人次数限制必须为1', {icon: 2});
                return false;
             }
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
        //删除模块
        function delModule(){
          alert('shanchu');
        }
</script>



