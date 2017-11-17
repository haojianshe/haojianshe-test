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
            <th colspan="4" >
                共有<?= count($models) ?>条记录
            </th>
            <th colspan="2" style='text-align:right;'>
                <input type="button" onclick="addedit(0);" id="btnnew" value="新建活动" class="button"/>
                <a class="button" href="<?php echo Yii::$app->params['msiteurl'] ?>/mactivity/lk/province_list" target='_blank'>预览城市列表</a>
            </th>
        </tr>
        <tr class="tb_header">
            <th >活动编号</th>
            <th >标题</th>
            <th >创建人</th>
            <th >发布时间</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <!-- 列表 -->
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['lkid'] ?></td>
        <td><?= $model['title'] ?></td>
        <td><?= $model['mis_realname'] ?></td>
        <td><?= date('Y-m-d H:i', $model['btime']) ?></td>
        <td><?= date('Y-m-d H:i', $model['ctime']) ?></td>
        <td>
            <a href='<?= Yii::$app->params['msiteurl'] . 'mactivity/lk/index?lkid=' . $model['lkid'] ?>' target='_blank'>预览</a>
            <a name='aedit' lkid='<?= $model['lkid'] ?>' href='#'>编辑</a>&nbsp;&nbsp;
            <a name='adel' lkid='<?= $model['lkid'] ?>' href='#'>删除</a>
            <a name='simulation' lkid="<?= $model['lkid'] ?>" href="#">模拟考试</a>
            <a name='details' lkid="<?= $model['lkid'] ?>" href="#">报名详情</a>
            <?if($model['rank_status']==1){?>
            <a style="color:forestgreen;" name='fabang' lkid='<?= $model['lkid'] ?>' href='#'>发榜</a>
            <?}?>
            <?if($model['rank_status']==2){?>
            <a style="color:red;" name='wfabang' lkid='<?= $model['lkid'] ?>' href='#'>已发榜</a>
            <?} ?>
            <a name="qa" lkid="<?= $model['lkid'] ?>" href="#">状元分享会</a>
            <a name="mingshi" lkid="<?= $model['lkid'] ?>" href="#">名师大讲堂</a>
            <a name="liankao" lkid="<?= $model['lkid'] ?>" href="#">联考攻略</a>
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
        addedit($(this).attr("lkid"), 1);
        return false;
    });
    //编辑或新增联考活动
    function addedit(activityid) {
        var content = '/lkactivity/edit';
        var title = '添加联考活动';
        if (activityid > 0) {
            content = content + '?activityid=' + activityid;
            title = '编辑联考活动--编号:' + activityid;
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['60%', '80%'],
            content: content
        });
    }
    //编辑模拟考试
    $("a[name=simulation]").click(function () {
        simulation($(this).attr("lkid"));
        return false;
    });
    //模拟考试列表
    function simulation(lkid) {
        var content = '/lkactivity/simulation?lkid=' + lkid;
        var title = '模拟考列表';
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['70%', '80%'],
            content: content
        });
    }
    //删除联考活动
    $("a[name=adel]").click(function () {
        del($(this).attr("lkid"), 0);
        return false;
    });
    //发榜活动
    $("a[name=fabang]").click(function () {
        del($(this).attr("lkid"), 2);
        return false;
    });
      //发榜活动
    $("a[name=wfabang]").click(function () {
        del($(this).attr("lkid"), 1);
        return false;
    });
    //删除活动
    function del(activityid, status) {
        var msg = '';
        if (status == 0) {
            msg = "是否删除？";
        } else if (status == 2) {
            msg = "确定要发榜？";
        }else if (status ==1) {
            msg = "确定取消发榜？";
        }
        layer.confirm(msg, {
            btn: ['确定', '否'] //按钮
        }, function () {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/lkactivity/del",
                data: "activityid=" + activityid + "&status=" + status, //要发送的数据                    
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

    //报名详情
    $("a[name=details]").click(function () {
        details($(this).attr("lkid"));
        return false;
    });
    //报名详情
    function details(lkid) {
        var content = '/lkactivity/details?lkid=' + lkid;
        var title = '报名详情';
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '80% '],
            content: content
        });
        return false;
    }
    //状元分享会
    $("a[name=qa]").click(function () {
        qa($(this).attr("lkid"));
        return false;
    });
    //状元分享会
    function qa(i) {
        var content = '/lkactivity/qa?lkid=' + i;
        var title = '选择问答列表';
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['60%', '80%'],
            content: content
        });
    }

    //名师大讲堂
    $("a[name=mingshi]").click(function () {
        article($(this).attr("lkid"), 2);
        return false;
    });
    //联考攻略
    $("a[name=liankao]").click(function () {
        article($(this).attr("lkid"), 3);
        return false;
    });
    //名师大讲堂 || 联考攻略
    function article(i, v) {
        var content = '/lkactivity/contentlist?lkid=' + i + "&zp_type=" + v;
        var title = '选择文章列表';
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['60%', '80%'],
            content: content
        });
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

