  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="8" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        <input type="button" id="btnadd" value="添加黑名单" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th style="width:10%">头像</th>
        <th style="width:15%">用户编号</th>
        <th style="width:15%">昵称</th>
        <th style="width:8%">用户手机号</th>
        <th style="width:8%">登录方式</th>
        <th style="width:8%">备注</th>
        <th style="width:8%">注册日期</th>
        <th style="width:8%">拉黑日期</th>
        <th style="width:15%">操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><img src='<?= $model['avatars']?>' style='height:80px;width:80px;'/> </td>
      <td><?= $model['uid'] ?></td>
      <td><?= $model['sname'] ?></td>
      <td><?= $model['umobile'] ?></td>
      <td><?= $model['oauth_type'] ?></td>
      <td><?= $model['desc'] ?></td>
      <td><?= date('Y-m-d H:i:s',$model['create_time']); ?></td>
      <td><?= date('Y-m-d H:i:s',$model['ctime']); ?></td>
      <td>
      	<a name='adel' uid='<?= $model['uid'] ?>' href='#'>移出黑名单</a> 
      </td>
      </tr>  
     <?}?>
      <tr class="operate">
	      <td colspan="6">&nbsp;
			<div class="cuspages right">
			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
			</div>      
	      </td>
      </tr>
  </table>  
<script>
//新建按钮绑定事件
$('#btnadd').on('click', function(){
	var content = '/blacklist/search';
	var title = '添加黑名单';
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['800px' , '520px'],
        content: content
    });
});
//移除绑定事件
$("a[name=adel]").click(function () {
	var userid = $(this).attr("uid");
    layer.confirm('确定将用户移出黑名单吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/blacklist/del",
            data: "userid=" + userid,//要发送的数据                    
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
</script>