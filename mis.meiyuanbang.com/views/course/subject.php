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
        <th colspan="3" style='text-align:right;'>
          <input type="button" onclick="addedit(0);" id="btnnew" value="新建一招" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th>编号</th>
        <th>标题</th>
        <th>封面图</th>
        <th>创建用户</th>
        <th>创建时间</th>
        <th>浏览量</th>
        <!--<th>排序</th>-->
        <th>操作</th>
      </tr>
    </thead>

    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td><?= $model['subjectid'] ?></td>
        <td><?= $model['subject_title'] ?></td>
          <td><img src="<?=$model['subject_pic'] ?>" height="50px" ></td>
        <td><?= $model['username'] ?></td>
          <td><?= date('Y-m-d H:i:s',$model['ctime']); ?></td>
        <td><?= $model['hits'] ?></td>
        <td width="250px">
          <?php
          if($model['status']==3){
          ?>
           <a name="notlistorder" onclick="upadtestatus(<?= $model['subjectid'] ?>,1);" href='javascript:;'><span style="color: red">审核</span></a>&nbsp;&nbsp;
          <?php
          }
          ?>
          <a name='adel'  target="_black"  href="<?=Yii::$app->params['msiteurl']?>videosubject?subjectid=<?= $model['subjectid'] ?>">预览</a>&nbsp;&nbsp;
          <a name='aedit' subjectid='<?= $model['subjectid'] ?>' href='javascript:;'>编辑</a>&nbsp;&nbsp;
          <a name='courss' subjectid='<?= $model['subjectid'] ?>' href='javascript:;'>课程列表</a>&nbsp;&nbsp;
          <a name='upadtestatus' onclick="upadtestatus(<?= $model['subjectid'] ?>,2);" href='javascript:;'>删除</a>&nbsp;&nbsp;
        </td>
      </tr>
    <?}?>
    <tr class="operate">
      <td colspan="7">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>
      </td>
    </tr>
  </table>
<script>
    $("a[name='courss']").click(function(){
       var subjectid= $(this).attr('subjectid');
       window.location.href='/course/curriculum?subjectid='+subjectid;
    });
    //编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
    	addedit($(this).attr("subjectid"));
        return false;
    });
    //编辑或新增
    function addedit(subjectid){
    	var content = '/course/update';
    	var title = '添加';
    	if(subjectid >0){
    		content = content + '?subjectid=' + subjectid; 
    		title = '编辑--编号:'+ subjectid;
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
    //删除 审核
    function upadtestatus(subjectid,status){
      var title;
      if(status==2){
        title="是否删除？";
      }else if(status==1){
        title="确定审核通过？";
      }else if(status==3){
        title="取消审核通过？";
      }
      layer.confirm(
        title, 
        {
          btn: ['是','否']
        },
        function(){
          $.ajax({
            type: "post",
            dataType: "json",
            url: "/course/operation",
              data: "subjectid=" + subjectid+"&status="+status,//要发送的数据
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