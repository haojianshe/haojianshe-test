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
   <table style='width:100%;'>
    <tbody>
    <tr>
      <td>类型<span class='need'>*</span></td>
      <td>
         <select name='CommentService[subjecttype]'>
                    <!-- //0帖子 1专家动态评论 2小组讨论 3精讲 4考点 5活动 -->
                    <option  >请选择</option>
                    <option value='0' <?if($model->subjecttype=='0'){?>  selected <?}?> >帖子</option>
                    <option value='1' <?if($model->subjecttype=='1'){?>  selected <?}?> >专家动态评论</option>
                    <option value='2' <?if($model->subjecttype=='2'){?>  selected <?}?>>小组讨论</option>
                    <option value='3' <?if($model->subjecttype=='3'){?>  selected <?}?>>文章</option>
                    <option value='4' <?if($model->subjecttype=='4'){?>  selected <?}?>>考点</option>
                    <option value='5' <?if($model->subjecttype=='5'){?>  selected <?}?>>活动</option>
                  <!--  评论主题的类型 0帖子 1专家动态评论 2小组讨论 3 文章 4考点 5活动 6 正能文章7、活动文章 8、活动问答 9、能力模型素材 10、直播11、课程 -->
                    <option value='6' <?if($model->subjecttype=='6'){?>  selected <?}?>>正能文章</option>
                    <option value='7' <?if($model->subjecttype=='7'){?>  selected <?}?>>活动文章</option>
                    <option value='8' <?if($model->subjecttype=='8'){?>  selected <?}?>>活动问答</option>
                    <option value='9' <?if($model->subjecttype=='9'){?>  selected <?}?>>能力模型素材</option>
                    <option value='10' <?if($model->subjecttype=='10'){?>  selected <?}?>>直播</option>
                    <option value='11' <?if($model->subjecttype=='11'){?>  selected <?}?>>课程</option>
            </select>
      </td>
    </tr>
  <tr>
      <td>内容id<span class='need'>*</span></td>
      <td>

      <input class="inputclass1" name="CommentService[subjectid]" style="" type="text" value="<?= $model->subjectid ?>" datatype="*1-30" nullmsg="请输入内容id" sucmsg="&nbsp;"/>
      </td>
  </tr>
        

  <tr>
    <td>内容<span class='need'>*</span></td>
    <td>
      <textarea name="CommentService[content]" style="width: 478px; height: 120px; margin: 0px;" value="<?= $model->content ?>" datatype="*1-200" nullmsg="请输入评论内容，最多200个字！" sucmsg="&nbsp;"><?= $model->content ?></textarea>
    </td>
  </tr>

  <tr>
       <td style="width: 20%">用户uid<span class='need'>*</span></td>
       <td>
           <input class="inputclass1" id="uid" name="CommentService[uid]" style="" type="text" value="<?= $model->uid ?>" datatype="*1-30" nullmsg="请输入用户id" sucmsg="&nbsp;"/>&nbsp;
           <input type="button" id="makeranduid" value="随机生成" size="1px" class="button">
           
       </td>
  </tr>
      
  <tr>
     <td style="width: 150px">评论时间<span class='need'>*</span></td>
       <td>
           <input  type="text" name="CommentService[ctime]" id="ctime" value="<?if($model->ctime){echo date('Y-m-d H:i',$model->ctime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
           <script type="text/javascript">
            Calendar.setup({
               weekNumbers: true,
               inputField : "ctime",
               trigger    : "ctime",
               dateFormat: "%Y-%m-%d %H:%M",
               showTime: true,
               minuteStep: 1,
               onSelect   : function() {this.hide();}
           });
        </script>
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
      <? if($msg<>''){ ?>
        <?if(isset($isclose) && $isclose){ ?>
          layer.msg('<?= $msg ?>', {icon: 1});
          setTimeout(function (){
            parent.location.reload();
           }, 1000);
        <? } else{ ?>
          layer.msg('<?= $msg ?>', {icon: 2});
        <? } ?>
      <? } ?>
      $("#makeranduid").click(function(){
        $("#uid").val(Math.floor(Math.random()*500+499));
      });
</script>
