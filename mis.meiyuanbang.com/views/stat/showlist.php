<?php
use common\widgets\MyLinkPager;
use mis\service\CorrectTalkService;
use mis\service\UserService;
use mis\service\ResourceService;
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
                            </tbody>
                        </table>
                    </form>
                </div>
            </th>
        </tr>
        <tr class="tb_header">
            <th style="width:12%">老师信息</th>  
            <th style="width:10%">用户信息</th>
            <th style="width:10%">批改id</th>
             <th style="width:10%">小于40秒数</th>
            <th style="width:5%">红笔图</th>
            <th style="width:5%">分评数</th>
            <th style="width:10%">范例图数</th>
            <th style="width:12%">批改时间</th>
        </tr>
        </tr>
    </thead>
    <?php
   
    ?>
    <?php foreach ($models[0]['data'] as $val) { ?>
    <tr class="tb_list">
        <td><img  style="width:50px;height:50px;border-radius:50px;" src="<?= json_decode($models[0]['avatar'], true)['img']['n']['url'] ?>" />&nbsp;&nbsp;<?= $models[0]['sname'] ?></td>
        <td><?php
         echo UserService::getInfoByUids($val['submituid'])[0]['sname'];
             ?></td>
        <td><?= $val['correctid'] ?></td>
       
           <td><?php
        if($val['majorcmt_id']){
           $majorcmt =  CorrectTalkService::getCorrectTalk($val['majorcmt_id']);
           if($majorcmt['duration']<40){
                echo "<span style='color:red'>".$majorcmt['duration']."</span>"; 
           }else{
               echo "<span>".$majorcmt['duration']."</span>"; 
           }
        }  else {
             echo "<span style='color:red'>无</span>";      
        }
        ?></td> 
        <td><?php
        if($val['correct_pic_rid']){
            $picData = ResourceService::getPicData($val['correct_pic_rid']);
        ?>
        <img  style="width:50px;height:50px;border-radius:50px;" src="<?= json_decode($picData->img, true)['n']['url']."@200h_2o" ?>" />
        <?php 
         }else{
             echo "<span style='color:red'>无</span>";    
         }
        ?>
        </td>
        <td>
        <?php
        if($val['pointcmt_ids']){
            echo count(explode(',', $val['pointcmt_ids']));
        }  else {
         echo "<span style='color:red'>无</span>";    
        }
        ?>
        </td>  
        <td><?php
        if($val['example_pics']){
            echo count(explode(',', $val['example_pics']));
        }  else {
         echo "<span style='color:red'>无</span>";      
        }
        ?></td>  
       <td><?= date("Y-m-d H:i:s",$val['correct_time']) ?></td>
    </tr>          
    <?php 
        }
        ?>
 
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