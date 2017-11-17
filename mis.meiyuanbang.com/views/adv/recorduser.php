<?php 
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="7" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        
      </tr>
      <tr class="tb_header">
        <th>编号</th>
        <th>广告主名称</th>
        <th>负责人</th>
        
        <th>广告数量</th>
        <th>创建时间</th>
        
        <th>操作</th>
       
      </tr>
    </thead>

    <? foreach ($models as $model) { ?>
      <tr class="tb_list">

      	<td><?= $model['advuid']?></td>
		<td><?= $model['name']?></td>

		<td><?= $model['adminuser']?></td>

	
		<td><?= $model['advcount']?></td>
		<td><?=  date("Y-m-d",  $model['ctime']) ?></td>
        <td width="250px">
          <a  href='/adv/record?advuid=<?= $model['advuid'] ?>'>投放管理</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
      </tr>
    <?}?>
    <tr class="operate">
      <td colspan="7">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>
      </td>
    </tr>
  </table>
<script>
    //编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
    	addedit($(this).attr("advuid"));
        return false;
    });
    //编辑或新增
    function addedit(advuid){
    	var content = '/adv/user_edit';
    	var title = '添加';
    	if(advuid >0){
    		content = content + '?advuid=' + advuid; 
    		title = '编辑--编号:'+ advuid;
    	}
    	layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['700px' , '450px'],
            content: content
      });
    }
    //删除 审核
    function upadtestatus(advuid){
      var title="是否删除？";
    
      layer.confirm(
        title, 
        {
          btn: ['是','否']
        },
        function(){
          $.ajax({
            type: "post",
            dataType: "json",
            url: "/adv/user_del",
              data: "advuid=" + advuid+"&status=1",//要发送的数据
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