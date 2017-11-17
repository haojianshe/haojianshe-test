  <?php
  use common\widgets\MyLinkPager;
  ?>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="3" >
        	考点编号:<?= $lessonmodel->lessonid ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= $lessonmodel->title ?>
        </th>
        <th colspan="3" style='text-align:right;'>
        	<input type="button" id="btnlessonedit" value="编辑考点" class="button"/>
          <input type="button" id="btnnew" value="添加节点" class="button"/>
        	<input type="button" id="desclist" value="管理描述" class="button"/>
        	<input type="button" id="btnback" value="返回列表" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th style='width:80px'>排序字段</th>
        <th style='width:80px'>节点编号</th>
        <th >节点标题</th>
        <th >简介</th>
        <th style='width:80px'>图片数</th>        
        <th style='width:180px'>操作</th>
      </tr>
    </thead>
    <? foreach ($sectionmodels as $model) { ?>
      <tr class="tb_list">
      <td><?= $model->listorder ?></td>
      <td><?= $model->sectionid ?></td>
      <td><?= $model->sectiontitle ?></td>
      <td><?= $model->desc ?></td>
      <td><?= $model->piccount ?></td>
      <td>
      	<a name='aeditinfo' sectionid='<?= $model->sectionid ?>' href='#'>节点信息</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='aeditimg' sectionid='<?= $model->sectionid ?>' href='#'>编辑图片</a>
      </td>
      </tr>
     <?}?>
  </table>
<div id="_tips"></div>
<script>
//编辑基本信息
$('#btnlessonedit').on('click', function(){
	var lessonid = <?= $lessonmodel->lessonid ?>;
	var content = '/lesson/edit';
	var title = '添加考点';
	if(lessonid >0){
		content = content + '?lessonid='+lessonid; 
		title = '编辑考点基本信息--编号:'+ lessonid;
	}
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '530px'],
        content: content
    });
});
//返回按钮
$('#btnback').on('click', function(){
	window.location.href = '/lesson';
});  
//新建按钮绑定事件
$('#btnnew').on('click', function(){
	addedit(0);
});


//新建按钮绑定事件
$('#desclist').on('click', function(){
  var content = '/lesson/desc?lessonid=<?= $lessonmodel->lessonid ?>';
  layer.open({
        type: 2,
        title: "描述列表",
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['90%','80%'],
        content: content
    });
});
//节点编辑按钮绑定事件
$('a[name=aeditinfo]').on('click', function(){
	addedit($(this).attr("sectionid"));
	return false;
});
//编辑或新增考点页面
function addedit(sectionid){
	var content = '/lesson/sectionedit?lessonid=<?= $lessonmodel->lessonid ?>';
	var title = '添加节点';
	if(sectionid >0){
		content = content + '&sectionid=' + sectionid; 
		title = '编辑节点基本信息--编号:'+ sectionid;
	}
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '530px'],
        content: content
    });
}
//编辑图片绑定事件
$('a[name=aeditimg]').on('click', function(){
	var sectionid = $(this).attr("sectionid");
	var content = '/lesson/sectionimg?sectionid='+sectionid;
	var title = '节点图片管理';
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '100%'],
        content: content
    });    
    return false;
});

</script>