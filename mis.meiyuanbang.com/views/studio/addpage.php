<?php 
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
           <th colspan="9" style="text-align: right;">
                <input type="submit"  name="search" class="button" value="保存"  id="hrefid">
            </th>
      </tr>
      <tr class="tb_header">
        <th>页面编号</th>
        <th>页面名称</th>
        <th>选择</th>
      </tr>
    </thead>
    <?php
    foreach ($model as $key=>$val) { ?>
      <tr class="tb_list">
         <td><?=$key?></td>
         <td><?=$val?></td>
         <td>
             <!--<input type="checkbox" name="checkname" id="<?//=$key?>"/>-->
          <input name="Fruit"  value=""  type="checkbox" accept="" id="check_<?php echo $key ?>" onclick="checkid(<?php echo $key ?>)"/>
         </td>
      </tr>
    <?php }?>
  </table>
<script>  
       	//父窗口句柄
     var index = parent.layer.getFrameIndex(window.name);
      $("#hrefid").click(function(){
          parent.location.reload();
          parent.layer.close(index);
      });
      function checkid(id) {
        var ss = $('#check_' + id).is(':checked');
        if (ss == true) {
            var status = 1;
        } else {
            var status = 0;
        }
        var url = '/studio/pageinsert';
        var data = {
            id: id,
            status: status,
            uid :'<?=$uid?>'
        }
        $.post(url, data, function (m) {
        }, 'json');
    }
    
</script>