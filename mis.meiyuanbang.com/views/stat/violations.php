<?php

use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">

<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr >
        </tr>
        <tr>
            <th  colspan="5" style="color:red;font-size:16px;" >
            </th>
            <th colspan="7" >
                <div  id="searchid" >
                    <form name="searchform" action="/stat/violations" method="get" >
                        <table width="100%" cellspacing="0" class="search-form">
                            <tbody>
                                <tr>
                                    <td >
                                        <div class="explain-col" style="float:right" >
                                            <input type ="hidden" name='is_search' value='1' />
                                            开始时间:
                                            <input type="text" name="start_time" id="start_time" value="<?= $search['start_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "start_time",
                                                    trigger: "start_time",
                                                    dateFormat: "%Y-%m-%d %H:%M",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>         
                                            结束时间： <input type="text" name="end_time" id="end_time" value="<?= $search['end_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "end_time",
                                                    trigger: "end_time",
                                                    dateFormat: "%Y-%m-%d %H:%M",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>  
                                            <input type="submit" name="search" class="button button-primary button-small" value="搜索" />

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </th>
        </tr>
        <tr class="tb_header">
            <th style="width:5%">用户编号</th>  
            <th style="width:10%">用户信息</th>
            <th style="width:5%">总数</th>
            <th style="width:5%">已批改</th>
            <th style="width:5%">总评小于40秒</th>
            <th style="width:10%">无红笔</th>
            <th style="width:10%">无分评</th>
            <th style="width:10%">无范例图</th>
            <th style="width:10%">操作</th>
        </tr>
        </tr>
    </thead>
    <? if($search['is_search']==1){
    ?>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['uid'] ?></td>
        <td>  <img  style="width:50px;height:50px;border-radius:50px;" src="<?= json_decode($model['avatar'], true)['img']['n']['url'] ?>" />&nbsp;&nbsp;<?= $model['sname'] ?></td>
        <td><?= $model['count'] ?></td>
        <td><?= $model['correctcount'] ?></td>
        <td><?= $model['lessFortyCount'] ?></td> 
        <td><?= $model['notRedPenCount'] ?></td>  
        <td><?= $model['netCommentsCount'] ?></td>  
        <td><?= $model['netPicCount'] ?></td>   
        <td> <a style="cursor:pointer;" name='aedit' prizesid='<?= $model['uid'] ?>'>查看</a>&nbsp;&nbsp;</td>  
    </tr>          
    <?}?>
    <? }?>
</table>
<div id="_tips"></div>

<script>
//新建按钮绑定事件
    $('a[name=aedit]').on('click', function () {
        addedit($(this).attr('prizesid'));
    });
    //查看单个老师统计详情
    function addedit(id) {
        var content = '/stat/show_list';
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var title = '详情列表';
        content = content + '?uid=' + id + '&start_time=' + start_time + '&end_time=' + end_time;
        // title = '添加奖品';
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '80%'],
            content: content
        });
    }
</script>