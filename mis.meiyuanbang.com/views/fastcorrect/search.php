  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>    
      <tr class="tb_header">
       ·<th style="width:15%">选择</th>
        <th style="width:15%">头像</th>
        <th style="width:10%">用户编号</th>
        <th style="width:15%">昵称</th>
        <th style="width:15%">擅长科目</th>
        <th style="width:10%">获得金币数</th>
        <th style="width:10%">已批改数</th>
        <th style="width:10%">待批改数</th> 
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list" name="user">
      <td><input type="checkbox" class="selteacher" <?if(in_array( $model['uid'] , $uids)){echo "checked='checked'";}?> data-sname="<?= $model['sname'] ?>" value="<?= $model['uid'] ?>"  /></td>
      <td><img src='<?= $model['avatars']?>' style='height:80px;width:80px;'/> </td>
      <td><?= $model['uid'] ?></td>
      <td><?= $model['sname'] ?></td>
      <td><? if($model['issketch']==1) {echo '速写  '; }?><? if($model['isdrawing']==1) {echo '素描  '; }?><? if($model['iscolor']==1) {echo '色彩  '; }?><? if($model['isdesign']==1) {echo '设计  '; }?></td>
      <td><?= $model['gaincoin'] ?></td>
      <td><?= $model['correctnum'] ?></td>
      <td><?= $model['queuenum'] ?></td>      
      </tr> 
     <?}?>
      <tr class="operate">
        <th colspan="8" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
     <tr>
        <td colspan=8>
          <div style="margin-left:40%;margin-top:30px;">
            <span class="normalbtn_l"><a id="asave" href="#">确认</a></span>
            <span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>
          </div>
        </td>
      </tr> 
  </table>  

<script>
    //点击区域选择
    $("[name=user]").click(function(){
      /*if($(this).find("input:checkbox").is(':checked') ){
         $(this).find("input:checkbox").attr('checked',false);
      }else{
        $(this).find(":checkbox").attr('checked',true);
      }*/
    });
    $("#asave").click(function(){
        var index = parent.layer.getFrameIndex(window.name);
        var uids=new Array();
        var snames=new Array();
        $('input:checkbox').each(function() {
                if ($(this).is(':checked')) {
                    uids.push($(this).val());
                    snames.push($(this).data("sname"));
                }
        });
        parent.$("[name='FastCorrectService[correct_teacheruids]']").val(uids.join(","))
        parent.$(".userinfo").html(snames.join(","))
        parent.layer.close(index);
        console.log(snames.join(","));
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      //parent.location.reload(); 
      parent.layer.close(index);
    });
</script>