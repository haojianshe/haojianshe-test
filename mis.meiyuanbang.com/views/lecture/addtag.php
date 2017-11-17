  <?php
  use common\widgets\MyLinkPager;
  use common\service\dict\BookDictDataService;
  use common\service\DictdataService;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="2" >共有<?=$pages->totalCount;?>条记录</th>
        <th colspan="3" style='text-align:right;'>
           <div style="float:right;" class="explain-col"> 
                  &nbsp;&nbsp; <input type="button" id="subject" value="添加" class="button"/>
            </div>
        </th>
      </tr>
      <tr class="tb_header">
        <th >标签编号</th>
        <th >标题</th>
        <th >排序</th>
        <th >创建时间</th>
        <th >操作</th>
      </tr>
    </thead>
     
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['lecture_tagid'] ?></td>
      <td><?= $model['tag_title'] ?></td>
      <td><?= $model['listorder'] ?></td>
      <td><?= date('Y-m-d H:i:s',$model['ctime']); ?></td>
      <td>
      	<a name='edit' lecture_tagid='<?= $model['lecture_tagid'] ?>' newsid='<?= $model['newsid'] ?>' href='#' newstype="<?php echo $model['newstype'];?>" >编辑副标题</a>&nbsp;&nbsp;
      	<a name='aedit' lecture_tagid='<?= $model['lecture_tagid'] ?>' newsid='<?= $model['newsid'] ?>' href='#' newstype="<?php echo $model['newstype'];?>" >精讲文章排序</a>&nbsp;&nbsp;
      	<a name='adel' newsid='<?= $model['lecture_tagid'] ?>' href='#'>删除</a>&nbsp;&nbsp;
      </td>
      </tr>
     <?}?>
     <tr class="operate">
	      <td colspan="6"><div class="cuspages right"><?= MyLinkPager::widget(['pagination' => $pages,]); ?></div>    </td>
      </tr>
  </table>
  
  <input type="hidden" id="inputid" value="" />
  <input type="hidden" id="news_id" value="<?php echo isset($models[0]['newsid'])?$models[0]['newsid']:$newsid;?>" />
  <div id="_tips"></div>
<script>
//排序
$("a[name=aedit]").click(function () {
    addTag($(this).attr("lecture_tagid"));
    return false;
});
function addTag(lecture_tagid){
   var content = '/lecture/add_tag_news';
	 content = content + '?lecture_tagid=' + lecture_tagid; 
	var title = '精讲文章排序:'+ lecture_tagid;
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['95%' , '95%'],
        content: content
    }); 
}

//新建副标题
$('#subject').on('click', function(){
	addedit($("#news_id").val(),0);
        return false;
});

//编辑按钮绑定事件
$("a[name=edit]").click(function () {
    addedit($(this).attr("newsid"),$(this).attr('lecture_tagid'));
    return false;
});

//编辑或新增用户页面
function addedit(newsid,lecture_tagid){
	var content = '/lecture/aedit_tag';
	var title = '添加副标题';
	if(lecture_tagid >0){
		content = content + '?newsid=' + newsid+'&lecture_tagid='+lecture_tagid; 
		title = '编辑副标--编号：'+lecture_tagid;
	}else{
            content = content + '?newsid=' + newsid; 
        }
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['95%' , '95%'],
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
            url: "/lecture/del_tag",
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