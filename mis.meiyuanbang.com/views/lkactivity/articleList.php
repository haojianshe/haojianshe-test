<?php

use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>

<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- 图片浏览 引入结束-->

<table cellspacing="0" cellpadding="0" class="content_list">
    <!--标题  -->
    <thead>
        <tr class="operate">
            <th colspan="7" >
                共有<?= count($models) ?>条记录
            </th>
            <th colspan="2" style='text-align:right;'>
                <input type="button" onclick="addedit(0);" id="btnnew" value="新建文章" class="button"/>
            </th>
        </tr>
        <tr class="tb_header">
            <th >文章编号</th>
            <th >标题</th>
            <th >关键词</th>
            <th >活动归属</th>
            <th >浏览数</th>
            <th >点赞数</th>
            <th >发布人</th>
            <th >发布时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <!-- 列表 -->
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['newsid'] ?></td>
        <td><?= $model['title'] ?></td>
        <td><?= $model['keywords'] ?></td>
        <td><?= isset($model['activity_type'])?'联考活动':"" ?></td>
        <td><?= $model['hits'] ?></td>
        <td><?= $model['supportcount'] ?></td>
        <td><?= $model['username'] ?></td>
        <td><?= date('Y-m-d H:i', $model['ctime']) ?></td>
        <td>
            <a href='<?= Yii::$app->params['msiteurl'] . 'activity/article_detail?newsid=' . $model['newsid'] ?>' target='_blank'>预览</a>
            <a name='aedit' newsid="<?= $model['newsid'] ?>" href='#'>编辑</a>
            <a name='adel' newsid="<?= $model['newsid'] ?>" href='#'>删除</a>
            <a name='comments' newsid="<?= $model['newsid'] ?>"  href="#">评论</a>
        </td>
    </tr>
    <?}?>
    <!-- 分页 -->
    <tr class="operate">
        <td colspan="6">
            <div class="cuspages right">
                <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
            </div>      
        </td>
    </tr>
</table>

<!-- 页面操作逻辑  开始-->
<script type="text/javascript">
   //编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
        addedit($(this).attr("newsid"));
        return false;
    });
   //编辑或新增联考活动文章
    function addedit(newsid) {
        var content = '/lkactivity/editarticle';
        var title = '添加文章';
        if (newsid > 0) {
            content = content + '?newsid=' + newsid;
            title = '编辑联考活动--编号:' + newsid;
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
    }
    //删除联考活动文章
    $("a[name=adel]").click(function () {
        del($(this).attr("newsid"), 1);
        return false;
    });
   //删除文章
    function del(newsid, status) {
        var msg = '';
        if (status == 1) {
            msg = "是否删除？";
        } 
        layer.confirm(msg, {
            btn: ['确定', '否'] //按钮
        }, function () {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/lkactivity/delarticle",
                data: "newsid=" + newsid + "&status=" + status, //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    }else if(data.errno==2) {
                        layer.msg(data.msg, {icon: 2});
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
    //评论
    $("a[name=comments]").click(function () {
        comments($(this).attr("newsid"));
        return false;
    });
   //获取评论列表
    function comments(newsid) {
        var content = '/lkactivity/comments?newsid=' + newsid;
        var title = '评论详情';
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, 
            area: ['80%', '80%'],
            content: content
        });
        return false;
    }
</script>
<!-- 页面操作逻辑 结束-->

<!-- 图片浏览 开始-->
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $("a#example1").fancybox({
            type: 'image',
            afterLoad: function () {
                this.title = '图片数：' + (this.index + 1) + '/' + this.group.length + (this.title ? ' - ' + this.title : '');
            },
            loop: false,
            padding: 2,
            helpers: {
                title: {
                    type: 'inside'
                }
            }
        });
    });
</script>
<!-- 图片浏览 结束 -->

