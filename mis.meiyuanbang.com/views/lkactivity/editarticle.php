<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mis\lib\enumcommon\ActivityClickTypeEnum;
use common\service\DictdataService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.js?v=201605191725"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<!--添加自定义按钮 阿里视频 模板 标题...-->
<script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/edittool/edittool.js?d=20170602"></script> 
<!-- 时间选择框样式 -->
<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css"/>
<!-- 时间选择框js -->
<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
  <!--添加乐视视频按钮-->
<!--   <script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/letv/levedio.js"></script> 
 -->
<div class="normaltable">
    <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
    <table style='width:100%;'>
        <tbody>
            <?php if (isset($model['newsid'])) { ?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="newsid" id="newsid" value="<?= $model['newsid'] ?>" />
        <?php } ?>
        <tr>
            <td style="width: 80px">标题<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="title" style="width:30%" type="text" value="<?= $model['title'] ?>" datatype="*1-200" nullmsg="请输入活动标题，最多200个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td >关键词<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="keywords" style="width:30%" type="text" value="<?= $model['keywords'] ?>" datatype="*1-200" nullmsg="请输入键词，最多200个字！" sucmsg="&nbsp;"/>&nbsp;多关键词之间用“，”隔开
            </td>
        </tr>
        <tr>
            <td>归属<span class='need'>*</span></td>
            <td>
                <select style="height:30px;" id="activity_type" name="activity_type" class="valid" value="">
                    <option value="1" >联考活动</option>
                </select>
            </td>
        </tr>
        <tr>
            <td >来源</td>
            <td>
                <input class="inputclass1" name="copyfrom" style="width:30%" type="text" value="<?= $model['copyfrom'] ?>"  sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td >封面样式<span class='need'>*</span></td>
            <td>
                <!--1/2/3/4 无图样式/通栏样式/左图样式/三图样式-->
                <select style="height:30px;" id="cover_type" name="cover_type" class="valid" value="">
                    <option value="1" <?php if ($model['cover_type'] == 1) {
            echo "selected=seleced";
        } ?> >无图样式</option>
                    <option value="2" <?php if ($model['cover_type'] == 2) {
            echo "selected=seleced";
        } ?>>通栏样式</option>
                    <option value="3" <?php if ($model['cover_type'] == 3) {
            echo "selected=seleced";
        } ?>>左图样式</option>
                    <option value="4" <?php if ($model['cover_type'] == 4) {
            echo "selected=seleced";
        } ?>>三图样式</option>
                </select>
            </td>
        </tr>
        <script>
$(function () {
    $("#cover_type").change(function () {
        if ($(this).val() == 2 || $(this).val() == 3) {
            $("#one").show();
            $("#thumb1").hide();
            $("#athumb1").hide();
            $("#thumb2").hide();
            $("#athumb2").hide();
        } else if ($(this).val() == 1) {
            $("#one").hide();
        } else if ($(this).val() == 4) {
            $("#one").show();
            $("#thumb1").show();
            $("#athumb1").show();
            $("#thumb2").show();
            $("#athumb2").show();
        }
    })
})
        </script>
        <tr id="one" 
        <?php
        if ($model['newsid'] == '') {
            echo 'style="display: none"';
        } else {
            if ($model['cover_type'] == 1) {
                echo 'style="display: none"';
            }
        }
        ?>
            >
            <td>封面主图<span class='need'>*</span></td>
            <td>
                <input type ="hidden" id="thumb0" name="thumb[]" value="<?= isset($model['img'][0]['img']) ? $model['img'][0]['img'] : "" ?>" />
                <input type ="hidden" id="thumb1" name="thumb[]" value="<?= isset($model['img'][1]['img']) ? $model['img'][1]['img'] : "" ?>" />
                <input type ="hidden" id="thumb2" name="thumb[]" value="<?= isset($model['img'][2]['img']) ? $model['img'][2]['img'] : "" ?>" />        
                <a name='athumb' id='athumb0' thumbid='0' href='#'><img id='img0' src="<?php
                if (@$model['img'][0]['img']) {
                    echo isset($model['img'][0]['img']) ? $model['img'][0]['img'] : "";
                } else
                    echo '/ueditor/dialogs/image/images/image.png';
                ?>" style='padding-left:15px;height:100px;' /></a>        	

                <a name='athumb' 
                                                       <?php
                                                       if ($model['cover_type'] == 2 || $model['cover_type'] == 3) {
                                                           echo 'style="display: none"';
                                                       }
                                                       ?>
                   id='athumb1' thumbid='1' href='#'><img id='img1' src="<?php
                if (@$model['img'][1]['img']) {
                    echo isset($model['img'][1]['img']) ? $model['img'][1]['img'] : "";
                } else
                    echo '/ueditor/dialogs/image/images/image.png';
                ?>" style='padding-left:15px;height:100px;'/></a>
                <a name='athumb'
