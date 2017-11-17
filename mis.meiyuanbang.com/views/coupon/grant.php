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
            <input type="button" onclick="addedit(0);" id="btnnew" value="新建投放" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th>编号</th>
        <th>投放标题</th>
        <th>投放类型</th>
        <th>个数</th>
        <th>用户(创建/审核)</th>
        <th>创建时间</th>
        <th>操作</th>
       </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td><?= $model['coupongrantid'] ?></td>
        <td><?= $model['title'] ?></td>
        <td><?php if($model['granttype']==0){ echo '实时发放';}else { echo "<span style='color:red'>预发放</span>";} ?></td>
        <td><?= $model['num'] ?></td>        
        <td><?= $model['grantuser'] ?>/<?= $model['audituser'] ?></td>
        <td><?= date('Y-m-d',$model['ctime']); ?></td>
        <td width="250px">
          <?if($model['status']==1){?>
          <a onclick="upadtestatus(<?= $model['coupongrantid'] ?>,2);" href='javascript:;' style="color:red;">审核</a>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <a name='upadtestatus' onclick="upadtestatus(<?= $model['coupongrantid'] ?>,3);" href='javascript:;'>删除</a>
          <?}else{?>
          已审核
          <?}?>&nbsp;&nbsp;
          <a href='javascript:;' onclick="openLink('/coupon/grant_user?coupongrantid=<?= $model['coupongrantid'] ?>','<?= $model['title'] ?>')">用户列表</a>
          &nbsp;&nbsp;
          <?if($model['status']==2 && $model['granttype']==1 && $model['waiting_grant_mobiles']!=''){?>
          	<a href='javascript:;' onclick="layer.msg('<?= $model['waiting_grant_mobiles'] ?>',{icon: 0});">未发放用户</a>
          <?}?>
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
    function addedit(){
    	var content = '/coupon/grant_add?couponid=<?=$couponid?>';
    	var title = '添加';
    	layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['90%' , '90%'],
            content: content
      });
    }
    //删除 审核
    function upadtestatus(coupongrantid,status){
      if(status==3){
         title="是否删除？";
      }else if(status==2){
        title="审核通过？";
      }
        layer.confirm(
          title, 
          {
            btn: ['是','否']
          },
        function(){
          layer.load();
          $.ajax({
            type: "post",
            dataType: "json",
            url: "/coupon/grant_updatestatus",
              data: "coupongrantid=" + coupongrantid+"&status="+status,//要发送的数据
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