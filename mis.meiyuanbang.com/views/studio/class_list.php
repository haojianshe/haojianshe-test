<?php 
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="6" >
          共有<?php echo $models['pages']->totalCount;?>条记录
        </th>
        <th colspan="2" style='text-align:right;'>
          <input type="button" name="btnnew1" value="添加班型"  class="button"/>
          <input type="button" onclick="goback();"  value="返回列表" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th>班型编号</th>
        <th>标题</th>
        <th>排序字段</th>
        <th>预计招生人数</th>
        <th>创建时间</th>
        <th>创建人</th>
        <th>操作</th>
      </tr>
    </thead>
    <?php foreach ($models['models'] as $model) { ?>
      <tr class="tb_list">
        <td ><?= $model['classtypeid'] ?></td>
        <td><?= $model['classtype_title'] ?></td>
        <td><?= $model['listorder'] ?></td>
        <td><?= $model['classtype_sum'] ?></td>
        <td><?= date("Y-m-d H:i:s",$model['ctime']) ?></td>
        <td><?= $model['username'] ?></td>
        <td width="400px">
            <a href=<?php echo Yii::$app->params['msiteurl'].'/studio/drawing/entry?studioid='.$model['uid'].'&classtypeid='.$model['classtypeid'];?> target="_black" style="cursor: pointer">预览</a>&nbsp;&nbsp;
            <a name='edit' classtypeid='<?=$model['classtypeid']?>' uid='<?= $model['uid'] ?>'   href='javascript:;' style="cursor: pointer">编辑</a>&nbsp;&nbsp;
            <?php if($model['status']==1){?>
            <a name='adel' classtypeid='<?= $model['classtypeid'] ?>' uid='<?= $model['uid'] ?>'  style="cursor: pointer">发布</a>&nbsp;&nbsp;
            <?php }else{
               ?>
            <a name='removedel'  classtypeid='<?= $model['classtypeid'] ?>' uid='<?= $model['uid'] ?>'  style="cursor: pointer;color:red;">取消发布</a>&nbsp;&nbsp;
            
             <?php
            }?>
           <a  name='class_type_list' uid='<?= $model['uid'] ?>' classtypeid='<?= $model['classtypeid'] ?>'  style="cursor: pointer">报名方式</a>&nbsp;&nbsp;
           <a  name='singdetail' uid='<?= $model['uid'] ?>' classtypeid='<?=$model['classtypeid']?>'   style="cursor: pointer">报名详情</a>&nbsp;&nbsp;
        </td>
      </tr>
    <?php }?>
    <tr class="operate">
      <td colspan="7">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $models['pages'],]); ?>
        </div>
      </td>
    </tr>
      <input type="hidden" value="<?=$models['uid']?>" id="uid" />
  </table>
<script>
     //返回列表
       function goback() {
        //window.location.href = "/studio/index/";
        window.history.go(-1);
     }
     
     //添加/编辑 班型
     $("input[name=btnnew1]").click(function () {
         classEdit(0,$("#uid").val());
    });
     $("a[name=edit]").click(function () {
         classEdit($(this).attr("classtypeid"),$(this).attr("uid"));
    });
    
 
    
     //编辑或新增广告位
    function classEdit(classtypeid,uid) {
        var content = '/studio/class_edit';
        var title = '添加广告';
        if (classtypeid > 0) {
            title = '编辑用户--编号:' + classtypeid;
        }
        content = content + '?classtypeid=' + classtypeid+"&uid="+uid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
    }
    
      //报名方式
    $("a[name=class_type_list]").click(function () {
         classTypeList($(this).attr("classtypeid"),$(this).attr("uid"));
    });
       //报名方式列表
    function classTypeList(classtypeid,uid) {
        var content = '/studio/signlist';
        var title = '报名方式';
        content = content + '?classtypeid=' + classtypeid+"&uid="+uid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
    }
  
     //报名详情  
    $("a[name=singdetail]").click(function () {
        signdetail($(this).attr("classtypeid"),$(this).attr("uid"));
    });
    //报名方式列表
    function signdetail(classtypeid,uid) {
        var content = '/studio/sign_detail';
        var title = '报名详情';
        content = content + '?classtypeid=' + classtypeid+"&uid="+uid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
    }
  
  
  
      //发布按钮
    $("a[name=adel]").click(function () {
    	updateclass($(this).attr("classtypeid"),$(this).attr("uid"),1);
        return false;
    });
        //发布按钮
    $("a[name=removedel]").click(function () {
    	updateclass($(this).attr("classtypeid"),$(this).attr("uid"),2);
        return false;
    });
    
    
  //发布按钮
    function updateclass(classtypeid,uid,i){
      var title;
      if(classtypeid>0){
       if(i==1){
            title="确定发布？";
         }else{
            title="确定取消发布？";
        }
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
            url: "/studio/updateclass",
              data: "classtypeid=" + classtypeid+"&uid="+uid+"&type="+i,//要发送的数据
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