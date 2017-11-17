<?php
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<div style="padding: 0px 20px 0px 20px;">
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>    
      <tr class="operate">
        <th colspan="3" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
      <tr class="tb_header">
        <th>选择</th>

        <th>视频编号</th>
        <th>一级分类</th>
        <th>二级分类</th>

        <th>封面图</th>
        <th width="300px;">描述</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list" name="video">
        <td>
        <?
        ?>
            <input type="radio" class="selvideo" name="radiobutton" value="<?= $model['courseid'] ?>" <?if(in_array($model['courseid'] , $courseids)){echo 'disabled="disabled"';}?>  data-courseid="<?= $model['courseid'] ?>" value="<?= $model['courseid'] ?>" > <?if(in_array($model['courseid'] , $courseids)){echo '已推荐';}?>
        </td>
        <td><?= $model['courseid'] ?></td>
        <td><?= $model['f_catalog'] ?></td>
        <td><?= $model['s_catalog'] ?></td>
        <td><img src='<?= $model['thumb_url']?>' style='height:40px;'/> </td>
        <td><?= $model['title'] ?></td>
      </tr> 
     <?}?>
      
    <!-- 分页 -->
    <tr class="operate">
        <td colspan="4">
          <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
          </div>      
        </td>
    </tr>
    <tr>
        <td colspan=4>
          <div style="margin-left:40%;margin-top:30px;">
            <span class="normalbtn_l"><a id="asave" href="#">确认</a></span>
            <span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>
          </div>
        </td>
    </tr> 
  </table>  
</div>
<script>
  var index = parent.layer.getFrameIndex(window.name);
  //关闭按钮,刷新父窗口
  $('#aclose').click(function(){
      //parent.location.reload(); 
      parent.layer.close(index);
  });
   
  $("#asave").click(function(){
      parent.$("#courseid").val($('input:radio:checked').val())
      parent.$(".courseinfo").html($('input:radio:checked').data("courseid"));
      parent.layer.close(index);
  });
  //关闭按钮,刷新父窗口
  $('#aclose').click(function(){
      //parent.location.reload(); 
      parent.layer.close(index);
  });
</script>