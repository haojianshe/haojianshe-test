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
         <th colspan="1" style="text-align: right;" >
          <input type="button" id="add" value="添加标签" class="button">
        </th>
      </tr>
      <style type="text/css">
      </style>
      <tr class="tb_header">
        <th>标签编号</th>
        <th>名称</th>
        <th>操作</th>
      </tr>
    </thead>
    <!-- 列表 -->
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
      <td><?= $model['tagid'] ?></td>
      <td><?= $model['tag_name'] ?></td>
      <td style="width: 100px;">
        <a name='aedit' onclick='addedit(<?= $model['tagid'] ?>)' >编辑</a>
      </td>
    </tr>
    <?}?>

    <!-- 分页 -->
    <tr class="operate">
      <td colspan="3">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>      
      </td>
    </tr>
  </table>

  <!-- 页面操作逻辑  开始-->
  <script type="text/javascript">
  //编辑标签
  function addedit(id){
    var content = '/tag/tag_edit';
    var title = '编辑标签';
    if(id >0){
      content = content + '?id=' + id; 
      title = '编辑标签--编号:'+ id;
    }
    layer.open({
      type: 2,
      title: title,
      maxmin: false,
          shadeClose: false, //点击遮罩关闭层
          area : ['600px' , '300px'],
          content: content
        });
  }
  //批量增加标签
  $("#add").click(function(){
    var content = '/tag/tag_edit';
    content = content + '?taggroupid=<?=$taggroupid?>'; 
    var title = '添加';
    layer.open({
      type: 2,
      title: title,
      maxmin: true,
      shadeClose: false, //点击遮罩关闭层
      area : ['600px' , '300px'],
      content: content
    });
  });
</script>
<!-- 页面操作逻辑 结束-->

