  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>    
      <tr class="tb_header">
        <th style="width:25%">头像</th>
        <th style="width:25%">用户编号</th>
        <th style="width:25%">昵称</th>
         <th style="width:25%">选择</th>
      </tr>
    </thead>
      <tr>
         <form name="searchform" action="/studio/teachersel" method="get" > 
             <select id="selectId" name="select">
                 <option value="1" <?php if($models['select']==1){ echo "selected=selected";}?> >昵称</option>
                 <option  value="2" <?php if($models['select']==2){ echo "selected=selected";}?> >电话</option>
             </select>&nbsp;&nbsp;
                <input name="sname" type="text" value="<?php echo $models['sname']?>" class="input-text">
                <input type="submit" name="search" class="button" value="搜索" />
         </form>
      </tr>
      <? foreach ($models['models'] as $model) { ?>
      <tr class="tb_list" name="user">
        <td><img src='<?= $model['avatars']?>' style='height:80px;width:80px;'/> </td>
        <td><?= $model['uid'] ?></td>
        <td><?= $model['sname'] ?></td>
          <td>
              <a style="cursor: pointer" name="tuiguang" id="<?php echo $model['uid']?>">设为推广用户</a>
        </td>
      </tr> 
      <?}?>
      <tr class="operate">
        <th colspan="3" >
          共有<?= $models['pages']->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
       <!-- 分页 -->
     <tr class="operate">
        <td colspan="4">
        <div class="cuspages right">
        <?= MyLinkPager::widget(['pagination' => $models['pages'],]); ?>
        </div>      
        </td>
      </tr>
  </table>  

<script>
    var index = parent.layer.getFrameIndex(window.name);
      //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
        parent.layer.close(index);
    });
    $("#asave").click(function(){
        parent.$("#teacheruid").val($('input:radio:checked').val());
        parent.$(".userinfo").html($('input:radio:checked').data("sname"));
        parent.layer.close(index);
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      parent.layer.close(index);
    });
    
     //编辑按钮绑定事件
    $("a[name=tuiguang]").click(function () {
    	add($(this).attr("id"));
        return false;
    });
    
     //删除 审核
    function add(uid){
      var title;
      if(uid>0){
        title="确定设为推广用户？";
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
            url: "/studio/add",
              data: "uid=" + uid,//要发送的数据
              success: function (data) {
                if (data.errno == 0) {
                 var index = parent.layer.getFrameIndex(window.name);
                // $("#hrefid").click(function(){
                  parent.location.reload();
                  parent.layer.close(index);
                   //})
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