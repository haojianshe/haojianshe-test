
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="tb_header">
        <th style="width:5%">选择</th>
        <th style="width:5%">头像</th>
        <th style="width:8%">用户编号</th>
        <th style="width:8%">昵称</th>
      </tr>
    </thead>
    <? foreach ($userlist as $model) { ?>
      <tr class="tb_list">
      <td><input name="payteacher" <? if(in_array($model['uid'], $teacheruids)){echo "checked";}?> type="checkbox" value="<?= $model['uid'] ?>" /></td>
      <td><img src="<?= json_decode($model['avatar'])->img->n->url?>" style='height:50px;width:50px;'/> </td>
      <td><?= $model['uid'] ?></td>
      <td><?= $model['sname'] ?></td>
     
      </tr>  
     <?}?>
       <tr>
        <td colspan=8>
          <div style="margin-left:40%;margin-top:30px;">
            <span class="normalbtn_l"><a id="asave" class="button" href="javascript:;">确认</a></span>
            <span class="normalbtn_l"><a id="aclose" class="button" href="javascript:;">关闭</a></span>
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
      var uid_arr=new Array();
      $.each($('input:checkbox'),function(){
          if(this.checked){
            uid_arr.push($(this).val());

          }
      });
      parent.$("#teacheruids").val(uid_arr.join(","));
      parent.layer.close(index);
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      parent.layer.close(index);
    });
</script>