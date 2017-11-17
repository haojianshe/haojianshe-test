  <?php
  use common\widgets\MyLinkPager;
  use common\service\dict\BookDictDataService;
  use common\service\DictdataService;
  ?>

<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
<script>
    function fun() {
        var result = true;
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        if (start_time != '' || end_time != '') {
            if (start_time > end_time) {
                layer.msg('开始时间不能大于结束时间，请你重新选择结束时间', {icon: 2});
                $("#end_time").val('');
                $("#end_time").focus();
                result = false;
            }
            if (start_time == end_time) {
               layer.msg('开始时间不能等于于结束时间，请你重新选择', {icon: 2});
                $("#end_time").val('');
                $("#end_time").focus();
                result = false;
            }
        }
        if (start_time == '' && end_time != '') {
           layer.msg('开始时间不能为空，请你选择开始时间', {icon: 2});
            $("#start_time").val('');
            $("#start_time").focus();
            result = false;
        }
        return result;
    }
</script>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list" >
    <thead>
          <th colspan="10" style='text-align:right;' >
               <div id="searchid">
                <form name="searchform" action="/lecture/index" method="get"  onsubmit="return fun()">
                  <table width="100%" cellspacing="0" class="search-form" >
                    <tbody  style=" border: 1px solid #ddd;">
                     <tr >
                      <td>
                          <div style="float:left;" class="explain-col"> 
                          类&nbsp;&nbsp;&nbsp;&nbsp;别：
                          <select name="newstype">
                                <option value="0"  >请选择</option>
                                <option value="1" <?php if($newstype==1){ echo 'selected=selected';}?> >精讲文章</option>
                                 <option value="2" <?php if($newstype==2){ echo 'selected=selected';}?>>精讲专题</option>
                          </select>
                           &nbsp;
                          置顶类型：
                          <select name="ztop">
                                <option value="0"  >请选择</option>
                                <option value="1" <?php if($ztop==1){ echo 'selected=selected';}?> >未置顶</option>
                                 <option value="2" <?php if($ztop==2){ echo 'selected=selected';}?>>置顶</option>
                          </select>
                          &nbsp;审核状态：
                          <select name="status">
                                <option value="0"  >请选择</option>
                                <option value="1" <?php if($status==1){ echo 'selected=selected';}?> >已审核</option>
                                 <option value="2" <?php if($status==2){ echo 'selected=selected';}?>>待审核</option>
                          </select>
                           &nbsp;发布人：
                          <select name="adminuser">
                                <option value="0"  >请选择</option>
                                <?php
                                if($userData){
                                    foreach($userData as $k=>$v){
                                        if($adminuser==$v['mis_realname']){
                                            $w = 'selected=selected';
                                        }else{
                                            $w = '';
                                        }
                                        echo "<option $w value=".$v['mis_realname'].">".$v['mis_realname']."</option>";
                                    }
                                }
                                ?>
                          </select>
                           &nbsp;
                          主分类：
                         <?php
                         $array = [];
                         foreach(DictdataService::getLectureMainType() as $key=>$val){
                             $array[$val['maintypeid']] = $val['maintypename'];
                         }
                          echo BookDictDataService::createMenuList('f_catalog_id',$array , $f_catalog_id, 'f_catalog_id','','','','','<option value="0" >请选择</option>');
                         ?>
                          &nbsp;
                          子分类：
                    <?php
                    if(empty($s_catalog_id || $f_catalog_id)){
                         echo '<select name="s_catalog_id" id="s_catalog_id">
                         <option value="" >请选择</option>
                          </select>';    
                         }else{
                             $newArray = DictdataService::getLectureSubType($f_catalog_id);
                             if($newArray){
                               foreach($newArray as $key=>$val){
                                 $arrays[$val['subtypeid']] = $val['subtypename'];
                                }
                                echo BookDictDataService::createMenuList('s_catalog_id',$arrays , $s_catalog_id, 's_catalog_id','','','','','<option value="0" >请选择</option>');
                             }
                         }
                         ?>
                        </div>
                      </td>
                    </tr>
                     <tr>
                             <td>
                        <div style="float:left;" class="explain-col"> 
                           <span class="seach_block">
                                      开始时间:
                                            <input type="text" name="start_time" id="start_time" value="<?php echo @$start_time ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
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
                                           结束时间： <input type="text" name="end_time" id="end_time" value="<?php echo @$end_time ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
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
                                             &nbsp;
                          标题:
                         <input name="title" type="text" value="<?php echo $title?>"   size="15px"  class="input-text">
                          &nbsp;
                          精讲ID:
                          <input name="idname" type="text" value="<?php echo $idname?>" size="15px" class="input-text">
                             &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                             <input type="submit" name="search" class="button" value="搜索" />
                          &nbsp;
                          <input type="button" id="btnnew" value="新建精讲" size="1px"  class="button"/>
                         &nbsp;
                          <input type="button" id="subject" value="新建专题" size="10px"  class="button"/>
                        </div>
                      </td>
                        </tr>
                  </tbody>
                </table>
              </form>
            </div>
        </th>
          <tr class="operate" >
          <th colspan="10">共<?= "<strong>".$pages->totalCount."</strong>";?>条记录&nbsp;浏览数<?php
                foreach ($models as $key => $value) {
                  $arr2[] = $value['hits'];
                }
                if($arr2){
                      echo "<strong>".array_sum($arr2)."</strong>";
                }else{
                    echo 0;
                }
              
              ?></th>
      </tr>
        <tr class="tb_header">
        <th style='width:50px'>精讲编号</th>
        <th style='width:40px'>类别</th>
        <th style='width:85px;text-align:center;'>分类</th>
        <th style='text-align:center;'>标题</th>
        <th style='width:40px'>点赞数</th>
        <th style='width:40px'>浏览数</th>
        <th style='width:50px'>发布人</th>
        <th style='width:150px;text-align:center;'>发布时间</th>
        <th style='width:80px'>创建日期</th>
        <th style='width:290px;text-align:center;'>操作</th>
      </tr>   
    </thead>
    <?
    foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['newsid'] ?></td>
      <td><?php if($model['newstype']==1){ echo '文章';}else if($model['newstype']==2){ echo '<strong>专题</strong>';} ?></td>
      <td><?= $model['lecturetype'] ?></td>
      <td><?= $model['title'] ?></td>
      <td><?= $model['supportcount'] ?></td>
      <td><?= $model['hits'] ?></td>
      <td><?= $model['username'] ?></td>
      <td>
	      <? if($model['publishtime'] ==0) { ?>
	      	<span style='color:#79a605'>无</span>
		  <? } else if ($model['publishtime'] >time()){?>
		  	<span style='color:red'><?= date('Y-m-d H:i',$model['publishtime']); ?>(定时)</span>
		  <? } else {?>
		  	<span><?= date('Y-m-d H:i',$model['publishtime']); ?></span>
		  <? } ?>
      </td>
      <td><?= date('Y-m-d',$model['utime']); ?></td>
      <td>
	      <table style='width:100%'>
	      	<tr>
		    	<td style="width:32px">
		    		<? if($model['status'] ==2) { ?>
		      		<a name='a_audit' newsid='<?= $model['newsid'] ?>' href='#' style='color:red'>审核</a>&nbsp;&nbsp;
		      		<? } ?>	
		    	</td>
		    	<td style="width:32px">
		    		<?php
			        if($model['newstype']==2){
			        ?>
			       <a href='<?= Yii::$app->params['msiteurl'].'lecture/subject?newsid='.$model['newsid'] ?>' target='_blank'>预览</a>&nbsp;&nbsp;
			        <?php }else{
			            ?>
			        <a href='<?= Yii::$app->params['msiteurl'].'lecture?isrepeat=1&id='.$model['newsid'] ?>' target='_blank'>预览</a>&nbsp;&nbsp;
			           <?php
			        } ?>
		    	</td>
		    	<td style="width:40px">
		    		<?php
			         if($model['stick_date']){
			        ?>
			      	<a href='javascrip:;' name='ztop' value="0"  newsid='<?= $model['newsid'] ?>' style=" color: red">取消置顶</a>&nbsp;&nbsp;
			         <?php }else{ ?>
			        <a href='javascrip:;' name='ztop' value="1"  newsid='<?= $model['newsid'] ?>' style=" color: green">置顶</a>&nbsp;&nbsp;
			         <?php }?>
		    	</td>
		    	<td style="width:32px">
		    		<a name='aedit' newsid='<?= $model['newsid'] ?>' href='#' newstype="<?php echo $model['newstype'];?>" >编辑</a>&nbsp;&nbsp;		
		    	</td>
		    	<td style="width:32px">
		    		<a name='adel' newsid='<?= $model['newsid'] ?>' href='#'>删除</a>&nbsp;&nbsp;
		    	</td>
		    	<td style="width:68px">
		    		<?php
			        if($model['newstype']==2){
			        ?>
			        <a name='insertTag' newsid='<?= $model['newsid'] ?>' class="<?= $model['title'] ?>" href='javascript:;' style="color:green">添加副标题</a>&nbsp;&nbsp;
			        <?php } ?>
		    	</td>
	    	</tr>
	      </table>        
      </td>
      </tr>
     <?}?>
     <tr class="operate">
	      <td colspan="6">
			<div class="cuspages right">
			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
			</div>      
	      </td>
      </tr>
  </table>
  
  <input type="hidden" id="inputid" value="" />
  <div id="_tips"></div>
