<?php
use common\widgets\MyLinkPager;
use common\service\dict\BookDictDataService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="4" >
                共有<?= count($models) ?>条记录 
            </th>
            <th colspan="6" style='text-align:right;'>
                <input type="button" id="btnnewBook" value="添加推荐" class="button"/>
            </th>
        </tr>
        <tr> 
            <th><?php
                echo BookDictDataService::createMenuList('mybbook', BookDictDataService::getBookMainType(), $type, 'mybbook', 0, 0, 0, 0, 0);
                ?>
            </th>
        </tr>
        <tr class="tb_header">
            <th >图书编号</th>
            <th >封面</th>
            <th >书名</th>
            <th >出版社</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['bookid'] ?></td>
        <td><?= "<img width='40px;' src='" . $model['thumb'] . "' >  " ?></td>
        <td><?= $model['title'] ?></td>
        <td><?= $model['publishing_name'] ?></td>
        <td><?= date('Y-m-d H:i:s', $model['ctime']); ?></td>
        <td>
            <a name='book'  style="cursor:pointer" recid='<?= $model['recid'] ?>' class="<?=$model['bookid']?>">删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
    <?}?>
    <tr class="operate">
        <td colspan="6">
            <div class="cuspages right">
                <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
            </div>      
        </td>
    </tr>
</table>
<div id="_tips"></div>
<script>
    //删除
    $("a[name=book]").click(function () {
        del($(this).attr("recid"),$(this).attr("class"),2);
        return false;
    });
    //删除活动
    function del(recid,bookid,status) {
        var msg = '';
        if (status == 2) {
            msg = "是否删除？";
        } 
        layer.confirm(msg, {
            btn: ['确定', '否'] //按钮
        }, function () {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/posid/delbook",
                data: "recid="+recid+ "&status=" + status+"&bookid="+bookid, //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("访问出错", {icon: 2});
                }
            });
        }, function () {

        });
    }
    //选择调转
    $("#mybbook").change(function () {
        var type = $(this).val();
        window.location.href = "/posid/mybbook?&type=" + type;
    });
      //排序字段 
    $("input[name=goto]").click(function () {
       window.location.href = '/publish/';
    });
    
  //添加美院帮推荐
    $("#btnnewBook").click(function () {
        addBookAdv();
    });
    function addBookAdv() {
        var content = '/posid/addbookadv';
        var title = '推荐位管理';
        content = content +"?type="+$("#mybbook").val();
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['90%', '90%'],
            content: content
        });
    }


</script>