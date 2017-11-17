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
        	<input type="button" id="btnnew" value="新建活动" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th >活动编号</th>
        <th >标题</th>
        <th >浏览数</th>
        <th >活动时间</th>
        <th >发布人</th>
        <th >更新时间</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['newsid'] ?></td>
      <td><?= $model['title'] ?></td>
       <td><?= $model['hits'] ?></td>
      <td><?if($model['btime']){echo '开始:'.date('Y-m-d H:i',$model['btime']).'  ';} ?>  <?if($model['etime']){echo '截止:'.date('Y-m-d H:i',$model['etime']);} ?> </td>
      <td><?= $model['username'] ?></td>
      <td><?= date('Y-m-d H:i:s',$model['utime']); ?></td>
      <td>
      	<a href='<?= Yii::$app->params['msiteurl'].'activity?id='.$model['newsid'] ?>' target='_blank'>预览</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
            url: "/activity/del",
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
	var content = '/activity/edit';
	var title = '添加活动';
	if(newsid >0){
		content = content + '?newsid=' + newsid; 
		title = '编辑活动--编号:'+ newsid;
	}
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['90%' , '100%'],
        content: content
    });
}
</script>