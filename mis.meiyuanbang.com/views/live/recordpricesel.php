<?php
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>    
    <tr>
      <?foreach ($iosprices as $key => $value) {?>
        <input  type="radio" name="recording_price_info" <? if($price==$value['price']){ echo "checked";}?> data-price="<?=$value['price']?>" value="<?=$value['price']?>" /><?=$value['price']?>元
      <?} ?>
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
        parent.$("#recording_ios_price").val($('input:radio:checked').val());
        parent.$("#recording_price_info").html($('input:radio:checked').data("price"));
        parent.layer.close(index);
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      parent.layer.close(index);
    });
</script>