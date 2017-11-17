<?php
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>    
     <tr class="operate">
          <th colspan="2" >
            共有<?= $pages->totalCount ?>条记录
          </th>
          <th colspan="6" style="text-align: right;">
              <form name="searchform" action="/course/videosel" method="get" > 
                <span style="font-size:  15px;">视频描述:</span>
                    <input style="height: 25px;line-height: 25px;"   name="desc" value="<?= $desc ?>" />       
                    <select name="video_type" id="video_type">
                      <option value="" key="">通用</option>
                      <option value="1" key=""  <?if(intval($video_type)==1){echo 'selected';}?> >直播</option>
                      <option value="2" key="" <?if(intval($video_type)==2){echo 'selected';}?> >课程</option>  
                    </select> &nbsp;&nbsp;
                    <input type="submit" name="search" class="button" value="搜索" />
             </form>
          </th>
        </tr>
      <tr class="tb_header">
        <th style="width:25%">选择</th>
        <th style="width:25%">图片</th>
        <th style="width:25%">编号</th>
        <th style="width:25%">描述</th>
      </tr>
    </thead>
      <? foreach ($models as $model) { ?>
      <tr class="tb_list" name="user">
        <td>
          <input type="radio" class="selteacher" name="radiobutton" value="<?= $model['videoid'] ?>" <?if( $model['videoid']== $videoid){echo "checked";}?>  data-videoid="<?= $model['videoid'] ?>" value="<?= $model['videoid'] ?>" > 
        </td>
        <td><img src='<?= $model['coverpic']?>' style='height:80px;width:80px;'/> </td>
        <td><?= $model['videoid'] ?></td>
        <td><?= $model['desc'] ?></td>
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
            <span class="normalbtn_l"><a id="asave" class="button" href="javascript:return ;">确认</a></span>
            <span class="normalbtn_l"><a id="aclose" class="button" href="javascript:return ;">关闭</a></span>
          </div>
        </td>
      </tr> 
  </table>  
<script>
    var index = parent.layer.getFrameIndex(window.name);
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      parent.layer.close(index);
    });
    $("#asave").click(function(){
        parent.$("#videoid").val($('input:radio:checked').val());
        parent.$(".videoinfo").html($('input:radio:checked').data("videoid"));
        parent.layer.close(index);
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      parent.layer.close(index);
    });
</script>