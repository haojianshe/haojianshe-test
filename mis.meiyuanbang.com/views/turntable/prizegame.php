<?php

use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr >
            <th colspan="4" >
                <div class="button-group" >
                    <a href="/turntable/prizegame" class="button button-primary  button-small ">抽奖管理</a>
                    <a href="/turntable/index" class="button  button-small">奖品列表</a>
                </div>
            </th>
        </tr>
        <tr class="operate">
            <th colspan="2" >
                共有<?php echo $pages->totalCount ?>条记录
            </th>
            <th colspan="2" style='text-align:right;'>
                <input type="button" id="btnnew" value="新建活动" class="button button-primary  button-small"/>
            </th>
        </tr>
        <tr class="tb_header">
            <th >活动编号</th>
            <th >标题</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <?php foreach ($models as $model) { ?>
        <tr class="tb_list">
            <td><?php echo $model['gameid']; ?></td>
            <td id="title_<?php echo $model['gameid'] ?>"><?php echo $model['title']; ?></td>
            <td><?php
                if ($model['ctime']) {
                    echo date('Y-m-d H:i', $model['ctime']);
                }
                ?> </td>
            <td>
                <a name='aedit' newsid='<?= $model['gameid'] ?>' href='#'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a name='adel' newsid='<?= $model['gameid'] ?>' href='#'>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a name='prize' newsid='<?= $model['gameid'] ?>' href='#'  >中奖用户</a>&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <?php
    }
    ?>
    <tr class="operate">
        <td colspan="6">
            <div class="cuspages right">
                <?= MyLinkPager::widget(['pagination' => $pages]); ?>
            </div>      
        </td>
    </tr>
</table>
<div id="_tips"></div>
<script>
    //中奖用户列表
     $("a[name=prize]").click(function () {
        prize($(this).attr("newsid"));
        return false;
    });
    
    
//新建按钮绑定事件
    $('#btnnew').on('click', function () {
        addedit(0, 1);
    });

//新建活动
    $('#reward').click(function () {
        setHred();
    });
//
    $("a[name=show]").click(function () {
        show($(this).attr("newsid"));
        return false;
    });


//编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
        addedit($(this).attr("newsid"), 1);
        return false;
    });

    //预览
    $("a[name=preview]").click(function () {
        addedit($(this).attr("newsid"), 2);
        return false;
    });

    function setHred() {
        window.location.href = '/turntable/index';
    }
    //预览弹出
    function show(newsid) {
        var content = '/turntable/prizeshow';
        var title = "预览活动";
        if (newsid > 0) {
            content = content + '?gameid=' + newsid;
            title = '预览活动--编号:' + newsid;
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['50%' , '80%'],
            content: content
        });
    }
    
    //中奖用户
    function prize(activityid){
            var content = '/turntable/userlist?activityid='+activityid;
            var title = '中奖用户';
           layer.open({
               type: 2,
               title: title,
               maxmin: false,
               shadeClose: false, //点击遮罩关闭层
               area : ['80%' , '80%'],
               content: content
           });
          return false;
    }

//编辑或新增用户页面
    function addedit(newsid, i) {
        var content = '/turntable/prizeedit';
        var title = "<span style='color:red'>概率添加时:必须在1到10000之间,且递增添加!如(活动一:1~1000;活动二:1001~2000,依次填写不可有交集和间断)</span>";
        if (newsid > 0) {
            content = content + '?newsid=' + newsid + '&i=' + i;
            title = "<span style='color:red'>概率编辑时:必须在1到10000之间,且递增添加!如(活动一:1~1000;活动二:1001~2000,依次填写不可有交集和间断)</span>";
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['75%' , '80%'],
            content: content
        });
    }
//删除按钮绑定事件
    $("a[name=adel]").click(function () {
        var newsid = $(this).attr("newsid");
        layer.confirm('删除后将不可恢复，确定删除吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/turntable/prizedel",
                data: "newsid=" + newsid, //要发送的数据                    
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
            //取消
        });
        return false;
    });


</script>