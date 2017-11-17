  <?php
  use common\widgets\MyLinkPager;
  use common\service\dict\BookDictDataService;
  use common\service\DictdataService;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="4" >共有<?=$pages->totalCount;?>条记录</th>
        <th colspan="6" style='text-align:right;'>
               <div id="searchid">
                <form name="searchform" action="/lecture/index?newstype=2" method="get" >
                  <table width="100%" cellspacing="0" class="search-form">
                    <tbody>
                     <tr>
                      <td>
                        <div style="float:right;" class="explain-col"> 
                          置顶类型：
                          <select name="ztop">
                                <option value="0"  >请选择</option>
                                <option value="1" <?php if($ztop==1){ echo 'selected=selected';}?> >未置顶</option>
                                 <option value="2" <?php if($ztop==2){ echo 'selected=selected';}?>>置顶</option>
                          </select>
                          <input type="hidden" name="newstype" value="2" />
                           &nbsp;&nbsp;
                          主分类：
                         <?php
                         $array = [];
                         foreach(DictdataService::getLectureMainType() as $key=>$val){
                             $array[$val['maintypeid']] = $val['maintypename'];
                         }
                          echo BookDictDataService::createMenuList('f_catalog_id',$array , $f_catalog_id, 'f_catalog_id','','','','','<option value="0" >请选择</option>');
                         ?>
                          &nbsp;&nbsp;
                          子分类：
                    <?php
                    if(empty($s_catalog_id)){
                         echo '<select name="s_catalog_id" id="s_catalog_id">
                         <option value="" >请选择</option>
                          </select>';    
                         }else{
                             $newArray = DictdataService::getLectureSubType($f_catalog_id);
                              if($newArray){
                                  foreach($newArray as $key=>$val){
                                   $array[$val['subtypeid']] = $val['subtypename'];
                                  }
                               echo BookDictDataService::createMenuList('s_catalog_id',$array , $s_catalog_id, 's_catalog_id','','','','','<option value="0" >请选择</option>');
                             }
                         }
                         ?>
                          &nbsp;&nbsp;
                          标题:
                         <input name="title" type="text" value="<?php echo $title?>" class="input-text">
                          &nbsp;&nbsp;
                          精讲ID:
                          <input name="idname" type="text" value="<?php echo $idname?>" size="10px" class="input-text">
                          <input type="submit" name="search" class="button" value="搜索" />
                             &nbsp;&nbsp;
                          <input type="button" id="subject" value="新建专题" class="button"/>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>
        </th>
      </tr>
    <script>
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
      
      <tr class="tb_header">
        <th >精讲编号</th>
        <th >类别</th>
        <th >分类</th>
        <th >标题</th>
        <th >点赞数</th>
        <th >发布人</th>
        <th >定时发布</th>
        <th >发布日期</th>
        <th >排序</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['newsid'] ?></td>
      <td><?php if($model['newstype']==1){ echo '精讲文章';}else if($model['newstype']==2){ echo '专题';} ?></td>
      <td><?= $model['lecturetype'] ?></td>
      <td><?= $model['title'] ?></td>
      <td><?= $model['supportcount'] ?></td>
      <td><?= $model['username'] ?></td>
      <td>
	      <? if($model['publishtime'] ==0) { ?>
	      	<span style='color:#79a605'>无</span>
		  <? } else {?>
		  	<span style='color:red'><?= date('Y-m-d H:i',$model['publishtime']); ?></span>
		  <? } ?>
      </td>
      <td><?= date('Y-m-d',$model['utime']); ?></td>
       <td><?php if($model['stick_date']==0){ echo "<span style=\"color: red\">未置顶</span>";}else{ echo "<span style=\"color: green\">已置顶</span>";} ?></td>
      <td>
        <? if($model['status'] ==2) { ?>
      		<a name='a_audit' newsid='<?= $model['newsid'] ?>' href='#' style='color:red'>审核</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<? } ?>
      	<a href='<?= Yii::$app->params['msiteurl'].'lecture?isrepeat=1&id='.$model['newsid'] ?>' target='_blank'>预览</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php
         if($model['stick_date']){
        ?>
      	<a href='javascrip:;' name='ztop' value="0"  newsid='<?= $model['newsid'] ?>' style=" color: red">取消置顶</a>&nbsp;&nbsp;&nbsp;&nbsp;
         <?php }else{ ?>
        <a href='javascrip:;' name='ztop' value="1"  newsid='<?= $model['newsid'] ?>' style=" color: green">置顶</a>&nbsp;&nbsp;&nbsp;&nbsp;
         <?php }?>
      	<a name='aedit' newsid='<?= $model['newsid'] ?>' href='#'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='adel' newsid='<?= $model['newsid'] ?>' href='#'>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php
        if($model['publishtime']==0 && $model['status'] !=2){
        ?>
        <a name='copyNewData' newsid='<?= $model['newsid'] ?>' class="<?= $model['title'] ?>">添加活动</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php }?>
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
//新建专题按钮
$('#subject').on('click', function(){
	addedit(0);
        return false;
});

//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
	addedit($(this).attr("newsid"));
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

//编辑或新增用户页面
function addedit(newsid){
	var content = '/lecture/edit';
	var title = '添加精讲专题';
	if(newsid >0){
		content = content + '?newsid=' + newsid; 
		title = '编辑精讲专题--编号:'+ newsid;
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
</script>