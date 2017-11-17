<?php
use yii\bootstrap\ActiveForm;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>

<div class="normaltable">
        <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
            <table style='width:100%;'>
                <tbody>
        <?php if (isset($model->prizesid)) { ?>
                    <input type ="hidden" name='isedit' value='1' />
                    <input type ="hidden" name="prizesid" value="<?= $model->prizesid ?>" />
        <?php } ?>
        <tr>
            <td style="width: 80px">奖品标题<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="title" style="width:70%" type="text" id="titleid" value="<?php echo isset($model->title) ? $model->title : "" ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">奖品类型

            </td>
            <td>
                <select name="type" id="selectid">
                    <option  value="0">请选择</option>
                    <option  value="1" <?php if (@$model->type == 1) {
                    echo 'selected=selected';
                } ?>>金币</option>
                                    <option  value="2" <?php if (@$model->type == 2) {
                    echo 'selected=selected';
                } ?>>虚拟物品</option>
                                    <option  value="3" <?php if (@$model->type == 3) {
                    echo 'selected=selected';
                } ?>>实物</option><option  value="4" <?php if (@$model->type == 4) {
                    echo 'selected=selected';
                } ?>>课程券</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>奖品图片<br/><span class='need'>134*134</span></td>
            <td>
                <input type ="hidden" id="thumb" name="thumb" value="<?php echo isset($model->img) ? $model->img : "" ?>" />      	
                <a name='athumb' id='athumb' thumbid='0' href='#'>
                    <img id='imgthumb' src="<? if(@$model->img){echo @$model->img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
                </a>
            </td>
        </tr>
        <tr class="activity_content" 
            <?php
            if (@$model->type == 3 || @$model->type == 0 ) {
                echo 'style="display: none"';
            }
            ?>
            id="contentid">
            <td id="numberid">
                <?php if (@$model->type == 4) {
                    echo '课程券id';
                }else if(@$model->type == 1 || @$model->type == 2){
                    echo '数值';
                }
                ?>
                
                </td>
            <td>
                <input  size="10"  type="text" name="content" id="contend" value="<?php echo @$model->content ?>" datatype="/^-?[1-9]\d*$/" nullmsg="不能为空" errormsg="必须为数字" sucmsg="&nbsp;"/>
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
$("#selectid").change(function () {
    var selectval = $(this).val();
    if (selectval == 1 || selectval == 2|| selectval == 4) {
        if(selectval==4){
            $("#numberid").text('课程券id');
        }else{
             $("#numberid").text('数值');
        }
        $("#contentid").show();
    } else {
        $("#contend").removeAttr("datatype");
        $("#contentid").hide();
    }
});
//父窗口句柄
var index = parent.layer.getFrameIndex(window.name);
//点击缩略图事件
$("a[name=athumb]").click(function () {
    var content = '/activity/thumbupload';
    var title = '编辑缩略图';
    content = content + '?url=' + encodeURI($('#thumb').val());
    layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area: ['600px', '400px'],
        content: content
    });
    return false;
});
//保存按钮
$("#asave").click(function () {
    var title = $('#titleid').val();
    var selectval = $('#selectid').val();
    if (title == '' && selectval == 0) {
        layer.msg('奖品标题或者奖品类型不能为空', {icon: 2});
        return false;
    }
    //检查缩略图
    t = $('#thumb').val();
    if (t == '') {
        layer.msg('缩略图必须上传', {icon: 2});
        return false;
    }
    var contend = $("#selectid").val();
  
    if (contend == 3) {
        $("#contend").removeAttr("datatype");
    }
    $("form").submit();
    return false;
});
//关闭按钮,刷新父窗口
$('#aclose').click(function () {
    parent.layer.close(index);
});
//window.location.href='/reward/index';
//parent.layer.close(index);
//保存成功后自动关闭
<?php if (isset($msg) && $msg <> '') { ?>
    <?php if (isset($isclose) && $isclose) { ?>
        layer.msg('<?php echo $msg ?>', {icon: 1});
        setTimeout(function () {
            parent.location.reload();
        }, 1000);
    <?php } else { ?>
        layer.msg('<?php echo $msg ?>', {icon: 2});
    <?php } ?>
<?php } ?>
//表单验证
$("#cmsform").Validform({
    tiptype: 3,
});
</script>