  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>    
      <tr class="tb_header">
        <th style="width:25%">选择</th>
        <th style="width:25%">头像</th>
        <th style="width:25%">用户编号</th>
        <th style="width:25%">昵称</th>
      </tr>
    </thead>
      <tr>
         <form name="searchform" action="/live/teachersel" method="get" > 
            用户名:
                <input name="sname" type="text" value="<?if($sname){echo $sname;}?>" class="input-text">
                <input type="submit" name="search" class="button" value="搜索" />
         </form>
      </tr>
      <? foreach ($models as $model) { ?>
      <tr class="tb_list" name="user">
        <td>
            <input type="radio" class="selteacher" name="radiobutton" value="<?= $model['uid'] ?>" <?if( $model['uid']== $uid){echo "checked";}?>  data-sname="<?= $model['sname'] ?>" value="<?= $model['uid'] ?>" > 

        </td>
        <td><img src='<?= $model['avatars']?>' style='height:80px;width:80px;'/> </td>
        <td><?= $model['uid'] ?></td>
        <td><?= $model['sname'] ?></td>
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

<script>
    var index = parent.layer.getFrameIndex(window.name);
      //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
        //parent.location.reload(); 
        parent.layer.close(index);
    });
    $("#asave").click(function(){
        parent.$("#teacheruid").val($('input:radio:checked').val());
        parent.$(".userinfo").html($('input:radio:checked').data("sname"));
        parent.layer.close(index);
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      parent.layer.close(index);
    });
</script>