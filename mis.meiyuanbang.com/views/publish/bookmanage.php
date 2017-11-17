<?php

use common\widgets\MyLinkPager;
use mis\service\UserService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="4" >
                共有<?= $pages->totalCount ?>条记录
            </th>
            <th colspan="6" style='text-align:right;'>
                <input type="button" id="btnnewBook" value="新建图书" class="button"/>
                <input type="button" onclick="funGoto()"  value="返回列表" class="button"/>
            </th>
        </tr>
        <tr class="tb_header">
            <th >图书编号</th>
            <th >封面</th>
            <th >书名</th>
            <th >出版社</th>
            <th >作者(编著)</th>
            <th >价格</th>
            <th >创建人</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['bookid'] ?></td>
        <td><?= "<img width='40px;' src='" . $model['thumb']. "' >  " ?></td>
        <td><?= $model['title'] ?></td>
        <td><?= $model['publishing_name'] ?></td>
        <td><?= $model['copyfrom'] ?></td>
        <td><?= $model['price'] ?></td>
        <td><?= $model['username'] ?></td>
        <td><?= date('Y-m-d H:i:s', $model['ctime']); ?></td>
        <td>
            <a  style="cursor:pointer"   href="<?= Yii::$app->params['msiteurl']?>publishing/book_detail?bookid=<?= $model['bookid'] ?>"  target="_blank" >预览</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a name='advert'  style="cursor:pointer" bookid='<?= $model['bookid'] ?>' >编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <!--<a name='book'  style="cursor:pointer" bookid='<?//= $model['bookid'] ?>' >删除</a>&nbsp;&nbsp;&nbsp;&nbsp;-->
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
<input type="hidden" value="<?php echo $uid;?>" id="uidid" />
<div id="_tips"></div>
<script>
//新建图书
$('#btnnewBook').on('click', function () {
     addBook(0,$("#uidid").val());
     return false;
    });
//编辑图书
$("a[name=advert]").click(function () {
    addBook($(this).attr("bookid"),$("#uidid").val());
    return false;
 });
//新建图书或编辑图书
    function addBook(bookid,uid) {
        var content = '/publish/editbook';
        var title = '添加图书';
        if (bookid > 0) {
            title = '编辑图书--编号:' + bookid;
        }
        content = content + '?bookid=' + bookid+"&uid="+uid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
    }
    
    
   //删除联考活动
    $("a[name=book]").click(function () {
        del($(this).attr("bookid"), 0);
        return false;
    });
    //删除活动
    function del(bookid,status) {
        var msg = '';
        if (status == 0) {
            msg = "是否删除？";
        }
        layer.confirm(msg, {
            btn: ['确定', '否'] //按钮
        }, function () {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/publish/delbook",
                data: "bookid=" + bookid, //要发送的数据                    
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
    
    //返回列表
    function funGoto(){
         window.location.href = '/publish';
        return false;
    }
//预览
    $("a[name=aedit]").click(function () {
        addedit($(this).attr("uid"));
        return false;
    });
//编辑或新增用户页面
    function addedit(uid) {
        var content = '/publish/show';
        var title = '添加用户';
        if (uid > 0) {
            content = content + '?uid=' + uid;
            title = '编辑用户--编号:' + uid;
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['50%', '70%'],
            content: content
        });
    }

</script>