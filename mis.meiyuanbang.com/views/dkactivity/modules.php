  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>

  <!-- 图片浏览 引入开始-->
  <script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
  <link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
  <!--鼠标控制滚动-->
  <script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
  <!-- 图片浏览 引入结束-->
  
  <table cellspacing="0" cellpadding="0" class="content_list">
      <!--标题  -->
      <thead>
        <tr class="operate">
          <th colspan="2" >
          	共有<?= $pages->totalCount?>条记录
          </th>
          <th colspan="2" style='text-align:right;'>
          	<input type="button" onclick="addModule(1);" id="btnnew" value="新建副文本模块" class="button"/>
            <input type="button" onclick="addModule(2);" id="btnnew" value="新建图片" class="button"/>
            <input type="button" onclick="addModule(3);" id="btnnew" value="新建视频" class="button"/>
             <input type="button" onclick="goback();"  value="返回列表" class="button"/>
          </th>
        </tr>
<style type="text/css">
      td{
        max-width: 200px;
      }
     
      </style>
        <tr class="tb_header">
          <th>模块编号</th>
          <th >标题</th>
          <th >内容</th>
          <th >操作</th>
        </tr>
      </thead>
      <!-- 列表 -->
      <? foreach ($models as $model) { ?>
        <tr class="tb_list">
          <td><?= $model['modulesid'] ?></td>
          <td><?= $model['title'] ?></td>
         <td><?
         switch ($model['type']) {
           case '1'://文本
           ?>
              <?=$model['content'];?>
           <?
             # code...
             break;
           case '2'://图片
           ?>
           图片
           <?
             # code...
             break;
             case '3'://视频
           ?>
            视频
          <?
             # code...
             break;
           default:
             # code...
             break;
         }?></td>
          <td>
            <a name='aedit' onclick='addedit(<?= $model['modulesid'] ?>,<?= $model['type'] ?>)' >编辑</a>
            <a name='adel' onclick='del(<?= $model['modulesid'] ?>)' >删除</a>
          </td>
        </tr>
      <?}?>

     <!-- 分页 -->
     <tr class="operate">
	      <td colspan="3">
  			<div class="cuspages right">
  			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
  			</div>      
	      </td>
      </tr>
  </table>



<!-- 页面操作逻辑  开始-->
<script type="text/javascript">
      function goback(){
        window.location.href="/dkactivity/index";
      }
        //删除能力素材
        function del(modulesid){
            layer.confirm('是否删除？', {
              btn: ['删除','否'] //按钮
            }, function(){
              $.ajax({
                type: "post",
                dataType: "json",
                url: "/dkactivity/delmodel",
                    data: "modulesid=" + modulesid,//要发送的数据                    
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
            }, function(){
             
            });
        }
  //编辑或新增改画活动
  function addedit(modulesid,type){
    var content = '/dkactivity/editmodule';
    var title = '编辑模块';
    if(modulesid >0){
      content = content + '?modulesid=' + modulesid+'&activityid=<?=$activityid;?>&type='+type; 
      title = '编辑模块--编号:'+ modulesid;
    }
    layer.open({
          type: 2,
          title: title,
          maxmin: false,
          shadeClose: false, //点击遮罩关闭层
          area : ['80%' , '90%'],
          content: content
      });
  }
  function addModule(type){
      var content = '/dkactivity/editmodule';
      var title = '添加模块';
      if(type >0){
        content = content + '?type=' + type+'&activityid=<?=$activityid;?>'; 
      }
      layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['80%' , '90%'],
            content: content
        });

  }

</script>
<!-- 页面操作逻辑 结束-->



<!-- 图片浏览 开始-->
<script language="javascript" type="text/javascript"> 
  $(document).ready(function() {
     $("a#example1").fancybox({
      type:'image',
      afterLoad : function() {
          this.title = '图片数：' + (this.index + 1) + '/' + this.group.length + (this.title ? ' - ' + this.title : '');
        },
        loop:false,
      padding: 2,
      helpers : {
          title : {
            type : 'inside'
          }
      }
     });
  });

</script>
<!-- 图片浏览 结束 -->

