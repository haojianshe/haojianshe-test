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
          <input type="button" id="btnnew" value="新建画室" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th>用户编号</th>
        <th>昵称</th>
        <th>联系人</th>
        <th>联系电话</th>
        <th>资讯电话</th>
        <th>创建人</th>
        <th>操作</th>
      </tr>
    </thead>
    <?php foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td ><?= $model['uid'] ?></td>
        <td><?= $model['sname'] ?></td>
        <td><?= $model['contact_user'] ?></td>
        <td><?= $model['studio_mobile'] ?></td>
        <td><?= $model['studio_tel'] ?></td>
        <td><?= $model['username'] ?></td>
        <td width="400px">
               <?php //if($model['status']==3){?>
            <a  target="_black" href=<?php echo Yii::$app->params['msiteurl'].'studio/drawing/index?studioid='.$model['uid'];?>  style="cursor: pointer">预览</a>&nbsp;&nbsp;
            <a name='edit' uid='<?= $model['uid'] ?>' sname='<?= $model['sname'] ?>'  href='javascript:;' style="cursor: pointer">编辑</a>&nbsp;&nbsp;
            <?php if($model['status']==1){?>
            <a name='adel' uid='<?= $model['uid'] ?>' status='<?= $model['status'] ?>'  style="cursor: pointer">发布</a>&nbsp;&nbsp;
            <?php }else{
                echo "<span style='color:red;'>已发布</span>";
            }?>
           <a  name='advert_list' uid='<?= $model['uid'] ?>'  style="cursor: pointer">广告位</a>&nbsp;&nbsp;
           <a  name='user_list'  uid='<?= $model['uid'] ?>' sname='<?= $model['sname'] ?>'  style="cursor: pointer">个人中心管理</a>&nbsp;&nbsp;
           <a  name='teacher_list' uid='<?= $model['uid'] ?>' style="cursor: pointer">老师管理</a>&nbsp;&nbsp;
           <a  name='aadel' uid='<?= $model['uid'] ?>' status='<?= $model['status'] ?>'  style="cursor: pointer" title='取消身份为不可逆操作,请谨慎操作！'><span style='color:green;'>取消身份</span></a>&nbsp;&nbsp;
        </td>
      </tr>
    <?php }?>
    <tr class="operate">
      <td colspan="7">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>
      </td>
    </tr>
  </table>
<script>
    //广告位
     $("a[name=advert_list]").click(function () {
        advertlist($(this).attr("uid"));
    });
    //编辑按钮绑定事件
    $("a[name=edit]").click(function () {
    	addedit($(this).attr("uid"),$(this).attr("sname"));
        return false;
    });
       //取消身份
    $("a[name=aadel]").click(function () {
    	upadtestatus($(this).attr("uid"),4);
        return false;
    });
      //发布按钮
    $("a[name=adel]").click(function () {
    	upadtestatus($(this).attr("uid"),$(this).attr("status"));
        return false;
    });
    
       //个人中心
    $("a[name=user_list]").click(function () {
       window.location.href = "/studio/userlist?uid="+$(this).attr("uid")+"&sname="+$(this).attr("sname");
    });
    
    
    
    //编辑或新增广告位
    function advertlist(uid) {
        var content = '/studio/advertlist';
        var title = '添加广告';
        if (uid > 0) {
            title = '编辑用户--编号:' + uid;
        }
        content = content + '?uid=' + uid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
    }
    
      //选择老师管理
         $("a[name=teacher_list]").click(function () {
          
            var content = '/studio/teacherlist'; 
            var title = '选择老师';
            content = content + '?uid='+ encodeURI($(this).attr("uid"));
            layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['60%' , '80%'],
            content: content
        });
      });
    
   //选择画室
    $("#btnnew").click(function () {
            var content = '/studio/teachersel';
            var title = '选择老师';
            content = content + '?uid='+ encodeURI($("#teacheruid").val());
            layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['60%' , '80%'],
            content: content
        });
      });
    
    //编辑或新增
    function addedit(uid,sname){
    	var content = '/studio/edit';
    	var title = '添加';
    	if(uid >0){
    		content = content + '?uid=' + uid; 
    		title = "编辑(用户编号："+uid+"   昵称："+sname+")";
    	}
    	layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['40%' , '50%'],
            content: content
      });
    }
    //删除 审核
    function upadtestatus(uid,status){
      var title;
      if(status==1){
        title="确定发布？";
      }else{
        title="确实取消身份吗？";
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
            url: "/studio/del",
              data: "uid=" + uid+"&status="+status,//要发送的数据
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