<?php

use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'> 
<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate" >
            <th colspan="2" >
                共有<?= $pages->totalCount ?>条记录
            </th>
            <th colspan="9">
                <div id="searchid">
                    <?php
                    #print_r($models);
                    ?>
                    <form name="searchform" action="/reward/userlist" method="get"  onsubmit="return fun()">
                        <input type="hidden" name="activityid" id="activityid" value="<?php echo isset($models['activityid']) ? $models['activityid'] : "" ?>" >
                        <table width="100%" cellspacing="0" class="search-form">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="explain-col">
                                            <select name='type'>
                                                <!-- //0帖子 1专家动态评论 2小组讨论 3精讲 4考点 5活动 -->
                                                <option  value='0'>请选择</option>
                                                <option value='1' <?if($models['type']==1){?>  selected <?}?> >金币</option>
                                                <option value='2' <?if($models['type']==2){?>  selected <?}?> >虚拟物品</option>
                                                <option value='3' <?if($models['type']==3){?>  selected <?}?>>实物</option>
                                            </select>
                                            开始时间:
                                            <input type="text" name="start_time" id="start_time" value="<?php echo isset($models['start_time']) ? $models['start_time'] : "" ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "start_time",
                                                    trigger: "start_time",
                                                    dateFormat: "%Y-%m-%d 00:00:00",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>         
                                            结束时间： <input type="text" name="end_time" id="end_time" value="<?php echo isset($models['end_time']) ? $models['end_time'] : "" ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "end_time",
                                                    trigger: "end_time",
                                                    dateFormat: "%Y-%m-%d 00:00:00",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script> 
                                            <input type="submit" name="search" class="button" value="搜索" />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </th>
        </tr>
    <style type="text/css">
        .content_list td {
            max-width: 400px;
            height: auto;
        }
        .button {
            background: #4d90fe none repeat scroll 0 0;
            border: 1px solid #3079ed;
            border-radius: 3px;
            color: #fff;
            cursor: pointer;
            font: bold 12px/19px arial,sans-serif;
            height: 27px;
            margin: 0;
            overflow: visible;
            padding: 0.15em 1em;
            text-transform: uppercase;
            width: auto;
        }
    </style>
    <tr class="tb_header">
        <th >用户ID</th>
        <th >头像</th>
        <th >昵称</th>
        <th >姓名</th>
        <th >电话</th>
        <th >地址</th>
        <th >中奖时间</th>
        <th >奖品名称</th>
        <th >奖品图片</th>
    </tr>
</thead>

<?php
if (!empty($models['data'])) {
    foreach ($models['data'] as $model) {
        ?>
        <tr class="tb_list">
            <td style=" width: 5%"><?php echo isset($model['uid']) ? $model['uid'] : ""; ?></td>
            <td style=" width: 10%">
                <img style="width:40px;height:40px;padding:3px;" src="<?php echo isset($model['avatar']) ? $model['avatar'] : ""; ?>">
            </td>
            <td style=" width: 5%"><?php echo isset($model['sname']) ? $model['sname'] : ""; ?></td>

            <td style=" width: 5%"><?php echo isset($model['name']) ? $model['name'] : ""; ?></td>
            <td style=" width: 15%"><?php echo isset($model['mobile']) ? $model['mobile'] : ""; ?></td>
            <td style=" width: 15%"><?php echo isset($model['address']) ? $model['address'] : ""; ?></td>
            <td style=" width: 10%"><?php
                if (@$model['ctime']) {
                    echo date('Y-m-d H:i', @$model['ctime']);
                }
                ?></td>
            <td style=" width: 10%"><?php echo isset($model['dptitle']) ? $model['dptitle'] : ""; ?> </td>
            <td style=" width: 10%">
                <img style="width:40px;height:40px;padding:3px;" src="<?php echo isset($model['img']) ? $model['img'] : ""; ?>">
            </td>
        </tr>
        <?php
    }
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
</form>
<div id="_tips"></div>
<script>
    function fun() {
        var result = true;
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        if (start_time != '' || end_time != '') {
            if (start_time > end_time) {
                layer.msg('开始时间不能大于结束时间，请你重新选择结束时间!', {icon: 2});
                $("#end_time").val('');
                $("#end_time").focus();
                result = false;
            }
            if (start_time == end_time) {
                layer.msg('开始时间不能等于于结束时间，请你重新选择!', {icon: 2});
                $("#end_time").val('');
                $("#end_time").focus();
                result = false;
            }
        }
        if (start_time == '' && end_time != '') {
            layer.msg('开始时间不能为空，请你选择开始时间!', {icon: 2});
            $("#start_time").val('');
            $("#start_time").focus();
            result = false;
        }
        return result;
    }
</script>