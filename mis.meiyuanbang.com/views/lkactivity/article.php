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
                共有<?= count($models['data']) ?>条记录
            </th>

        </tr>
        <tr class="tb_header">
            <th >选择</th>
            <th >活动编号</th>
            <th >标题</th>
            <th >创建人</th>
            <th >发布时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <!-- 列表 -->
    <?php foreach ($models['data'] as $model) { ?>
        <tr class="tb_list">
            <td><input type="checkbox" accept="" id="check_<?php echo $model['newsid'] ?>" onclick="checkid(<?php echo $model['newsid'] ?>)"  name="newsCheck"
                <?php
                if ($models['lkids']) {
                    foreach ($models['lkids'] as $k => $v) {
                        if ($v['newsid'] == $model['newsid'] && $v['status'] == 1) {
                            echo 'checked=checked';
                        }
                    }
                }
                ?>
                       class="<?php echo $model['newsid']; ?>" value="<?php echo $model['newsid']; ?>" /></td>
            <td><?= $model['newsid'] ?></td>
            <td><?= $model['title'] ?></td>
            <td><?= $model['username'] ?></td>
            <!--<td><?//= date('Y-m-d H:i', $model['ctime']) ?></td>-->
            <td>
                <?php
                if ($models['zhiding']) {
                    foreach ($models['zhiding'] as $key => $val) {
                        if ($val['newsid'] == $model['newsid'] && !empty($val['ptime'])) {
                            echo date('Y-m-d H:i:s', $val['ptime']);
                        } else if ($val['newsid'] == $model['newsid'] && empty($val['zdtime'])) {
                            echo '';
                        }
                    }
                }
                ?>
            </td>
            <td>
                <?php
                if ($models['zhiding']) {
                    foreach ($models['zhiding'] as $key => $val) {
                        if ($val['newsid'] == $model['newsid'] && empty($val['zdtime']) && $val['status'] != 0) {
                            ?>
                            <a onclick="zhiding(<?php echo $model['newsid']; ?>, 1)"  style="cursor: pointer">置顶</a>
                            <?php
                        } else if ($val['newsid'] == $model['newsid'] && !empty($val['zdtime'])) {
                            ?>
                            <a style="color: red; cursor: pointer" onclick="zhiding(<?php echo $model['newsid'] ?>, 0)">已置顶</a>
                            <?php
                        }
                    }
                }
                ?>
            </td>
        </tr>
    <?php } ?>
    <!-- 分页 -->
    <tr class="operate">
        <td colspan="6">
            <div class="cuspages right">
                <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
            </div>      
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <div>
                <input type="hidden" value="<?php echo $models['lkid']; ?>" id="lkid" />                
                <input type="hidden" value="<?php echo $models['zp_type'] ?>" id="zp_type" />                
            </div>
        </td>
    </tr>
</table>

<script type="text/javascript">
    function checkid(id) {
        var ss = $('#check_' + id).is(':checked');
        if (ss == true) {
            var status = 1;
        } else {
            var status = 0;
        }
        var zp_type = $("#zp_type").val();
        var lkid = $("#lkid").val();
        var url = '/lkactivity/qainsert';

        var data = {
            zp_type: zp_type,
            lkid: lkid,
            status: status,
            newsid: id
        }
        $.post(url, data, function (m) {
            if (m == 2) {
                if (zp_type == 2) {
                    var str = '联考攻略';
                } else {
                    var str = '名师大讲堂';
                }
                layer.msg('您好:该记录已经被'+str+'选中,请你重新选择别的记录', {icon:2});
                $('#check_' + id).attr('disabled','disabled');
                return false;
            }
            if (m == 1) {
                 layer.msg('修改成功!', {icon:1});
                window.location.reload();
                return false;
            } else {
                layer.msg('修改失败!', {icon:2});
                window.location.reload();
                return false;
            }
        }, 'json');
    }

    function zhiding(newsid, status) {
        var zp_type = $("#zp_type").val();
        var lkid = $("#lkid").val();
        var url = '/lkactivity/zhiding';
        var data = {
            newsid: newsid,
            zp_type: zp_type,
            status: status,
            lkid: lkid
        }
        $.post(url, data, function (m) {
            if (m == 1) {
                layer.msg('置顶成功!', {icon:1});
                window.location.reload();
                return false;
            } else {
                layer.msg('置顶失败!', {icon:2});
                window.location.reload();
                return false;
            }
        }, 'json');
    }
    //保存按钮
    $("#asave").click(function () {
        var lkid = $("#lkid").val();
        var zp_type = $("#zp_type").val();
        var strid = getVal();
        var url = '/lkactivity/qainsert';
        var data = {
            lkid: lkid,
            zp_type: zp_type,
            strid: strid
        }
        $.post(url, data, function (m) {
            if (m == 1) {
                layer.msg('录入成功', {icon: 1});
                parent.layer.close(index);
            } else {
                layer.msg('录入失败', {icon: 2});
                parent.layer.close(index);
            }
        }, 'json');
    });
    //添加文字模板
    var index = parent.layer.getFrameIndex(window.name);
    //关闭按钮,刷新父窗口
    $('#aclose').click(function () {
        parent.layer.close(index);
    });
    function getVal() {
        var chk_value = [];
        $('input[name="newsCheck"]:checked').each(function () {
            chk_value.push($(this).attr('class'));
        });
        return  chk_value.length == 0 ? '你还没有选择任何内容！' : chk_value
    }
</script>

