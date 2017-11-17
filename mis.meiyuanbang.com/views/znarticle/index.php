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
        	<input type="button" id="btnnew" value="新建文章" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th >文章编号</th>
        <th >分类标注</th>
        <th >标题</th>
        <th >点赞数</th>
        <th >发布人</th>
        <th >发布日期</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['newsid'] ?></td>
      <td><?= $model['mark'] ?></td>
      <td><?= $model['title'] ?></td>
      <td><?= $model['supportcount'] ?></td>
      <td><?= $model['username'] ?></td>
      <td><?= date('Y-m-d',$model['utime']); ?></td>
      <td>
        <? if($model['status'] ==2) { ?>
      		<a name='a_audit' newsid='<?= $model['newsid'] ?>' href='#' style='color:red'>审核</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<? } ?>
      	<a href='<?= Yii::$app->params['msiteurl'].'zhnarticle?isrepeat=1&id='.$model['newsid'] ?>' target='_blank'>预览</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='aedit' newsid='<?= $model['newsid'] ?>' href='#'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='adel' newsid='<?= $model['newsid'] ?>' href='#'>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
      </td>
      </tr>
     <?}?>
     <tr class="operate">
	      <td colspan="6">
			<div class="cuspages right">
			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
			</div>      
	      </td>
      </tr>
  </table>
<div id="_tips"></div>
<script>
//新建按钮绑定事件
$('#btnnew').on('click', function(){
	addedit(0);
});

//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
	addedit($(this).attr("newsid"));
    return false;
});

//审核按钮绑定事件
$("a[name=a_audit]").click(function () {
	var newsid = $(this).attr("newsid");
    layer.confirm('是否确定通过审核？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/znarticle/audit",
            data: "newsid=" + newsid,//要发送的数据                    
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
    }, function(){
        //取消
    });
    return false;
});

//删除按钮绑定事件
$("a[name=adel]").click(function () {
	var newsid = $(this).attr("newsid");
    layer.confirm('删除后将不可恢复，确定删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/znarticle/del",
            data: "newsid=" + newsid,//要发送的数据                    
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
    }, function(){
        //取消
    });
    return false;
});

//编辑或新增用户页面
function addedit(newsid){
	var content = '/znarticle/edit';
	var title = '添加文章';
	if(newsid >0){
		content = content + '?newsid=' + newsid; 
		title = '编辑文章--编号:'+ newsid;
	}
	top.parent.layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['70%' , '100%'],
        content: content
    });
}
</script>