  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
     <tr >
<!--        <th colspan="8" >
          <div class="button-group" >
            <a href="/teacher/" class="button button-small button-primary">老师认证</a>
            <a href="/teacher/redindex" class="button button-small">红笔老师</a>
          </div>
        </th>-->
      </tr>
      <tr class="operate">
        <th colspan="7" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        <input type="button" id="btnadd" value="添加认证老师" class="button button-primary  button-small"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th style="width:5%">头像</th>
        <th style="width:8%">用户编号</th>
        <th style="width:8%">昵称</th>
        <th style="width:8%">用户手机号</th>
        <th style="width:8%">登录方式</th>
        <th style="width:8%">是否殿堂老师</th>
        <th style="width:8%">注册日期</th>        
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
      <td><? if($model['ukind_verify']==1){echo "<span style='color:green;'>是</span>";}else{echo '否';} ?></td>
      <td><?= date('Y-m-d H:i:s',$model['create_time']); ?></td>
      <td>
      	<a name='adelteacher' uid='<?= $model['uid'] ?>' href='#'>取消认证</a>
      	<? if($model['ukind_verify']==1) { ?>
      		<a name='adelfamous' uid='<?= $model['uid'] ?>' href='#'>取消殿堂</a> 
      	<? } else{ ?>
      		<a name='aaddfamous' uid='<?= $model['uid'] ?>' href='#'>加入殿堂</a> 
      	<? } ?>
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
//新建按钮绑定事件
$('#btnadd').on('click', function(){
	var content = '/teacher/search';
	var title = '添加认证老师';
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['800px' , '620px'],
        content: content
    });
});
//为取消认证绑定事件
$("a[name=adelteacher]").click(function () {
	var userid = $(this).attr("uid");
    layer.confirm('确定取消用户的老师认证吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/teacher/del",
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

//为取消殿堂绑定事件
$("a[name=adelfamous]").click(function () {
	var userid = $(this).attr("uid");
    layer.confirm('确定取消老师的殿堂资格吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/teacher/famousdel",
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

//为设置殿堂绑定事件
$("a[name=aaddfamous]").click(function () {
	var userid = $(this).attr("uid");
    layer.confirm('确定将老师加入殿堂吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/teacher/famousadd",
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