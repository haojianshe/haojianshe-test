<?php 
use common\widgets\MyLinkPager;
use common\service\dict\StudioDictDataService;
#getBookMainType
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="2" >
            个人中心管理（用户编号：<?php echo $uid;?>   昵称：<span id="snameid"><?php echo $sname;?></span>）
        </th>
        <th colspan="2" style='text-align:right;'>
            <input type="button" onclick="addModule(2);" id="btnnew" value="添加页面" class="button"/>
             <input type="button" onclick="goback();"  value="返回列表" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th>页面编号</th>
        <th>名称</th>
        <th>排序字段</th>
        <th>操作</th>
      </tr>
    </thead>
    <?php foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td ><?= $model['menuid'] ?></td>
        
        <td><?php
                foreach (StudioDictDataService::getBookMainType() as $key=>$val){
                    if($model['menuid']==$key){
                        echo $val;
                    }
                }
         ?></td>
            <td><input type="text" value="<?= $model['listorder'] ?>" name="listorder" size="3" menuid="<?php echo $model['menuid'] ?>" uid="<?php echo $model['uid'] ?>"/>  </td>
            <td width="400px">
             <a name='listorderdel' menuid="<?php echo $model['menuid'] ?>" uid="<?php echo $model['uid'] ?>"  href='javascript:;' style="cursor: pointer">删除</a>&nbsp;&nbsp;
             <?php
             if($model['menuid']==1){
             ?>
              <a  name='class_type' studiomenuid='<?= $model['studiomenuid'] ?>'  uid="<?php echo $model['uid'] ?>"  style="cursor: pointer">班型列表</a>&nbsp;&nbsp;
             <?php
             }elseif ($model['menuid']==3) {
             ?>
              <a  name='workers' uid='<?= $model['uid'] ?>' studiomenuid='<?= $model['studiomenuid'] ?>'  style="cursor: pointer">作品管理</a>&nbsp;&nbsp;
          <?php
         }elseif ($model['menuid']==2) {
             ?>
            <a  name='editcontent'  uid='<?= $model['uid'] ?>'  style="cursor: pointer">编辑简介</a>&nbsp;&nbsp;
              <a  name='address' uid='<?= $model['uid'] ?>'   style="cursor: pointer">地址管理</a>&nbsp;&nbsp;
          <?php
         }elseif ($model['menuid']>4) {
             ?>
            <a name='pageMange' studiomenuid='<?= $model['studiomenuid'] ?>' uid='<?= $model['uid'] ?>' menuid="<?php echo $model['menuid'] ?>"   style="cursor: pointer">页面管理</a>&nbsp;&nbsp;
          <?php
         }
         ?>
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
<input type="hidden" id="u_id" value="<?php echo $uid;?>" />
<input type="hidden" id="sname" value="<?php echo $sname;?>" />
<script>
     function goback() {
        window.location.href = "/studio/index/";
     }
     //班型列表
      $("a[name=class_type]").click(function () {
       window.location.href = "/studio/class_list?studiomenuid="+$(this).attr("studiomenuid")+"&uid="+$("#u_id").val();
      });
       //地址管理
        $("a[name=address]").click(function () {
         address($(this).attr("uid"),$("#sname").val());
       });
        function address(uid,sname) {
        var content = '/studio/editaddress_list';
        var  title = '地址管理（用户编号：'+uid+'   昵称：'+sname+'）';
        content = content + '?uid=' + uid+'&sname='+sname;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
        }
         //作品管理
        $("a[name=workers]").click(function () {
         workers($(this).attr("uid"),$(this).attr("studiomenuid"),$("#sname").val()); 
       });
        function workers(uid,studiomenuid,sname) {
        var content = '/studio/opuslist';
        var  title = '地址管理（用户编号：'+uid+'   昵称：'+sname+'）';
        content = content + '?uid=' + uid+'&sname='+sname+"&studiomenuid="+studiomenuid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
        }
        
        
        
       //编辑简介
       $("a[name=editcontent]").click(function () {
         editcontent($(this).attr("uid"),$("#sname").val());
       });
        function editcontent(uid,sname) {
            var content = '/studio/editcontent';
            var  title = "编辑简介（用户编号："+uid+"   昵称："+sname+"）";
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
       
       
       //删除
        $("input[name=listorder]").blur(function () {
        var menuid = $(this).attr("menuid");
        var uid = $(this).attr("uid");
        var value = $(this).val();
        layer.confirm('确定要操作吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/studio/listorder",
                data: "menuid=" + menuid+"&value="+value+"&uid="+uid, //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("访问出错", {icon: 2});
                }
            });
        }, function () {
            //取消
        });
        return false;
    });
    
     //排序字段
      $("a[name=listorderdel]").click(function () {
        var menuid = $(this).attr("menuid");
        var uid = $(this).attr("uid");
        var value = '1.1';
        layer.confirm('确定要操作吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/studio/listorder",
                data: "menuid=" + menuid+"&value="+value+"&uid="+uid, //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("访问出错", {icon: 2});
                }
            });
        }, function () {
            //取消
        });
        return false;
    });
    
     //添加页面
    $("#btnnew").click(function () {
            var uid = $("#u_id").val();
           if(uid==''){
                layer.msg('当前无用户',{icon: 2});
           }
            var content = '/studio/addpage';
            var title = '添加页面';
            content = content + '?uid='+ encodeURI($("#u_id").val());
            layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['60%' , '80%'],
            content: content
        });
      });
    
    //页面管理
     $("a[name=pageMange]").click(function () {
        menumanage($(this).attr("studiomenuid"),$(this).attr("uid"),$(this).attr("menuid"));
    });
    //页面管理
    function menumanage(studiomenuid,uid,menuid) {
        var sname = $("#sname").val();
        var content = '/studio/menumanage';
        var title = '往期成绩-页面管理（用户编号：'+uid+'   昵称：'+sname+'）';
        content = content + '?studiomenuid=' + studiomenuid+"&uid="+uid+"&menuid="+menuid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '90%'],
            content: content
        });
    }
    
    
    
    
    
