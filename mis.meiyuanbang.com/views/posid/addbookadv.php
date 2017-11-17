<?php

use common\widgets\MyLinkPager;

use common\service\dict\BookDictDataService;
use mis\service\RecommendBookService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th>
                共有<?= count($models) ?>条记录
            </th>
               <th>
                   <select id="mybbook" name="mybbook">
                        <option value="0">请选择出版社</option> 
                       <?php
                       foreach(RecommendBookService::getPublishList() as $key=>$val){
                       ?>
                        <option value="<?=$val['uid']?>" <?php if($uid==$val['uid']){ echo 'selected=selected';}?>><?=$val['sname']?></option> 
                        <?php
                        }
                        ?>
                        
                   </select>
            </th>
            <th style='text-align:right;'>
                <input type="button" id="locationid"  value="保存" class="button"/>
            </th>
        </tr>
        <tr class="tb_header">
            <th >图书编号</th>
            <th >书名</th>
            <th >选择</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['bookid'] ?></td>
        <td><?= $model['title'] ?></td>
        <td><input type="checkbox" name="checkbox" id="check_<?=$model['bookid']?>" value="<?= $model['bookid'] ?>"/></td>
    </tr>
    <?}?>
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
  </td>
</tr>
</tbody>
</table> 
<input type="hidden" id="typeid" value="<?= $type ?>"/>
<div id="_tips"></div>
<script>
    $("#mybbook").change(function(){
       window.location.href = '/posid/addbookadv?uid='+$(this).val()+"&type="+$("#typeid").val();
    });
    
    var index = parent.layer.getFrameIndex(window.name);
    $("#locationid").click(function(){
      setTimeout(function (){
            parent.location.reload();
       }, 10);
       window.top.location.replace = '/posid/recommended?uid='+$("#mybbook").val()+"&type="+$("#typeid").val();
    });
    //删除联考活动
    $("input[name=checkbox]").click(function () {
         var value = $(this).val();
        var ss = $('#check_' + value).is(':checked');
        if (ss == true) {
            var status = 1;
        } else {
            var status = 2;
        }
        var url = '/posid/addadvbook';
        var data = {
            value: value,
            status: status,
            typeid: $("#typeid").val()
        }
        $.post(url, data, function (m) {

        }, 'json')
    });
</script>