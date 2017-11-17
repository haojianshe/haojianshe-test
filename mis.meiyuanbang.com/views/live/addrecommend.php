<?php

use common\widgets\MyLinkPager;
use mis\service\UserService;
use common\service\dict\LiveDictService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="2" >
                共有<?= $pages->totalCount ?>条记录
            </th>
            <th colspan="9" style="text-align: right;">
                <input type="submit"  name="search" class="button" value="保存"  id="hrefid">
            </th>
        </tr>
    <style type="text/css">
    </style>
    <tr class="tb_header">
        <th style="width: 100px;">直播编号</th>
        <th style="width: 100px;">标题</th>
        <th style="width: 100px;">用户名</th>
        <th style="width: 100px;">分类</th>
        <th style="width: 100px;">创建时间</th>
        <th style="width: 100px;">选择</th>
    </tr>
</thead>
<!-- 列表 -->
<?php foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['liveid'] ?></td>
        <td><?= $model['live_title'] ?></td>
        <td><?php
    echo UserService::findOne(['uid' => $model['teacheruid']])->sname;
    ?></td>
        <td><?= LiveDictService::getCorrectMainTypeNameById($model['f_catalog_id']); ?>-<?php
        $catalogid = LiveDictService::getCorrectSubType($model['s_catalog_id']);
        foreach ($catalogid as $key => $val) {
            foreach ($val as $k => $v) {
                if ($model['s_catalog_id'] == $k) {
                    echo $v;
                }
            }
        }
    ?></td>
        <td><?= date("Y-m-d H:i:s", $model['start_time']); ?></td>
        <td>
            <input name="Fruit"  value=""  type="checkbox" accept="" id="check_<?php echo $model['liveid'] ?>" onclick="checkid(<?php echo $model['liveid'] ?>)"/>
        </td>
    </tr>
<?php } ?>

<!-- 分页 -->
<tr class="operate">
    <td colspan="9">
        <div class="cuspages right">
            <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>      
    </td>
</tr>
</table>

<!-- 页面操作逻辑  开始-->
<script type="text/javascript">
    	//父窗口句柄
     var index = parent.layer.getFrameIndex(window.name);
      $("#hrefid").click(function(){
          parent.location.reload();
          parent.layer.close(index);
      })
      function checkid(id) {
        var ss = $('#check_' + id).is(':checked');
       
        if (ss == true) {
            var status = 1;
        } else {
            var status = 0;
        }
        var url = '/live/reminsert';
        var data = {
            liveid: id,
            status :status
        }
        $.post(url, data, function (m) {
//            if (m == 1) {
//                 layer.msg('修改成功!', {icon:1});
//               
//                return false;
//            } else {
//                layer.msg('修改失败!', {icon:2});
//                window.location.reload();
//                return false;
//            }
        }, 'json');
    }
        
        
    function goback() {
        window.location.href = "/live/index";
    }

    //删除按钮绑定事件
    $("a[name=del]").click(function () {
        var liveid = $(this).attr("liveid");
        layer.confirm('删除后将不可恢复，确定删除吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/live/del",
                data: "liveid=" + liveid, //要发送的数据                    
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
<!-- 页面操作逻辑 结束-->