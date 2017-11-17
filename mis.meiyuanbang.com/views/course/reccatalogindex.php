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
        <input type="button" onclick="addedit(0);" id="btnnew" value="新建" class="button"/>
      </th>
    </tr>
    <tr class="tb_header">
        <th>编号</th>
       
        <th>一级分类</th>
        <th>二级分类</th>
        <th>排序编号</th>
        <th>创建时间</th>
        <th width="200px;">操作</th>
    </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
    <td><?= $model['recommendid'] ?></td>
    <td><?= $model['f_catalog'] ?></td>
    <td><?= $model['s_catalog'] ?></td>
    <td><?= $model['sort_id'] ?></td>
    <td><?= date('Y-m-d',$model['ctime']); ?></td>
    <td>
        <a name='aedit'   recommendid='<?= $model['recommendid'] ?>' href="javascript:return ;">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a    href="/course/rec_course?recommendid=<?= $model['recommendid'] ?>">课程列表</a>&nbsp;&nbsp;&nbsp;&nbsp;

    	<a name='adel' onclick='upadtestatus(<?= $model['recommendid'] ?>);' href='javascript:;'>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
    	addedit($(this).attr("recommendid"));
        return false;
    });
    //编辑或新增
    function addedit(recommendid){
    	var content = '/course/rec_catalog_edit';
    	var title = '添加';
    	if(recommendid >0){
    		content = content + '?recommendid=' + recommendid; 
    		title = '编辑--编号:'+ recommendid;
    	}
    	layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['500px' , '300px'],
            content: content
      });
    }


    //删除
    function upadtestatus(recommendid){
      layer.confirm('是否删除？', {
          btn: ['删除','否'] //按钮
          }, 
          function(){
                $.ajax({
                  type: "post",
                  dataType: "json",
                  url: "/course/rec_catalog_del",
                      data: "recommendid=" + recommendid,//要发送的数据
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