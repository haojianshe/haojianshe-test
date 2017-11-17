  <?php
  use common\widgets\MyLinkPager;
  use mis\service\OrderinfoService;
  ?>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="tb_header">
        <th >订单号</th>
        <th >订单名称</th>
        <th >渠道</th>
        <th >下单时间</th>
        <th >总价格</th>
        <th >优惠</th>
        <th >订单金额</th>
        <th >用户名</th>
         <th >用户电话</th>
      </tr>
    </thead>
    <?php
    if($models){
    foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['orderid'] ?></td>
      <td><?= $model['ordertitle'] ?></td>
       <td><?= $model['order_from'] ?></td>
       <td><?= date('Y-m-d H:i:s',$model['ctime']);// $model['course_group_fee'] ?></td>
       <td><?=  $model['course_sale_price'] ?></td>
       <td><?=  ($model['course_sale_price']-$model['fee']) ?></td>
       <td><?=  $model['fee'] ?></td>
       <td><?= $model['sname'] ?></td>
       <td><?= $model['umobile'] ?></td>
      </tr>
     <?php 
      }
      }
    ?>
     <tr class="operate">
	      <td colspan="11">
			<div class="cuspages right">
			<?php
                       if($pages){
                           echo  MyLinkPager::widget(['pagination' => $pages]);
                       }
                        ?>
			</div>      
	      </td>
      </tr>
  </table>
<div id="_tips"></div>
<script>
    
        //保存按钮
        $("#asave").click(function () {
           var start_time=$("#start_time").val();
           var end_time=$("#end_time").val();
            if(start_time==""){
            	layer.msg('请选择开始时间', {icon: 2});
                return false;
            }
            if(end_time==""){
            	layer.msg('请选择结束时间', {icon: 2});
                return false;
            }
            $("form").submit();
            return false;
        });

function getOrderlist(id){
    	var content = '/groupbuy/order_list';
	var title = '获取已支付订单';
        content = content + '?groupbuyid=' + id; 
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['90%' , '100%'],
        content: content
    });
}
    
//新建按钮绑定事件
$('#btnnew').on('click', function(){
	addedit(0);
});

//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
	addedit($(this).attr("newsid"));
    return false;
});


//编辑或新增用户页面
function addedit(newsid){
	var content = '/groupbuy/edit';
	var title = '添加活动';
	if(newsid >0){
		content = content + '?groupbuyid=' + newsid; 
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
            url: "/groupbuy/del",
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

</script>