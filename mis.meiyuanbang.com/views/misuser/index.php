  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="5" >
        	共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        	<input type="button" id="btnnew" value="新建用户" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th style="width:10%">用户编号</th>
        <th style="width:15%">用户名</th>
        <th style="width:8%">真实姓名</th>
        <th style="width:8%">邮箱</th>
        <th style="width:8%">部门</th>
        <th style="width:15%">操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model->mis_userid ?></td>
      <td><?= $model->mis_username ?></td>
      <td><?= $model->mis_realname ?></td>
      <td><?= $model->email ?></td>
      <td><?= $model->department ?></td>
      <td>
      	<a name='aedit' uid='<?= $model->mis_userid ?>' href='#'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='asetrole' uid='<?= $model->mis_userid ?>' href='#'>设置用户角色</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='achgpwd' uid='<?= $model->mis_userid ?>' href='#'>修改密码</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a name='vestmanage' uid='<?= $model->mis_userid ?>' href='#'>马甲管理</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='adel' uid='<?= $model->mis_userid ?>' href='#'>删除</a> 
        
        
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
<div id="_tips"></div>
<script>
//可使用prompt方法
layer.config({
    extend: 'extend/layer.ext.js'
});
//新建按钮绑定事件
$('#btnnew').on('click', function(){
    addedit(0);
});
//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
	addedit($(this).attr("uid"));
    return false;
});
//用户角色设定
$("a[name=asetrole]").click(function () {
	setrole($(this).attr("uid"));
    return false;
});

//马甲用户管理
$("a[name=vestmanage]").click(function () {
  vestmanage($(this).attr("uid"));
    return false;
});


//http://yii.meiyuanbang.cn/vesttweet/vestmanage?mis_userid=1
//管理用户
function vestmanage(userid){
  var content = '/misuser/vestmanage';
  var title = '编辑马甲用户';
  if(userid >0){
    content = content + '?mis_userid=' + userid; 
    title = '编辑马甲用户';
  }
  layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '420px'],
        content: content
    });
}


//删除绑定事件
$("a[name=adel]").click(function () {
	var userid = $(this).attr("uid");
    layer.confirm('用户删除后将不可恢复，确定删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/misuser/del",
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
//修改密码
$("a[name=achgpwd]").click(function () {
	var userid = $(this).attr("uid");
	layer.prompt({
	    title: '请输入新密码',
	    formType:0  //prompt风格，支持0-2
	}, function(pass){
		//检查密码长度
		if(pass.length<6){
			layer.msg('请输入6位以上密码',{icon: 2});
			return false;
		}
		//进行修改
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/misuser/chgpwd",
            data: "userid=" + userid + "&pwd="+pass,//要发送的数据                    
            success: function (data) {
                if (data.errno == 0) {
            		layer.msg('修改成功',{icon: 1});
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
    return false;
});


//编辑或新增用户页面
function addedit(userid){
	var content = '/misuser/edit';
	var title = '添加新用户';
	if(userid >0){
		content = content + '?userid=' + userid; 
		title = '编辑用户';
	}
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '420px'],
        content: content
    });
}
//编辑或新增用户页面
function setrole(userid){
	var content = '/misuser/userrole';
	var title = '编辑用户角色';
	content = content + '?userid=' + userid; 
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '520px'],
        content: content
    });
}
</script>