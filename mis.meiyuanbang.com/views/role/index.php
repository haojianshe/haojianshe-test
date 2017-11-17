  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="4" >
        	共有<?= count($models) ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        	<input type="button" id="btnnew" value="新建角色" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th >角色编号</th>
        <th >角色名称</th>
        <th >上级角色编号</th>
        <th >备注</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model->roleid ?></td>
      <td><?= $model->rolename ?></td>
      <td><?= $model->parent_roleid ?></td>
      <td><?= $model->desc ?></td>
      <td>
      	<a name='aedit' roleid='<?= $model->roleid ?>' href='#'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='apermission' roleid='<?= $model->roleid ?>' href='#'>设置权限</a> 
      </td>
      </tr>
     <?}?>
  </table>
<div id="_tips"></div>
<script>
//新建按钮绑定事件
$('#btnnew').on('click', function(){
	addedit(0);
});

//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
	addedit($(this).attr("roleid"));
    return false;
});

//编辑按钮绑定事件
$("a[name=apermission]").click(function () {
	setpermission($(this).attr("roleid"));
    return false;
});

//编辑或新增用户页面
function addedit(roleid){
	var content = '/role/edit';
	var title = '添加新角色';
	if(roleid >0){
		content = content + '?roleid=' + roleid; 
		title = '编辑角色信息';
	}
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '620px'],
        content: content
    });
}

//为角色设置权限
function setpermission(roleid){
	var content = '/role/setpermission?roleid=' + roleid;
	var title = '为角色授权';
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '520px'],
        content: content
    });
}
</script>