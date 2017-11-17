<?php 
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="3" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="2" style='text-align:right;'>
          <input type="button" id="btnnew" value="新建作品" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th>编号</th>
        <th>标题</th>
        <th>排序</th>
        <th>作品图片</th>
        <th>操作</th>
      </tr>
    </thead>
    <?php
    #print_r($models);
    foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td ><?= $model['studioopusid'] ?></td>
        <td><?= $model['opus_title'] ?></td>
        <td><?= $model['listorder'] ?></td>
        <td><img style="width: 50px;" src="<?= $model['resource'] ?>" /></td>
        <td width="400px">
            <a name='opusname' studioopusid='<?= $model['studioopusid'] ?>' uid='<?= $model['uid'] ?>'   href='javascript:;' style="cursor: pointer">编辑</a>&nbsp;&nbsp;
            <a name='opusdel' studioopusid='<?= $model['studioopusid'] ?>' uid='<?= $model['uid'] ?>'  style="cursor: pointer">删除</a>&nbsp;&nbsp;
        </td>
      </tr>
    <?php }?>
    <tr class="operate">
      <td colspan="7">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>
      </td>
    </tr>
  </table>
<input type="hidden" value="<?=$uid?>" id="uid" />
<input type="hidden" value="<?=$studiomenuid?>" id="studiomenuid" />
<script>
    
        $("#btnnew").click(function () {
         addOpus(0,$('#studiomenuid').val(),$("#uid").val());
       });
       
       //编辑作品
        $("a[name=opusname]").click(function () {
         
         addOpus($(this).attr("studioopusid"),$('#studiomenuid').val(),$(this).attr("uid"));
       });
        function addOpus(studioopusid,studiomenuid,uid) {
        var content = '/studio/editopus';
        if(studioopusid>0){
            var  title = '作品编辑---编号：'+uid;
        }else{
             var  title = '作品添加';
        }
        content = content + '?uid=' + uid+"&studioopusid="+studioopusid+'&studiomenuid='+studiomenuid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
        }
        
        
        
     //删除作品
       $("a[name=opusdel]").click(function () {
           
         del_address($(this).attr("studioopusid"),$(this).attr("uid"));
       });
    
    function del_address(addrid,uid){
      var title;
      if(addrid>0){
        title="确定删除？";
      }
      layer.confirm(
        title, 
        {
          btn: ['是','否']
        },
        function(){
          $.ajax({
            type: "post",
            dataType: "json",
            url: "/studio/del_opus",
              data: "addrid=" + addrid+"&uid="+uid,//要发送的数据
              success: function (data) {
                if (data.errno == 0) {
                  window.location.reload();
                }
                else {
                  layer.msg(data.msg,{icon: 2});
                }
              },
              error: function (XMLHttpRequest, textStatus, errorThrown) {
                layer.msg("访问出错",{icon: 2});
              }
          });
        });
    }
</script>