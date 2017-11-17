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
            <th colspan="2" style='text-align:right;'>
                <input type="button" id="btnnew" value="新建用户" class="button"/>
            </th>
        </tr>
        <tr class="tb_header">
            <th >用户编号</th>
            <th >头像</th>
            <th >昵称</th>
            <th >图书数</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['uid'] ?></td>
        <td><?= "<img width='40px;' src='" . json_decode($model['avatar'])->img->n->url . "' >  " . $model['sname'] ?></td>
        <td><?= $model['sname'] ?></td>
        <td><?php echo (UserService::getBookCount($model['uid']));?></td>
        <td><?= date('Y-m-d H:i:s', $model['create_time']); ?></td>
        <td>
            <a name='aedit'   style="cursor:pointer" uid='<?= $model['uid'] ?>'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a name='advert'  style="cursor:pointer"  uid='<?= $model['uid'] ?>' >广告位</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a name='book'   style="cursor:pointer"uid='<?= $model['uid'] ?>' >图书管理</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a name='manage'   style="cursor:pointer"uid='<?= $model['uid'] ?>' >推荐管理</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
//新建按钮绑定事件
    $('#btnnew').on('click', function () {
        addedit(0);
    });

//编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
        addedit($(this).attr("uid"));
        return false;
    });


//编辑或新增用户页面
    function addedit(uid) {
      
        var content = '/publish/edit';
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
   
    //添加广告位置
    $("a[name=advert]").click(function () {
        addadvert($(this).attr("uid"));
        return false;
    });
    function addadvert(uid) {
        var content = '/publish/advert';
        var title = '广告位管理';
        content = content + '?uid=' + uid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['90%', '90%'],
            content: content
        });
    }
     //添加广告位置
    $("a[name=book]").click(function () {
        window.location.href = '/publish/bookmanage?uid='+$(this).attr("uid");
        return false;
    });
   
   //推荐管理
    $("a[name=manage]").click(function () {
        window.location.href = '/publish/recommended?uid='+$(this).attr("uid");
        return false;
    });
</script>