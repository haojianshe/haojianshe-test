  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="4" >
        	共有<?= count($models) ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        	<input type="button" id="btnnew" value="新建资源" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th >资源编号</th>
        <th >资源名称</th>
        <th >资源url地址</th>
        <th >备注</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model->resourceid ?></td>
      <td><?= $model->resourcename ?></td>
      <td><?= $model->url ?></td>
      <td><?= $model->desc ?></td>
      <td>
      	<a name='aedit' resourceid='<?= $model->resourceid ?>' href='#'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp; 
      </td>
      </tr>
     <?}?>
  </table>
<div id="_tips"></div>
<script>
//新建按钮绑定事件
$('#btnnew').on('click', function(){
	addedit('');
});

//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
	addedit($(this).attr("resourceid"));
    return false;
});

//编辑或新增资源页面
function addedit(resourceid){
	var content = '/resource/edit';
	var title = '添加新资源';
	if(resourceid != ''){
		content = content + '?resourceid=' + resourceid; 
		title = '编辑资源信息';
	}
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '420px'],
        content: content
    });
}
</script>