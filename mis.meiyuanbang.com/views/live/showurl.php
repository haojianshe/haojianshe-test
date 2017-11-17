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
 
    <style type="text/css">
      .table1{}
      .table1 th, .table td { 
      text-align: center;
      vertical-align: middle!important;
      }
    .table1 td{ 
    font-size:   14px; 
    BORDER:1px solid #BDD2EE;
    
    } 
    </style>
<table cellspacing="0" cellpadding="0" class="content_list table1" border="0"  width="100%" cellpadding="0" cellspacing="1" bgcolor="#fff">
    <thead>
  
</thead>
<!-- 列表 -->
<tr class="tb_list">
    <td>直播编号</td><td><?= $models['liveid'] ?></td>
    

<tr class="tb_list">
 <td>直播标题</td><td style="font-size: 15px;"><strong><?= $models['live_title'] ?></strong></td>
 </tr>
 <tr class="tb_list">
     <td>推流地址</td><td><?= $models['live_push_url'] ?></td>
      </tr>
       <tr class="tb_list">
     <td>m3u8直播观看地址</td><td><?= $models['live_display_url'] ?></td>
      </tr>
       <tr class="tb_list">
     <td>rtmp直播观看地址</td><td><?= $models['rtmp_url'] ?></td>
      </tr>
</table>