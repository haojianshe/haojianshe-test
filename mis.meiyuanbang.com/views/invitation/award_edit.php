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
            <?php if (isset($model->award_id)) { ?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="award_id" id="prizes_id" value="<?= $model->award_id ?>" />
        <?php } ?> 
 
           <tr>
            <td style="width: 20%">领奖备注<span class='need'>*</span></td>
            <td>
<textarea name="comment" id="comment" style="width: 374px; height: 108px; margin: 0px;" value="<?= $model->comment ?>"  datatype="*1-300" nullmsg="领奖备注！" sucmsg="&nbsp;"><?php
if($model->status==1){
    echo $model->comment;
}
?></textarea>
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
       //保存按钮
        $("#asave").click(function () {
            //领奖备注
             var comment = $('#comment').val();
            if(comment == ''){
              layer.msg('领奖备注必须填写', {icon: 2});
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
        //删除模块
        function delModule(){
          alert('shanchu');
        }
</script>
