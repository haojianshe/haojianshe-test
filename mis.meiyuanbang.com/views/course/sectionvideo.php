<?php 
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
    <tr class="operate">
      <th colspan="6" >
        共有<?= $pages->totalCount ?>条记录
      </th>
      <th colspan="2" style='text-align:right;'>
        <input type="button" onclick="addedit(0);" id="btnnew" value="新建" class="button"/>
      </th>
    </tr>
    <tr class="tb_header">
        <th>编号</th>
        <th>标题</th>
        <th>节序号</th>
        <th>视频编号</th>
        <th>现价</th>
        <th>原价</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
    <td><?= $model['coursevideoid'] ?></td>
    <td width="200px;"><?= $model['title'] ?></td>
    <td ><?= $model['section_video_num'] ?></td>
    <td><?= $model['videoid'] ?></td>
    <td ><?= $model['sale_price'] ?></td>
    <td ><?= $model['price'] ?></td>
    <td width="200px;"><?= date('Y-m-d',$model['ctime']); ?></td>
    <td width="100px;">
    	<a name='aedit' coursevideoid='<?= $model['coursevideoid'] ?>' href='javascript:;'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
    	<a name='adel' onclick='upadtestatus(<?= $model['coursevideoid'] ?>)' href='javascript:;'>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
<script>
    
    //编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
    	addedit($(this).attr("coursevideoid"));
        return false;
    });
    //编辑或新增
    function addedit(coursevideoid){
    	var content = '/course/section_video_edit';
    	var title = '添加';
        content = content + '?courseid=<?=$courseid?>&sectionid=<?=$sectionid?>'; 
    	if(coursevideoid >0){
    		content = content + '&coursevideoid=' + coursevideoid; 
    		title = '编辑--编号:'+ coursevideoid;
    	}
    	layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['90%' , '80%'],
            content: content
      });
    }
    //删除
    function upadtestatus(coursevideoid){
      layer.confirm('是否删除？', {
          btn: ['删除','否'] //按钮
          }, 
          function(){
                $.ajax({
                  type: "post",
                  dataType: "json",
                  url: "/course/section_video_del",
                      data: "coursevideoid=" + coursevideoid,//要发送的数据
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