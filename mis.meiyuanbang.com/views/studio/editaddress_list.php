<?php 
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="5" >
          共有<?= $pages->totalCount?>条记录
        </th>
        <th colspan="2" style='text-align:right;'>
          <input type="button" id="btnnew" value="新建地址" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th>编号</th>
        <th>标题</th>
        <th>内景图</th>
        <th>联系方式</th>
        <th>位置</th>
        <th>操作</th>
      </tr>
    </thead>
    <?php
   
    foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td ><?= $model['addrid'] ?></td>
        <td><?= $model['addr_title'] ?></td>
        <td><img style="width: 50px;" src="<?= $model['addr_img'] ?>" /></td>
        <td><?= $model['tel'] ?></td>
        <td><?= $model['addr_detail'] ?></td>
        <td width="400px">
            <a name='address' addrid='<?= $model['addrid'] ?>' uid='<?= $model['uid'] ?>' sname='<?= $model['sname'] ?>'  href='javascript:;' style="cursor: pointer">编辑</a>&nbsp;&nbsp;
            <a name='adel' addrid='<?= $model['addrid'] ?>' uid='<?= $model['uid'] ?>' status='<?= $model['status'] ?>'  style="cursor: pointer">删除</a>&nbsp;&nbsp;
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
<script>
    
        $("#btnnew").click(function () {
         address($("#uid").val(),0,0);
       });
   //地址管理
        $("a[name=address]").click(function () {
         address($(this).attr("uid"),1,$(this).attr("addrid"));
       });
        function address(uid,s,addrid) {
        var content = '/studio/editaddress';
        if(uid>0){
            var  title = '地址编辑---编号：'+uid;
        }else{
             var  title = '地址添加';
        }
        
        content = content + '?uid=' + uid+"&s="+s+"&addrid="+addrid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
        }
//删除 审核
       $("a[name=adel]").click(function () {
         del_address($(this).attr("addrid"));
       });

    
    function del_address(addrid){
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
            url: "/studio/del_address",
              data: "addrid=" + addrid,//要发送的数据
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