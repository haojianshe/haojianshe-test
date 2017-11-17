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
          
      <tr class="operate">
        <th colspan="1" >
            共<strong><?= $pages->totalCount ?></strong>条记录
        </th>
        <th colspan="7" style='text-align:right;'>
            <input type="button" onclick="addedit(0);" id="btnnew" value="新建" class="button"/>
        </th>

      </tr>
      <tr class="tb_header">
        <th>编号</th>
        <th>课程券名称</th>
        <th>课程券类型</th>
        <th>使用价格限制</th>
        <th>使用时间限制</th>
        <th>创建时间</th>
        <th>备注</th>
        <th>操作</th>
       </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td><?= $model['couponid'] ?></td>
        <td><?= $model['coupon_name'] ?></td>
        <td><?= $model['coupon_type'] ?></td>
        <td><?= $model['min_price'] ?>-<?= $model['max_price'] ?></td>
      
        <td><?= date('Y-m-d',$model['btime']); ?> 到<?= date('Y-m-d',$model['etime']); ?></td>
        <td><?= date('Y-m-d',$model['ctime']); ?></td>
        <td><?= $model['mark'] ?></td>
        <td width="250px">
        <!--   <a onclick="addedit(<?= $model['couponid'] ?>,3);" href='javascript:;'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;   -->       
          <a  href='javascript:;' onclick="openLink('/coupon/grant?couponid=<?= $model['couponid'] ?>','<?= $model['coupon_name'] ?>')">投放管理</a>&nbsp;&nbsp;&nbsp;&nbsp;

          <a  href='javascript:;' onclick="openLink('/coupon/user?couponid=<?= $model['couponid'] ?>)','<?= $model['coupon_name'] ?>')">使用详情</a>&nbsp;&nbsp;&nbsp;&nbsp;

          <a name='upadtestatus' onclick="upadtestatus(<?= $model['couponid'] ?>,3);" href='javascript:;'>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
      </tr>
    <?}?>
    <tr class="operate">
      <td colspan="11">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>
      </td>
    </tr>
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