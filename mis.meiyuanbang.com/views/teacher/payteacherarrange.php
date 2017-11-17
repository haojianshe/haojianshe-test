  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr >
      </tr>
      <tr class="operate">
        <th colspan="5" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        <input type="button"onclick="teacherArrangeEdit(0)" value="添加排班" class="button button-primary  button-small"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th style="width:8%">编号</th>
        <th style="width:15%">用户</th>
        <th >开始时间</th>
        <th >结束时间</th>
        <th style="width:8%">创建时间</th>        
        <th style="width:15%">操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
          <td><?= $model['arrangeid'] ?></td>
          <td >
            <? foreach ($model['teacherlist'] as $key => $value) {?>
              <!-- <img style="width: 40px;height: 40px;" src="<?=$value['avatars']?>"> --><?=$value['sname']?>,
              <?} ?>
          </td>
          <td><?= date('Y-m-d H:i:s',$model['btime']); ?></td>
          <td><?= date('Y-m-d H:i:s',$model['etime']); ?></td>
          <td><?= date('Y-m-d H:i:s',$model['ctime']); ?></td>
          <td>
          		<a onclick="teacherArrangeEdit(<?= $model['arrangeid'] ?>)" href="javascript:;">编辑</a>  
          		<a onclick="delArrange(<?= $model['arrangeid'] ?>)" href='javascript:;'>删除</a> 
          </td>
      </tr>  
     <?}?>
      <tr class="operate">
	      <td colspan="8">
    			<div class="cuspages right">
    			   <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
    			</div>      
	      </td>
      </tr>
  </table>  
<script>
//新建编辑
function teacherArrangeEdit(arrangeid){
	var content = '/teacher/pay_teacher_arrange_edit';
  var title = '添加付费老师排班';
  if(arrangeid){
      title = '编辑付费老师排班';
      content=content+"?arrangeid="+arrangeid;
  }
	
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['800px' , '620px'],
        content: content
    });
}

//删除排班
function delArrange(arrangeid) {
    layer.confirm('确定删除？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/teacher/pay_teacher_arrange_del",
            data: "arrangeid=" + arrangeid,//要发送的数据                    
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
}

</script>