<?php
if ($model['cover_type'] == 2 || $model['cover_type'] == 3) {
    echo 'style="display: none"';
}
?>
                   id='athumb2' thumbid='2' href='#'><img id='img2' src="<?php
if (@$model['img'][2]['img']) {
    echo isset($model['img'][2]['img']) ? $model['img'][2]['img'] : "";
} else
    echo '/ueditor/dialogs/image/images/image.png';
?>" style='padding-left:15px;height:100px;'/></a>
 <span >3图时大小230*150   1图宽为690高度可以不限制</span>
            </td>
        </tr>
        <tr>
            <td >封面概述<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="desc" style="width:90%" type="text" value="<?= $model['desc'] ?>"  datatype="*1-200" nullmsg="请输入活动标题，最多200个字！" sucmsg="&nbsp;"/>
            </td>
        </tr> 
        <tr>
            <td >点赞数<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="supportcount" style="width:5%" type="text" value="<?= $model['supportcount'] ?>" datatype="/^-?[0-9]\d*$/"  errormsg="必须为数字" sucmsg="&nbsp;"/>&nbsp;&nbsp;为空请填0
            </td>
        </tr>
        <tr>
            <td >浏览数<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="hits" style="width:5%" type="text" value="<?= $model['hits'] ?>" datatype="/^-?[0-9]\d*$/"  errormsg="必须为数字" sucmsg="&nbsp;"/>&nbsp;&nbsp;为空请填0
            </td>
        </tr>
        <tr>
            <td>内容</td>
            <td>
                <script name='content' id="editor" type="text/plain" style="width:770px;height:500px;"></script>
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
    //显示富文本框内容
     var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
    ue.ready(function () {
        ue.setContent('<?= $model['content'] ?>');
    });
    function del(i) {
        $("#title_" + i).remove();
        var hiddenName = $("input[name=hiddenids]").val();
        var a = hiddenName.replace(i, "");
        $("input[name=hiddenids]").val(a);
        return false;
    }
</script>

<script type="text/javascript">
    //状元分享会
    $("#addAq").click(function () {
        var hiddenName = $("input[name=hiddenids]").val();
        var lkid = $("#lkid_id").val();
        var content = '/lkactivity/qa';
        var title = '选择问答列表';
        content = content + '?lkid=' + lkid + '&hiddenids=' + hiddenName;
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
        content = content + '?lkid=' + lkid + '&hiddenids=' + hiddenName;
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
        var cover_type = $("#cover_type").val();
        if (cover_type > 1 && cover_type < 4) {
            if ($("#thumb0").val() == '') {
              layer.msg('请您选择图片', {icon: 2});
                return;
            }
        }
        if (cover_type == 4) {
            if ($("#thumb0").val() == '') {
                 layer.msg('请您选择图片', {icon: 2});
                return;
            }
            if ($("#thumb1").val() == '') {
                  layer.msg('请您选择图片', {icon: 2});
                return;
            }
            if ($("#thumb2").val() == '') {
                 layer.msg('请您选择图片', {icon: 2});
                return;
            }
        }
        $("form").submit();
        return false;
    });
    $("#cmsform").Validform({
        tiptype: 3,
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function () {
        parent.layer.close(index);
    });

    //保存成功后自动关闭
<?php if (isset($isclose) && $isclose) { ?>
        parent.layer.msg('<?php echo $msg ?>');
        setTimeout(function () {
            parent.location.reload();
        }, 1000);
<?php } ?>

    //点击缩略图事件
    $("a[name=athumb]").click(function () {
        var thumbid = $(this).attr("thumbid");
        var content = '/lecture/thumbupload';
        var title = '编辑缩略图';
        content = content + '?id=' + thumbid + '&url=' + encodeURI($('#thumb' + thumbid).val());
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

    //点击缩略图事件
    $("a[name=athumb1]").click(function () {
        var content = '/activity/cthumbupload';
        var title = '编辑缩略图';
        content = content + '?url=' + encodeURI($('#thumb1').val()) + "&imgclass=imgthumb1&valclass=thumb1";
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
</script>
