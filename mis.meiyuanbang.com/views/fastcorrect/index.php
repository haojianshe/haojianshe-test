  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<!-- 图片浏览 引入开始-->
<!-- <script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script> -->
<!-- <link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
 --><!--鼠标控制滚动-->
<!-- <script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
 --><!-- 图片浏览 引入结束-->
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="1" >
        	共有<?= $pages->totalCount ?>条记录
        </th>

        <th colspan="5" style='text-align:right;'>
        <a target="_blank" href="<?=Yii::$app->params['msiteurl']?>activity/fastcorrect?v=<?=time()?>"><input type="button" id="pre" value="预览活动" class="button"/></a>
          <input type="button" id="btnnew" value="新建活动" class="button"/>
        </th>
      </tr>
      <style type="text/css">
      td{
        max-width: 200px;
      }
     
      </style>
      <tr class="tb_header">
        <th>活动编号</th>
        <th>活动名称</th>
        <th>开始时间</th>
        <th>结束时间</th>
        <th>创建时间</th>
         <th>操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
      <td><?= $model['fastcorrectid'] ?></td>
      <td><?= $model['activity_name']?>
      <td><?= date("Y-m-d H:i:s",$model['starttime'])  ?></td>
      <td><?= date("Y-m-d H:i:s",$model['endtime'])  ?></td>
      <td><?= date("Y-m-d H:i:s",$model['ctime'])  ?></td>
      <td>
         <a href="javascript:;"  name='aedit' fastcorrectid='<?= $model['fastcorrectid']?>'>编辑</a>           
         <a onclick="del(<?= $model['fastcorrectid'] ?>)">删除</a> 
         
      </td>
    </tr> 
    <?}?>
       <tr class="operate">
         <td colspan="6">&nbsp;
           <div class="cuspages right">
             <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
           </div>      
         </td>
       </tr>
   </table>
   <div id="_tips"></div>
   <script type="text/javascript">       
     //删除活动
    function del(fastcorrectid){
            layer.confirm('是否删除？', {
        btn: ['删除','否'] //按钮
      }, function(){
        $.ajax({
          type: "post",
          dataType: "json",
          url: "/fastcorrect/del",
              data: "fastcorrectid=" + fastcorrectid+"&is_del=1",//要发送的数据
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
   
     //编辑
    $("a[name=aedit]").click(function () {
      addedit($(this).attr("fastcorrectid"));
      return false;
    });
    //进入编辑页面的弹层函数
    function addedit(fastcorrectid){
      var content = '/fastcorrect/edit';
      var title = '编辑活动';
      if(fastcorrectid >0){
        content = content + '?fastcorrectid=' + fastcorrectid; 
        title = '编辑活动';
      }
      layer.open({
        type: 2,
        title: title,
        maxmin: true,
            shadeClose: false, //点击遮罩关闭层
            area : ['800px' , '600px'],
            content: content
          });
      }


      $("#btnnew").click(function(){
          var content = '/fastcorrect/edit';
          layer.open({
            type: 2,
            title: "新建活动",
            maxmin: true,
                shadeClose: false, //点击遮罩关闭层
                area : ['800px' , '600px'],
                content: content
              });
          
      });
</script>


