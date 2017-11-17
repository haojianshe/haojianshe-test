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
        <th colspan="11" style='text-align:right;' >
               <div id="searchid">
                <form name="searchform" action="/groupbuy/index" method="get"  onsubmit="return fun()">
                  <table width="100%" cellspacing="0" class="search-form" >
                    <tbody>
                     <tr>
                         <td>
                        <div style="float:left;" class="explain-col"> 
                           <span class="seach_block">
                                      开始时间:
                                            <input type="text" name="start_time" id="start_time" value="<?php 
                                            if($data['start_time']){
                                                echo date("Y-m-d H:i:s",$data['start_time']);
                                            }
                                             ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "start_time",
                                                    trigger: "start_time",
                                                    dateFormat: "%Y-%m-%d 00:00:00",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;结束时间： <input type="text" name="end_time" id="end_time" value="<?php 
                                            if($data['end_time']){
                                                echo date("Y-m-d H:i:s",$data['end_time']);
                                            }
                                            
                                            ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            </span>
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "end_time",
                                                    trigger: "end_time",
                                                    dateFormat: "%Y-%m-%d 00:00:00",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>
<!--                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;状态
                                            <select name="type">
                                                <option value="0">请选择</option>
                                                <option value="1">进行中</option>
                                                <option value="2">已上线</option>
                                                <option value="3">未上线</option>
                                            </select>-->
                                            &nbsp; &nbsp;<input type="submit" name="search" id="asave"  value="搜索" class="button"/>
                        </div>
                      </td>
                      <td>&nbsp; &nbsp;<input type="button" onclick="addedit(0);"  id="btnnew" value="新建团购" class="button"/></td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>
        </th>
        <tr class="operate" style="text-align:right;">
        <th colspan="11" >
            共<strong><?= $pages->totalCount ?></strong>条记录
          总金额<strong><?= $data['orderSum']->fee;?></strong>元
        </th>
        
      </tr>
      <tr class="tb_header">
        <th >编号</th>
        <!--<th >标题</th>-->
        <th >课程名称</th>
        <th >团购价格</th>
        <th >开始时间</th>
        <th >结束时间</th>
        <th >团购总数</th>
        <th >订单数</th>
        <th >订单金额</th>
        <th >状态</th>
        <th >操作</th>
      </tr>
    </thead>
    <?php
    if($models){
    foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['groupbuyid'] ?></td>
      <!--<td><?//= $model['title'] ?></td>-->
       <td><?= $model['courseTitle'] ?></td>
       <td><?= $model['course_group_fee'] ?></td>
       <td><?= date('Y-m-d H:i:s',$model['start_time']);// $model['course_group_fee'] ?></td>
       <td><?= date('Y-m-d H:i:s',$model['end_time']);// $model['course_group_fee'] ?></td>
       <td><?= $model['person_count_total'] ?></td>
       
       <td><a onclick="getOrderlist(<?=$model['groupbuyid']?>)" style="cursor:pointer;"><?= OrderinfoService::getGroupBuyOrderCount($model['groupbuyid'],1); ?><a/></td>
       <td><?= OrderinfoService::getGroupBuyOrderCount($model['groupbuyid'],2); ?></td>
       <td>
        <?php
            if($model['start_time']>time()){
                echo '<span style="color:blur">未上线</span>';
            }else if($model['start_time']<time() && $model['end_time']>time()){
                 echo '<span style="color:green">进行中</span>';
            }else if($model['end_time']<time()){
                 echo '<span style="color:red">已结束</span>';
            }
            ?>
       </td>
        <td>
            <?php
            if($model['start_time']>time()){
              ?>
            <a name='aedit' newsid='<?= $model['groupbuyid'] ?>'  >编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
          <a name='adel' newsid='<?= $model['groupbuyid'] ?>' style="cursor:pointer;">删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php
            }else if($model['start_time']<time() && $model['end_time']>time()){
                  ?>
            <a name='aedit' newsid='<?= $model['groupbuyid'] ?>' style="cursor:pointer;">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php 
            }else if($model['end_time']<time()){
                
            }
            ?>
       
        </td>
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