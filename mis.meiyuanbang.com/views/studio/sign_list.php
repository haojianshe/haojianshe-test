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
          <input type="button" name="btnnew" value="添加报名方式"  class="button"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th>编号</th>
        <th>标题</th>
        <th>原价</th>
         <th>现价</th>
        <th>排序</th>
        <th>创建时间</th>
        <th>操作</th>
      </tr>
    </thead>
    <?php foreach ($models['models'] as $model) { ?>
      <tr class="tb_list">
        <td ><?= $model['enrollid'] ?></td>
        <td><?= $model['enroll_title'] ?></td>
        <td><?= $model['original_price'] ?></td>
        <td><?= $model['discount_price'] ?></td>
          <td><?= $model['listorder'] ?></td>
        <td><?= date("Y-m-d H:i:s",$model['ctime']) ?></td>
        <td width="400px">
            <a name='edit' classtypeid='<?=$model['classtypeid']?>' enrollid='<?=$model['enrollid']?>' uid='<?= $model['uid'] ?>'   href='javascript:;' style="cursor: pointer">编辑</a>&nbsp;&nbsp;
           <a  name='singdel' enrollid='<?= $model['enrollid'] ?>' classtypeid='<?=$model['classtypeid']?>' uid='<?= $model['uid'] ?>'  style="cursor: pointer">删除</a>&nbsp;&nbsp;
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
      <input type="hidden" value="<?=$models['classtypeid']?>" id="classtypeid" />
  </table>
<script>
    //班型列表js start
     //返回列表
       function goback() {
        window.history.go(-1);
     }
     
     //添加/编辑 班型
     $("input[name=btnnew]").click(function () {
         classEdit($("#uid").val(),$("#classtypeid").val(),0);
    });
     $("a[name=edit]").click(function () {
         classEdit($(this).attr("uid"),$(this).attr("classtypeid"),$(this).attr("enrollid"));
    });
 
    
     //编辑或新增广告位
    function classEdit(uid,classtypeid,t) {
        var content = '/studio/signedit';
        var title = '添加报名方式';
        if (t > 0) {
            title = '编辑报名方式--编号:' + t;
        }
        content = content + '?classtypeid=' + classtypeid+"&uid="+uid+"&t="+t;
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
    $("a[name=singdel]").click(function () {
    	delsign($(this).attr("enrollid"),$(this).attr("classtypeid"),$(this).attr("uid"));
        return false;
    });
  //发布按钮
    function delsign(enrollid,classtypeid,uid){
      var title;
      if(enrollid>0){
        title="确定删除？";
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
            url: "/studio/delsign",
              data: "enrollid=" + enrollid+"&classtypeid="+classtypeid+"&uid="+uid,//要发送的数据
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