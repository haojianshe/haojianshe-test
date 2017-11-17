<?php 
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
    <tr class="operate">
      <th colspan="3" >
        共有<?=$pages->totalCount ?>条记录
      </th>
      <th colspan="3" style='text-align:right;'>
        <a onclick="addedit(0)"  class="button">增加</a>
         <a class="button" href="/course/rec_catalog">返回</a>
      </th>
    </tr>
    <tr class="tb_header">
        <th>编号</th>
        <th>视频标题</th>
        <th>封面</th>
        <th>排序</th>
        <th>创建时间</th>
        <th width="100px;">操作</th>
    </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
    <td><?= $model['courserecid'] ?></td>
    <td><?= $model['title'] ?></td>
    <td><img src="<?= $model['thumb_url'] ?>" height="50px;"></td>
    <td><?= $model['sort_id'] ?></td>
    <td><?= date('Y-m-d',$model['ctime']); ?></td>
    <td>
    	<a name='aedit' courserecid='<?= $model['courserecid'] ?>' href='javascript:;'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
    	<a name='adel' onclick='upadtestatus(<?= $model['courserecid'] ?>);' href='javascript:;'>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
    	addedit($(this).attr("courserecid"));
        return false;
    });
    //编辑或新增
    function addedit(courserecid){
    	var content = '/course/rec_course_edit';
    	var title = '添加';
        content = content + '?recommendid=<?=$recommendid?>'; 
    	if(courserecid >0){
    		content = content + '&courserecid=' + courserecid; 
    		title = '编辑--编号:'+ courserecid;
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
   
    //删除
    function upadtestatus(courserecid){
      layer.confirm('是否删除？', {
          btn: ['删除','否'] //按钮
          }, 
          function(){
                $.ajax({
                  type: "post",
                  dataType: "json",
                  url: "/course/rec_course_del",
                      data: "courserecid=" + courserecid,//要发送的数据
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