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
        <th colspan="3" style='text-align:right;'>
        	<input type="button" id="btnnew" value="添加启动图" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th >编号</th>
        <th >备注</th>
        <th >跳转地址</th>
        <th >有效期</th>
        <th >状态</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['pageid'] ?></td>
      <td><?= $model['desc'] ?></td>
      <td><?= $model['jumpurl'] ?></td>
      <td><?= date('Y-m-d H:i',$model['startdate']); ?>&nbsp;&nbsp;至&nbsp;&nbsp;<?=date('Y-m-d H:i',$model['expiredate']); ?></td>
      <td>
	      <? $curtime = time();
	         $stime = intval($model['startdate']);
	         $etime = intval($model['expiredate']);
	         if($curtime<$stime){
	         	echo '<span style="color:red;">未开始</span>';
	         }
	         elseif($curtime>$etime){
	         	echo '<span style="color:red;">已过期</span>';
	         }
	         else{
	         	echo '<span style="color:green;">使用中</span>';
	         }
	       ?>
      </td>
      <td>
        <a name='aedit' pageid='<?= $model['pageid'] ?>' href='#'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
	addedit($(this).attr("pageid"));
    return false;
});
//编辑或新增用户页面
function addedit(pageid){
	var content = '/startpage/edit';
	var title = '添加启动图';
	if(pageid >0){
		content = content + '?pageid=' + pageid; 
		title = '编辑启动图--编号:'+ pageid;
	}
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['50%' , '80%'],
        content: content
    });
}
</script>