<?php
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="4" >
                共有<?= $pages->totalCount ?>条记录
            </th>
            <th colspan="2" style='text-align:right;'>
                <input type="button" id="btnnew" value="新建广告" class="button"/>
            </th>
        </tr>
        <tr class="tb_header">
            <th >推荐位id</th>
            <!--<th >类型</th>-->
            <th >图片</th>
            <th >参数</th>
            <th >排列字段</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['posidid'] ?></td>
        <!--<td>类型</td>-->
        <td><?= "<img width='40px;' src='" .$model['img'] . "' >  " . $model['sname'] ?></td>
        <td><?= $model['url'] ?></td>
        <td><?= $model['listorder'] ?></td>
        <td><?= date('Y-m-d H:i:s', $model['ctime']); ?></td>
        <td>
            <a name='aedit' posidid='<?= $model['posidid'] ?>' style="cursor: pointer;">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a name='del' posidid='<?= $model['posidid'] ?>' style="cursor: pointer;">删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
<input type="hidden" id="uidid" value="<?= $uid ?>"/>
<div id="_tips"></div>
<script>
//新建按钮绑定事件
    $('#btnnew').on('click', function () {
        addedit(0);
    });

//编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
        addedit($(this).attr("posidid"));
        return false;
    });

//编辑或新增用户页面
    function addedit(posidid) {
        var content = '/publish/editadvert';
        var uidid = $("#uidid").val();
        var title = '添加广告';
        if (posidid > 0) {
            title = '编辑用户--编号:' + posidid;
        }
        content = content + '?posidid=' + posidid+"&uid="+uidid;
        
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
    $("a[name=del]").click(function () {
        del($(this).attr("posidid"), 0);
        return false;
    });
    //删除活动
    function del(uid,status) {
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
                url: "/publish/deladvert",
                data: "uid=" + uid, //要发送的数据                    
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

</script>