<?php 
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="4" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="2" style='text-align:right;'>
            <a name='btnnew'  style="cursor: pointer" class="button"  style="line-height: 10px;font-size: 10px">新建广告位</a>&nbsp;&nbsp;
        </th>
      </tr>
      <tr class="tb_header">
        <th>推荐位id</th>
        <th>图片</th>
        <th>参数</th>
        <th>排列字段</th>
        <th>创建时间</th>
        <th>操作</th>
      </tr>
    </thead>
    <?php foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td ><?= $model['posidid'] ?></td>
        <td><img style="width: 60px;" src="<?= $model['img'] ?>"/></td>
        <td><?= $model['url'] ?></td>
        <td><?= $model['listorder'] ?></td>
        <td><?= date("Y-m-d H:i:s",$model['ctime']); ?></td>
        <td width="350px">
         <a   name='advertedit' posidid='<?= $model['posidid'] ?>'  style="cursor: pointer">编辑</a>&nbsp;&nbsp;
          <a  name='advertdel' posidid='<?= $model['posidid'] ?>'   style="cursor: pointer">删除</a>&nbsp;&nbsp;
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
<input type="hidden" id="uid" value="<?php echo $uid?>" />
<script>
     //广告位添加
     $("a[name=btnnew]").click(function () {
        editadvert(0,$("#uid").val());
    });
    //广告位编辑
     $("a[name=advertedit]").click(function () {
        editadvert($(this).attr("posidid"),$("#uid").val());
    });
    //编辑广告位
     $("a[name=advertdel]").click(function () {
        deladvert($(this).attr("posidid"),$("#uid").val());
    });
    
    
    //编辑或新增广告位
    function editadvert(posidid,uid) {
        var content = '/studio/editadvert';
        title = '';
        if (uid > 0) {
            if(posidid>0){
                title = '编辑画室广告位:' + posidid;
            }else{
                title = '添加画室广告';
            }
        }
        content = content + '?posidid=' + posidid+"&uid="+uid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['70%', '80%'],
            content: content
        });
    }
   
    //删除广告位
    function deladvert(posidid,uid){
      var title;
      if(posidid>0){
        title="确实删除广告吗？";
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
            url: "/studio/deladvert",
              data: "posidid=" + posidid+"&uid="+uid,//要发送的数据
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