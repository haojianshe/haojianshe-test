<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mis\lib\enumcommon\ActivityClickTypeEnum;
use common\service\dict\BookDictDataService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<!-- 时间选择框样式 -->
<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css"/>
<!-- 时间选择框js -->
<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
<!--添加乐视视频按钮-->
<script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/letv/levedio.js"></script> 

<div class="normaltable">
    <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
    <table style='width:100%;'>
        <tbody>
            <?php if (isset($model['newsid'])) { ?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="bookid" id="bookid" value="<?= $model['bookid'] ?>" />
        <?php } ?>
        <tr>
            <td style="width: 20%">书名<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="title" style="width:30%" type="text" value="<?= $model['title'] ?>" datatype="*1-100" nullmsg="请输入书名，最多100个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">出版方<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="publishing_name" style="width:25%" type="text" value="<?= $model['publishing_name'] ?>" datatype="*1-100" nullmsg="请输入出版方名称，最多100个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">作者</td>
            <td>
                <input class="inputclass1" style="width:10%" name="copyfrom" style="width:70%" type="text" value="<?= $model['copyfrom'] ?>"  sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">图书类型<span class='need'>*</span></td>
            <td>
                <?php
                echo BookDictDataService::createMenuList('f_catalog_id', BookDictDataService::getBookMainType(), $model['f_catalog_id'], 'f_catalog_id');
                ?>
                <?php
                if ($model['f_catalog_id']) {
                    foreach (BookDictDataService::getBookSubType() as $key => $val) {
                        if ($model['f_catalog_id'] == $key) {
                            echo BookDictDataService::createMenuList('s_catalog_id', $val, $model['s_catalog_id'], 's_catalog_id');
                        }
                    }
                } else {
                    $i = 1;
                    foreach (BookDictDataService::getBookSubType() as $k => $v) {
                        if ($i == 1)
                            echo BookDictDataService::createMenuList('s_catalog_id', $v, $model['s_catalog_id'], 's_catalog_id');
                        $i++;
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">关键词</td>
            <td>
                <input class="inputclass1" name="keywords" style="width:30%" type="text" value="<?= $model['keywords'] ?>"  sucmsg="&nbsp;"/>&nbsp;&nbsp;多关键词之间用“,”隔开
            </td>
        </tr>
        <tr>
            <td style="width: 20%">价格<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="price" style="width:10%" type="text" value="<?= $model['price'] ?>" datatype="/^[0-9]+([.]{1}[0-9]+){0,1}$/"  errormsg="必须为数字" sucmsg="&nbsp;"/>&nbsp;&nbsp;为空请填0
            </td>
        </tr>
        <tr>
            <td style="width: 20%">购买链接</td>
            <td>
                <input class="inputclass1" name="buy_url" style="width:40%" type="text" value="<?= $model['buy_url'] ?>"  sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td>封面<br/></td>
            <td>
                <input type ="hidden" id="thumb" name="thumb" value="<?php echo isset($model['img']) ? $model['img'] : "" ?>" />  
                <input type ="hidden" id="rids" name="rid" value="<?=$model['rid']?>" />  
                <a name='athumb' id='athumb' thumbid='0' href='#'>
                    <img id='imgthumb' src="<? if(@$model['img']){echo @$model['img'];}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
                </a>
                <!--<span class='need'>134*134</span>-->
            </td>
        </tr>
        <tr>
            <td style="width: 20%">书籍概述</td>
            <td>
                <input class="inputclass1" name="desc" style="width:40%" type="text" value="<?= $model['desc'] ?>"  sucmsg="&nbsp;"/>
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
               <input type="hidden" id="uidid" name="uidid" value="<?= $uid ?>"/>
        </tbody>
    </table> 
    <?php ActiveForm::end(); ?> 
</div>

<script>
    $("#f_catalog_id").change(function () {
        var f_catalog_id = $(this).val();
        var url = '/publish/select_menu';
        var data = {
            f_catalog_id : f_catalog_id
        }
     $.post(url,data,function(m){
          $("#s_catalog_id option").remove();
         $("#s_catalog_id").append(m);
     },'json');
    });
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
    var index = parent.layer.getFrameIndex(window.name);
    //保存按钮
    $("#asave").click(function () {
         var t = $('#thumb').val();
            if(t == ''){
              layer.msg('缩略图必须上传', {icon: 2});
                return false;
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
        var content = '/publish/thumbuploadbook';
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
</script>
