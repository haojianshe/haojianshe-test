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
          <input type="button"  id="fanhui"  value="返回列表" class="button"/>
          <input type="button" onclick="addedit(0);" id="btnnew" value="添加视频" class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th>编号</th>
        <th>课程标题</th>
        <th>封面图</th>
        <th>创建时间</th>
        <th>排序</th>
        <th>操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td><?= $model['courseid'] ?></td>
        <td><?= $model['title'] ?></td>
          <td><img src="<?=$model['thumb_url'] ?>" height="50px" ></td>
          <td><?= date('Y-m-d H:i:s',$model['ctime']); ?></td>
        <td>
     <input type="text" value="<?= $model['listorder'] ?>" name="itemid" size="3" subjectid="<?= $model['subjectid'] ?>" itemid="<?= $model['itemid'] ?>"/>
        </td>
        <td width="250px">
            <a name='upadtestatus'  subjectid="<?= $model['subjectid'] ?>" itemid="<?= $model['itemid'] ?>" style="cursor:pointer">删除</a>&nbsp;&nbsp;
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
    <input type="hidden" value="<?php echo $subjectid;?>" id="subject_id" />
  </table>
<script>
      $("#fanhui").click(function(){
       window.location.href='/course/subject';
    });
    
    $("input[name=itemid]").blur(function () {
        var itemid = $(this).attr("itemid");
        var subjectid = $(this).attr("subjectid");
        var value = $(this).val();
        var type=1;
        layer.confirm('确定要操作吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/course/editem",
                data: "itemid=" + itemid+"&value="+value+"&subjectid="+subjectid+"&type="+type, //要发送的数据                    
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

 //删除
  $("a[name=upadtestatus]").click(function () {
        var itemid = $(this).attr("itemid");
        var subjectid = $(this).attr("subjectid");
        var value = $(this).val();
        var type=2;
        layer.confirm('确定要删除吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/course/editem",
                data: "itemid=" + itemid+"&value="+value+"&subjectid="+subjectid+"&type="+type, //要发送的数据                    
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
   
    //编辑或新增
    function addedit(subjectid){
        var subjectid = $("#subject_id").val();
    	var content = '/course/recommend';
    	var title = '添加推荐';
    	if(subjectid >0){
    		content = content + '?subjectid=' + subjectid; 
    		title = '添加课程';
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