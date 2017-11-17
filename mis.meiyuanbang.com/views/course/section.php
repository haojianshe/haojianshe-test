<?php 
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
          <th colspan="3" >
            课程编号:<?=$courseid?>&nbsp;|&nbsp; 共有<?= $pages->totalCount ?>条记录
          </th>
          <th colspan="2" style='text-align:right;'>
            <a class="button" onclick="addedit(0);">新建</a>
            <a class="button" href="/course/index">返回</a>
          </th>
        </tr>
        <tr class="tb_header">
            <th>编号</th>
            <th>标题</th>
            <th>章序号</th>
            <th>创建时间</th>
            <th width="200px">操作</th>
        </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
    <td><?= $model['sectionid'] ?></td>
    <td><?= $model['title'] ?></td>
    <td><?= $model['section_num'] ?></td>
    <td><?= date('Y-m-d',$model['ctime']); ?></td>
    <td>
    	<a name='aedit' sectionid='<?= $model['sectionid'] ?>' href='javascript:;'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
      <a  onclick="videolist(<?= $model['sectionid'] ?>)" href='javascript:;'>视频列表</a>&nbsp;&nbsp;&nbsp;&nbsp;
    	<a name='adel' onclick='upadtestatus(<?= $model['sectionid'] ?>)' href='javascript:;'>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
    </tr>
    <?}?>
    <tr class="operate">
      <td colspan="5">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>      
      </td>
    </tr>
  </table>
<script>
    //新建按钮绑定事件
    $('#btnnew').on('click', function(){
    	addedit(0);
    });
    //编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
    	addedit($(this).attr("sectionid"));
        return false;
    });
    //编辑或新增
    function addedit(sectionid){
    	var content = '/course/section_edit';
    	var title = '添加';
        content = content + '?courseid=<?=$courseid?>'; 
    	if(sectionid >0){
    		content = content + '&sectionid=' + sectionid; 
    		title = '编辑--编号:'+ sectionid;
    	}
    	layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['700px' , '300px'],
            content: content
      });
    }

    //视频列表
    function videolist(sectionid){
      var content = '/course/section_video?sectionid='+sectionid+"&courseid=<?=$courseid?>";
      var title = '视频列表';
      layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['80%' , '90%'],
            content: content
      });
    }


    //删除
    function upadtestatus(sectionid){
      layer.confirm('是否删除？', {
          btn: ['删除','否'] //按钮
          }, 
          function(){
                $.ajax({
                  type: "post",
                  dataType: "json",
                  url: "/course/section_del",
                      data: "sectionid=" + sectionid,//要发送的数据
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