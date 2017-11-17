  <?php
  use common\widgets\MyLinkPager;
 
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>  
           
  
      <tr class="tb_header">
        <th style="width:25%">头像</th>
        <th style="width:25%">用户编号</th>
        <th style="width:25%">昵称</th>
         <th style="width:25%">选择</th>
      </tr>
      
    </thead>
     
      <tr>
         <form name="searchform" action="/studio/teacherlist" method="get" > 
             <input type="hidden" value="<?=$models['uid']?>" name="uid" />
             <select id="selectId" name="select">
                 <option value="1" <?php if($models['select']==1){ echo "selected=selected";}?> >昵称</option>
                 <option  value="2" <?php if($models['select']==2){ echo "selected=selected";}?> >电话</option>
             </select>&nbsp;&nbsp;
                <input name="sname" type="text" value="<?php echo $models['sname']?>" class="input-text">
                <input type="submit" name="search" class="button" value="搜索" />&nbsp;&nbsp;
                <input type="submit" name="over" class="button" value="已选中列表" />
         </form>
      </tr>
        <!--<input style="margin-left:550px;" type="submit"  name="search" class="button" value="保存"  id="hrefid">-->
      <? foreach ($models['models'] as $model) { ?>
      <tr class="tb_list" name="user">
        <td><img src='<?= $model['avatars']?>' style='height:80px;width:80px;'/> </td>
        <td><?= $model['uid'] ?></td>
        <td><?= $model['sname'] ?></td>
       <td>
            <input name="Fruit"  value=""  type="checkbox" accept="" <?php if($model['type_status']==1) echo 'checked=checked';?> id="check_<?php echo $model['uid'] ?>" onclick="checkid(<?php echo $model['uid'] ?>)"/>
          
        </td>
      </tr> 
      <?}?>
      <tr class="operate">
        <th colspan="3" >
          共有<?= $models['pages']->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
       <!-- 分页 -->
     <tr class="operate">
        <td colspan="4">
        <div class="cuspages right">
        <?= MyLinkPager::widget(['pagination' => $models['pages'],]); ?>
        </div>      
        </td>
      </tr>
      <input type="hidden" value="<?=$models['uid']?>" id="uuid" />
  </table> 
<script>
    var index = parent.layer.getFrameIndex(window.name);
     $("#hrefid").click(function(){
          parent.location.reload();
          parent.layer.close(index);
      });
      //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
        parent.layer.close(index);
    });
    
    
     function checkid(id) {
       // var uuid = $("#uuid").val();
        var ss = $('#check_' + id).is(':checked');
        if (ss == true) {
            var status = 1;
        } else {
            var status = 0;
        }
        var url = '/studio/teacherinsert';
        var data = {
            uid: id,
            status: status,
            uuid :'<?=$models['uid']?>'
        }
        $.get(url, data, function (m) {
            if(m==1){
                 layer.msg('已选择！',{icon: 1});
            }else{
                layer.msg('已取消！',{icon: 1});
            }
           
        }, 'json');
    }
    
   
</script>