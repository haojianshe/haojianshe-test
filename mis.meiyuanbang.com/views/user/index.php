  <?php
   use common\widgets\MyLinkPager;
    use common\service\dict\BookDictDataService;
  use common\service\DictdataService;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
  
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">    
            <th colspan="10">
               <div id="searchid">
                <form name="searchform" action="/user/index" method="get" >
                  <table width="100%" cellspacing="0" class="search-form">
                    <tbody>
                     <tr>
                      <td>
                        <div class="explain-col">
                                  <span class="seach_block">
                                      报名开始时间:<input type="text" name="start_time" id="start_time" value="<?php echo @$start_time ?>" class="inputclass1" readonly="readonly" style="width:190px">&nbsp;
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "start_time",
                                                    trigger: "start_time",
                                                    dateFormat: "%Y-%m-%d %H:%M:%S",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>
                                        报名结束时间:<input type="text" name="end_time" id="end_time" value="<?php echo @$end_time ?>" class="inputclass1" readonly="readonly" style="width:190px">&nbsp;
                                            </span>
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "end_time",
                                                    trigger: "end_time",
                                                    dateFormat: "%Y-%m-%d %H:%M",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>
                                            &nbsp;学员姓名：<input name="user_name" type="text" size="8px;" value="<?php echo isset($user_name)?$user_name:""?>" class="input-text" />
                          &nbsp;报名状态：
                          <select name="is_sign">
                                <option value="0"  >请选择</option>
                                <option value="1" <?php if($is_sign==1){ echo 'selected=selected';}?> >已报名</option>
                                <option value="2" <?php if($is_sign==2){ echo 'selected=selected';}?>>未报名</option>
                          </select>&nbsp;&nbsp;
                        <input type="submit" name="search" class="button" value="搜索" />
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>
        </th>
        </tr>
      <tr class="operate">
        <th colspan="8" >共有<?= $pages->totalCount ?>条记录</th>   
        <th colspan="2" style='text-align:right;'><input type="button" id="btnnew" value="新建学员" class="button"/></th>
      </tr>
      <tr class="tb_header">
        <th>用户编号</th>
        <th>姓名</th>
        <th>电话</th>
        <th>年龄(岁)</th>
        <th>是否上过体验课</th>
        <th>是否报名</th>
        <th>创建时间</th>
        <th>报名时间</th>
        <th>上体验课时间</th>
        <th>操作</th>
      </tr>
    </thead>
    <?php
    if(isset($models)){
    foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['uid'] ?></td>
      <td><?= $model['user_name'] ?></td>
      <td><?= $model['umobile'] ?></td>
      <td><?= $model['user_age'] ?></td>
      <td><?PHP if($model['is_expe']==1){ echo '<span style=color:green>是</span>';}else echo '<span style=color:red>否</span>'; ?></td>
      <td><?PHP if($model['is_sign']==1){ echo '<span style=color:green>是</span>';}else echo '<span style=color:red>否</span>'; ?></td>
      <td><?= date('Y-m-d H:i:s',$model['create_time']); ?></td>
      <td><?= date('Y-m-d H:i:s',$model['sign_time']); ?></td>
      <td><?= date('Y-m-d H:i:s',$model['expe_time']); ?></td>
      <td>
        <?php if($model['status']>1) { ?>
      		<a name='a_audit' uid='<?= $model['uid'] ?>' href='#' style='color:red'>审核</a>&nbsp;
        <?php }else{
            echo '<span style=color:green>正常</span>&nbsp;';
        } ?>
      	<a name='aedit' uid='<?= $model['uid'] ?>' >编辑</a>&nbsp;
         <?php if($model['status']==1) { ?>
      			<a name='adel' uid='<?= $model['uid'] ?>' >注销</a>&nbsp;
      	<?php } ?>
      </td>
      </tr>
     <?php 
        }
        }
        ?>
      <tr class="operate">
        <td colspan="10">
          <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
          </div>      
        </td>
      </tr>
  </table>
<div id="_tips"></div>
<script>

//新建按钮绑定事件
$('#btnnew').on('click', function(){
	addedit(0);
});
//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
	addedit($(this).attr("uid"));
    return false;
});
//编辑或新增用户页面
function addedit(uid){
	var content = '/user/edit';
	var title = '添加新学员';
	if(uid >0){
            content = content + '?uid=' + uid; 
            title = '编辑学员--编号:'+ uid;
	}
	top.parent.layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['70%' , '90%'],
        content: content
    });
}

//审核按钮绑定事件
$("a[name=a_audit]").click(function () {
    var uid = $(this).attr("uid");
    var type = 1;
    layer.confirm('已注销用户是否再次启用吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/user/audit",
             data: "uid=" + uid+"&type="+type,//要发送的数据                      
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

//注销按钮绑定事件
$("a[name=adel]").click(function () {
	var uid = $(this).attr("uid");
        var type = 2;
    layer.confirm('確定要注销该学员吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
             url: "/user/audit",
            data: "uid=" + uid+"&type="+type,//要发送的数据                    
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