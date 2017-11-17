  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>    
      <tr class="tb_header">
        <th style="width:25%">课程编号</th>
        <th style="width:25%">标题</th>
        <th style="width:25%">封面</th>
        <th style="width:25%">选择</th>
      </tr>
    </thead>
      <tr>
         <form name="searchform" action="/course/course_list" method="get" > 
            标题:
                <input name="title" type="text" value="<?if($title){echo $title;}?>" class="input-text">
                <input name="courseid" type="hidden" value="<?php echo $courseid;?>" class="input-text">
                <input type="submit" name="search" class="button" value="搜索" />
         </form>
      </tr>
      <? foreach ($models as $model) { ?>
      <tr class="tb_list" name="user">
            <td><?= $model['courseid'] ?></td>
       
        <td><?= $model['title'] ?></td>
         <td><img src='<?= $model['thumb_url']?>' style='height:80px;width:80px;'/> </td>
       <td>
 <input type="radio" class="selteacher" name="radiobutton" value="<?= $model['courseid'] ?>" 
        data-sname="<?php echo $model['title'] ?>" value="<?php echo $model['courseid'] ?>" > 
        </td>
       
      </tr> 
      <?}?>
      <tr class="operate">
        <th colspan="3" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
       <!-- 分页 -->
     <tr class="operate">
        <td colspan="4">
        <div class="cuspages right">
        <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>      
        </td>
      </tr>
     <tr>
        <td colspan=8>
          <div style="margin-left:40%;margin-top:30px;">
            <span class="normalbtn_l"><a id="asave" class="button" href="#">确认</a></span>
            <span class="normalbtn_l"><a id="aclose" class="button" href="#">关闭</a></span>
          </div>
        </td>
      </tr> 
  </table>  
<input type="hidden" value="<?=$id?>" id="ids"/>

<script>
    var index = parent.layer.getFrameIndex(window.name);
      //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
        //parent.location.reload(); 
        parent.layer.close(index);
    });
    $("#asave").click(function(){
        parent.$("#courseid").val($('input:radio:checked').val());
        var ids = $("#ids").val();
        if(ids==1){
             parent.$("#courseidOne").val($('input:radio:checked').data("sname"));
             parent.$("#hidden1").val($('input:radio:checked').val());
        }else{
            parent.$("#courseidTwo").val($('input:radio:checked').data("sname"));
             parent.$("#hidden2").val($('input:radio:checked').val());
        }
       
       // parent.$("#titleid").val($('input:radio:checked').val());
        parent.layer.close(index);
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      parent.layer.close(index);
    });
</script>