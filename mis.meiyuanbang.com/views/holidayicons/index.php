  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <!-- 图片浏览 引入开始-->
  <script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
  <link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
  <!--鼠标控制滚动-->
  <script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

  <table cellspacing="0" cellpadding="0" class="content_list">
      <thead>
        <tr class="operate">
          <th colspan="2" >
          	共有<?= $pages->totalCount ?>条记录
          </th>
          <th colspan="7" style="text-align: right;">
             <input type="submit"  name="search" class="button" value="新建节日图标" onclick="addedit(0);">
          </th>
        </tr>
      <style type="text/css">
      </style>
        <tr class="tb_header">
          <th style="width: 100px;">模块编号</th>
          <th style="width: 300px;">底部图</th>
          <th style="width: 100px;">底部字体颜色</th>
      
          <th style="">首页图标</th>
          <th style="">描述</th>
          <th style="width: 200px;">创建时间</th>
          <th style="width: 100px;">操作</th>
        </tr>
      </thead>
      <!-- 列表 -->
      <? foreach ($models as $model) { ?>
        <tr class="tb_list">
          <td><?= $model['iconsid'] ?></td>
          <td>
            <img style="width: 40px;"  src="<?= $model['bottom_nav1_url'] ?>" /> 
            <img style="width: 40px;"  src="<?= $model['bottom_nav2_url'] ?>" />
            <img style="width: 40px;"  src="<?= $model['bottom_nav3_url'] ?>" />
            <img style="width: 40px;"  src="<?= $model['bottom_nav4_url'] ?>" />
            <img style="width: 40px;"  src="<?= $model['bottom_nav5_url'] ?>" />
          </td>
          <td><div style="color: #<?= $model['bottom_nav_color']; ?>">底部</div></td>

          <td>

            <img style="width: 40px;"  src="<?= $model['home_videosubject'] ?>" /> 
            <img style="width: 40px;"  src="<?= $model['home_tweet'] ?>" /> 
            <img style="width: 40px;"  src="<?= $model['home_lecture'] ?>" /> 
            <img style="width: 40px;"  src="<?= $model['home_lesson'] ?>" /> 
            <img style="width: 40px;"  src="<?= $model['home_live'] ?>" /> 
            <img style="width: 40px;"  src="<?= $model['home_book'] ?>" /> 
            <img style="width: 40px;"  src="<?= $model['home_qa'] ?>" /> 
            <img style="width: 40px;"  src="<?= $model['home_activity'] ?>" />
          
          </td>

          <td><?= $model['desc'] ?></td>
          <td><?= date('Y-m-d h:i:s',$model['ctime']); ?></td>
          <td>            
            <?if($model['status']==1){?>
              <a name='aedit' href="javascript:" onclick='update(<?= $model['iconsid'] ?>,3)' >使用</a>
            <?}?>

            <?if($model['status']==3){?>
              <a style="color: red" name='aedit' href="javascript:" onclick='update(<?= $model['iconsid'] ?>,1)' >取消使用</a>
            <?}?>
            <?if($model['status']!=3){?>
              <a name='aedit' href="javascript:" onclick='addedit(<?= $model['iconsid'] ?>)' >编辑</a>
              <a name='aedit' href="javascript:" onclick='update(<?= $model['iconsid'] ?>,2)' >删除</a>
            <?}?>
          </td>
        </tr>
      <?}?>
     <!-- 分页 -->
     <tr class="operate">
	      <td colspan="4">
  			<div class="cuspages right">
  			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
  			</div>      
	      </td>
      </tr>
  </table>

<!-- 页面操作逻辑  开始-->
<script type="text/javascript">
    //编辑
    function addedit(id){
      var content = '/holidayicons/edit';
      var title = '编辑';
      if(id >0){
        content = content + '?id=' + id; 
      }
      layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['700px' , '500px'],
            content: content
        });
    }

    //更改状态
    function update(id,status){
      var content = '/holidayicons/update';
      var title = '编辑';
 
        layer.confirm('确定更改吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: content,
                data: 'id='+id+'&status=' + status,//要发送的数据
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
            //取消
        });
        return false;
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
