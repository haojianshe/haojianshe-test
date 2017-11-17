<?php

use common\widgets\MyLinkPager;
use mis\service\UserRepeatloginService;
use mis\service\CorrectService;
use mis\service\UserService;
use mis\service\UserPushService;
use common\service\CommonFuncService;
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
        <tr class="operate">
            <th colspan="1" >
                共有<?= @$models['pages']->totalCount ?>条记录 
            </th>
            <th colspan="9">
                <div id="searchid">
                    <form name="searchform" action="/stat/invite_list" method="get" >
                        <table width="100%" cellspacing="0" class="search-form">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="explain-col">
                                            <input type ="hidden" name='is_search' value='1' />
                                            邀请人：
                                            <select name="user_type">
                                                <option value="1" <?if(@$search['user_type']=='1'){?>  selected <?}?>  >用户电话</option>
                                                <option value="2" <?if(@$search['user_type']=='2'){?>  selected <?}?> >用户名</option>
                                            </select>&nbsp;
                                            <input type ="text" name='user' value='<?= @$search['user'] ?>' />&nbsp;&nbsp;
                                            生效日期:
                                            <input type="text" name="start_time" id="start_time" value="<?= @$search['start_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
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
                                            </script>至&nbsp;<input type="text" name="end_time" id="end_time" value="<?= @$search['end_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
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
            <th>编号</th>  
            <th>邀请人</th>
            <th>邀请人电话</th>
            <th>被邀请人</th>
            <th>设备号</th>
            <th>城市</th>
            <th>创建时间</th>
            <th>生效时间</th>
            <th>被邀请人APP使用时长</th>
            <th>被邀请人批改次数</th>
        </tr>
    </thead>

    <?php
    if (isset($models['models'])) {
        foreach ($models['models'] as $model) {
            ?>
            <tr class="tb_list">
                <td><?= $model['id'] ?></td>
                <td><?= $model['name'][0]['sname'] ?></td>
                <td><?= $model['name'][0]['umobile'] ?></td>
                <td><?= $model['umobile']; ?></td>
                <td><?= $model['xg_device_token'];?></td>
                <td><?php
                if(!empty($model['city'])){
                   echo $model['city']['province'].'&nbsp;&nbsp;'.$model['city']['city'];
                }
                ?></td>
                <td><?= date('Y-m-d H:i:s', $model['create_time']) ?></td>
                <td><?= date('Y-m-d H:i:s', $model['invite_time']) ?></td>
                <td><?= $model['hours']?$model['hours']:0; ?></td>
                <td><?= CorrectService::getUserCorrect($model['uid']) ?></td>   
            </tr>
            <?php
        }
    }
    ?>

    <script type="text/javascript">
        $('[name="sname_tweet"]').on('click', function () {
            var content = '/tweet?search=搜索&sname=' + this.getAttribute('data-sname');
            // alert(content);
            var title = '帖子';
            layer.open({
                type: 2,
                title: title,
                maxmin: true,
                shadeClose: false, //点击遮罩关闭层
                area: ['1300px', '700px'],
                content: content
            });
        });
    </script>
    <tr class="operate">
        <td colspan="10">&nbsp;
            <div class="cuspages right">
                <?php
                if (isset($models['pages'])) {
                    echo MyLinkPager::widget(['pagination' => $models['pages'],]);
                }
                ?>
            </div>      
        </td>
    </tr>
</table>
<div id="_tips"></div>

