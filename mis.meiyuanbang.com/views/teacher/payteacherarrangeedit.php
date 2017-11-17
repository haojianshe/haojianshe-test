
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
            <?php if(isset($model->arrangeid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="CorrectPayteacherArrangeService[arrangeid]" value="<?= $model->arrangeid ?>" />
            <?php } ?>
             <tr>
                <td style="width: 80px">老师uid（英文,隔开）</td>
                <td>
                  <textarea id="teacheruids" name="CorrectPayteacherArrangeService[teacheruids]" style="width:98%;height:100px;"  ><?= $model->teacheruids ?></textarea>
                   <span class="normalbtn_l"><a id="selpayteacher"  href="javascript:;">选择</a></span>
                </td>
            </tr>
            <tr>
               <td style="width: 80px">起止日期<span class="need">*</span></td>
               <td>
                   <input type="text" name="CorrectPayteacherArrangeService[btime]" id="btime" value="<?if($model->etime){echo date('Y-m-d H:i',$model->btime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
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
                至
                <input type="text" name="CorrectPayteacherArrangeService[etime]" id="etime" value="<? if($model->etime){echo date('Y-m-d H:i',$model->etime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
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
    
    //父窗口句柄
    var index = parent.layer.getFrameIndex(window.name);
    //保存按钮
    $("#asave").click(function () {
        var btime=$("#btime").val();
        var etime=$("#etime").val();
        if(btime>=etime){
          alert("开始时间不能大于结束时间");
          return false;
        }
        $("form").submit();
        return false;
    });
     $("#selpayteacher").click(function () {

                var content = '/teacher/sel_pay_teacher';
                var title = '选择付费批改老师';
                content = content+'?teacheruids='+ $("#teacheruids").val();
                var search =layer.open({
                    type: 2,
                    title: title,
                    maxmin: true,
                    area : ['700px' , '600px'],
                    content: content
                  });
                layer.full(search);
    });

    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
        //parent.location.reload(); 
        parent.layer.close(index);
    });

    //保存成功后自动关闭
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
    
    //表单验证
    $("#cmsform").Validform({
        tiptype:3,
    }); 
</script>