//    //编辑按钮绑定事件
//    $("a[name=edit]").click(function () {
//    	addedit($(this).attr("uid"),$(this).attr("sname"));
//        return false;
//    });
//       //取消身份
//    $("a[name=aadel]").click(function () {
//    	upadtestatus($(this).attr("uid"),4);
//        return false;
//    });
//      //发布按钮
//    $("a[name=adel]").click(function () {
//    	upadtestatus($(this).attr("uid"),$(this).attr("status"));
//        return false;
//    });
//    
//       //个人中心
//    $("a[name=user_list]").click(function () {
//       window.location.href = "/studio/userlist";
//    });
//    
  
//    
//    
//  
//    
//    //编辑或新增
//    function addedit(uid,sname){
//    	var content = '/studio/edit';
//    	var title = '添加';
//    	if(uid >0){
//    		content = content + '?uid=' + uid; 
//    		title = "编辑(用户编号："+uid+"   昵称："+sname+")";
//    	}
//    	layer.open({
//            type: 2,
//            title: title,
//            maxmin: false,
//            shadeClose: false, //点击遮罩关闭层
//            area : ['40%' , '50%'],
//            content: content
//      });
//    }
//    //删除 审核
//    function upadtestatus(uid,status){
//      var title;
//      if(status==1){
//        title="确定发布？";
//      }else{
//        title="确实取消身份吗？";
//      }
//      layer.confirm(
//        title, 
//        {
//          btn: ['是','否']
//        },
//        function(){
//          $.ajax({
//            type: "post",
//            dataType: "json",
//            url: "/studio/del",
//              data: "uid=" + uid+"&status="+status,//要发送的数据
//              success: function (data) {
//                if (data.errno == 0) {
//                  window.location.reload();
//                }
//                else {
//                  layer.msg(data.msg,{icon: 2});
//                }
//              },
//              error: function (XMLHttpRequest, textStatus, errorThrown) {
//                layer.msg("访问出错",{icon: 2});
//              }
//          });
//        });
//    }
</script>