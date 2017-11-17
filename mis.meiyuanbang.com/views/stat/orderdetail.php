<?php
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<style type="text/css">
  body{
    margin: 8px;
  }
  .container{
    padding: 0px;
  }
   .search-form td {
          border: #cccccc 1px solid;
          background-color: #f3f3f3;
          height: 35px;
          line-height: 25px;
        }
</style>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
       <tr style="width: 800px;" class="tb_header">
        <th style="width:10%">订单类型</th>
        <th style="width:10%">缩略图</th>
        <th style="width:10%">购买内容标题</th>
        <th style="width:10%">单价</th>
        <th style="width:10%">备注</th>
      </tr>
    </thead>
    <? if($models){ foreach ($models as $model) {
      ?>
      <tr class="tb_list">
      <?
     // /订单类型 :1直播  2点播 3画室班型报名方式
        switch (intval($model['subjecttype'])) {
          case 1:
          ?>
            <td>直播</td>
            <td>图片</td>
            <td>标题</td>
          <?
          break;
          case 2:
          ?>
            <td>课程</td>
            <td><img style="max-height: 50px;" src="<?=$model['course_section']['video']['coverpic']?>"></td>
            <td><?=$model['course_section']['title']?></td>
          <?
          break;
          case 3:
            ?>
            <td>画室班型报名</td>
            <td>图片</td>
            <td>标题</td>
            <?
            break;
        }

     ?>

      <td><?= $model['fee'] ?></td>
      <td><?= $model['remark'] ?></td>
      </tr>  
     <?}}?>
      <tr class="operate">
        <td colspan="8">
          <div class="cuspages right">
          <?if($models){echo  MyLinkPager::widget(['pagination' => $pages,]);} ?>
          </div>      
        </td>
      </tr>
  </table>