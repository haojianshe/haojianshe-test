  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
     <tr >

<!--        <th colspan="9" >
          <div class="button-group" >
            <a href="/teacher/" class="button button-small ">老师认证</a>
            <a href="/teacher/redindex" class="button button-small button-primary">红笔老师</a>
          </div>
        </th>-->
      </tr>
      <tr class="operate">
        <th colspan="11" >
         
           <div id="searchid">
             
                <form name="searchform" action="/teacher/redindex" method="get"  onsubmit="return fun()">
                  <table width="100%" cellspacing="0" class="search-form" >
                    <tbody>
                     <tr>
                         <td>
                          共有<?= $pages->totalCount ?>条记录
                        <div style="float:right;" class="explain-col"> 
                           <select id="pay_type" name="pay_type"> <option value="0" <?if($search['pay_type']==0){echo 'selected="selected"';}?> >免费</option> 
                             <option  <?if($search['pay_type']==1){echo 'selected="selected"';}?> value="1">付费</option> 
                            </select>
                          <input type="submit" name="search" class="button button-primary  button-small" value="搜索" />
                           <input type="button" id="btnadd" value="添加红笔老师" class="button button-primary  button-small"/>
                        </div>
                      </td>
                        </tr>
                  </tbody>
                </table>
              </form>
            </div>

        </th>
       
      </tr>
      <tr class="tb_header">
        <th style="width:5%">头像</th>
        <th style="width:5%">用户编号</th>
        <th style="width:5%">昵称</th>
        <th style="width:15%">擅长科目</th>
        <th style="width:8%">付费价格</th>
        <th style="width:8%">获得金币数</th>
        <th style="width:8%">已批改数</th>
        <th style="width:8%">待批改数</th> 
        <? if($search['pay_type']==0){?>
          <th style="width:8%">当前状态</th> 
        <?}?>       
        <th style="width:15%">操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><img src='<?= $model['avatars']?>' style='height:80px;width:80px;'/> </td>
      <td><?= $model['uid'] ?></td>
      <td><?= $model['sname'] ?></td>
      <td><? if($model['issketch']==1) {echo '速写  '; }?><? if($model['isdrawing']==1) {echo '素描  '; }?><? if($model['iscolor']==1) {echo '色彩  '; }?><? if($model['isdesign']==1) {echo '设计  '; }?></td>
      <td>android:<?= $model['correct_fee'] ?>元 &nbsp;&nbsp;</br>ios:<?= $model['correct_fee_ios'] ?>元</td>
      <td><?= $model['gaincoin'] ?></td>
      <td><?= $model['correctnum'] ?></td>
      <td><?= $model['queuenum'] ?></td>
      <? if($search['pay_type']==0){?>
        <td>
        	<? if($model['status'] ==2) { ?>
        		<a name='a_astatus' uid='<?= $model['uid'] ?>' href='#' style='color:red'>暂停(休息)</a>&nbsp;&nbsp;&nbsp;&nbsp;
        	<? } ?>
        	<? if($model['status'] ==3) { ?>
        		<a name='a_astatus' uid='<?= $model['uid'] ?>' href='#' style='color:red'>暂停(繁忙)</a>&nbsp;&nbsp;&nbsp;&nbsp;
        	<? } ?>
        	<? if($model['status'] ==0) { ?>
        		<a name='a_astatus' uid='<?= $model['uid'] ?>' href='#' style='color:green'>接受批改</a>&nbsp;&nbsp;&nbsp;&nbsp;
        	<? } ?>
        </td>
      <?}?>       
      <td>
        <a name='aedit' uid='<?= $model['uid'] ?>' href='javascript:;'>编辑</a>
      	<a name='adel' uid='<?= $model['uid'] ?>' href='javascript:;'>取消红笔资格</a>
      </td>
      </tr>  
     <?}?>
      <tr class="operate">
	      <td colspan="9">
			<div class="cuspages right">
			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
			</div>      
	      </td>
      </tr>
  </table>  
<script>
//暂停批改 接受批改按钮
$("a[name=a_astatus]").click(function () {
	var userid = $(this).attr("uid");
	//确定进行删除
    $.ajax({
        type: "post",
        dataType: "json",
        url: "/teacher/redstatus",
        data: 'userid='+userid,//要发送的数据                    
        success: function (data) {
            if (data.errno == 0) {
            	layer.msg('操作成功', {icon: 1});
	        	setTimeout(function (){
	        		window.location.reload();
	           	}, 1000);
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

//新建按钮绑定事件
$('#btnadd').on('click', function(){
	var content = '/teacher/search?isred=1';
	var title = '添加红笔老师';
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['800px' , '620px'],
        content: content
    });
});
//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
	var userid = $(this).attr("uid");
	var content = '/teacher/rededit?from=list&userid='+userid;
	var title = '设置红笔老师';
	layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['600px' , '320px'],
        content: content
    });
});

//取消红笔老师绑定事件
$("a[name=adel]").click(function () {
	var userid = $(this).attr("uid");
    layer.confirm('确定取消用户的红笔老师身份吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/teacher/reddel",
            data: "userid=" + userid,//要发送的数据                    
            success: function (data) {
                if (data.errno == 0) {
                	layer.msg('操作成功', {icon: 1});
    	        	setTimeout(function (){
    	        		window.location.reload();
    	           	}, 1000);
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