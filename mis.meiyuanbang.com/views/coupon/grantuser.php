<?php 
use common\widgets\MyLinkPager;
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
        <th>用户编号</th>
        <th>用户名</th>
       
       </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td><?= $model['uid'] ?></td>
       <td><img width="40px" src="<?= json_decode($model['avatar'])->img->n->url ?>"><?= $model['sname'] ?> </td>
       
      </tr>
    <?}?>
   
  </table>
<script>
  
    function openLink(url,title){
        layer.open({
            type: 2,
            title:title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['90%' , '90%'],
            content: url
      });
    }
    //编辑或新增
    function addedit(couponid){
    	var content = '/coupon/edit';
    	var title = '添加';
    	if(couponid >0){
    		content = content + '?couponid=' + couponid; 
    		title = '编辑--编号:'+ couponid;
    	}
    	layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['60%' , '90%'],
            content: content
      });
    }
    //删除 审核
    function upadtestatus(couponid){
      title="是否删除？";
      layer.confirm(
        title, 
        {
          btn: ['是','否']
        },
        function(){
          $.ajax({
            type: "post",
            dataType: "json",
            url: "/coupon/del",
              data: "couponid=" + couponid+"&status=2",//要发送的数据
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