<script>
//新建按钮绑定事件
$('#btnnew').on('click', function(){
	addedit(0,1);
         return false;
});
//新建专题按钮
$('#subject').on('click', function(){
	addedit(0,2);
        return false;
});

//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
    addedit($(this).attr("newsid"),$(this).attr("newstype"));
    return false;
});


//置顶按钮
$("a[name=ztop]").click(function () {
    var newsid = $(this).attr("newsid");
    var value = $(this).attr("value");
    layer.confirm('确定操作吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lecture/ztop",
            data: "newsid=" + newsid+"&value="+value,//要发送的数据                    
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

//审核按钮绑定事件
$("a[name=copyNewData]").click(function () {
   var newsid = $(this).attr("newsid");
   var title = $(this).attr("class");
    layer.confirm('是否添加活动？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lecture/copynewdata",
            data: "newsid=" + newsid+"&title="+title,//要发送的数据                    
            success: function (data) {
                if (data.errno == 0) {
                    layer.msg(data.msg,{icon: 1});
                }else if(data.errno == 2) {
                    layer.msg(data.msg,{icon: 1});
                }else if(data.errno == 1) {
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
//审核按钮绑定事件
$("a[name=a_audit]").click(function () {
    var newsid = $(this).attr("newsid");
    layer.confirm('是否确定通过审核？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lecture/audit",
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
            url: "/lecture/del",
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


    $("a[name=insertTag]").click(function () {
        //增加副标题页面
        addTag($(this).attr("newsid"));
    });
    
     function addTag(newsid){
            var content = '/lecture/addtag';
            var title = '副标题列表';
            content = content + '?newsid=' + newsid; 
            layer.open({
                type: 2,
                title: title,
                maxmin: false,
                shadeClose: false, //点击遮罩关闭层
                area : ['90%' , '90%'],
                content: content
           });
     }

//编辑或新增用户页面
function addedit(newsid,type){
     
        if(type==2){
             titles = '专题';
        }else{
            titles = '文章';
        }
	var content = '/lecture/edit';
	var title = '添加精讲'+titles;
	if(newsid >0){
		content = content + '?newsid=' + newsid+"&type="+type; 
		title = '编辑精讲'+titles+'--编号:'+ newsid;
	}else{
            content = content + '?type='+type; 
        }
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['90%' , '90%'],
        content: content
    });
}
        var index = parent.layer.getFrameIndex(window.name);
      $("#f_catalog_id").change(function () {
        var f_catalog_id = $(this).val();
        if(f_catalog_id==0){
             $("#s_catalog_id option").remove();
              $("#s_catalog_id").append('<option value=0>请选择</option>');
             return;
        } 
        $("#dividid tr").remove();
        var url = '/lecture/select_menu';
        var data = {
            f_catalog_id: f_catalog_id,
            type:2
        }
        $.post(url, data, function (m) {
            $("#s_catalog_id option").remove();
            $("#s_catalog_id").append('<option value=0>请选择</option>');
            $("#s_catalog_id").append(m);
        }, 'json');
    });
  
</script>