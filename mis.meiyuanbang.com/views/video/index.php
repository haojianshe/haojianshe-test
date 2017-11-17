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
              <form name="searchform" action="/video/index" method="get" > 
                类型:
                    <select name="video_type" id="video_type">
                      <option value="" key="">全部</option>
                      <option value="-1" key="" <?if(intval($video_type)==-1){echo 'selected';}?>>通用</option>
                      <option value="1" key=""  <?if(intval($video_type)==1){echo 'selected';}?> >直播</option>
                      <option value="2" key="" <?if(intval($video_type)==2){echo 'selected';}?> >课程</option>  
                    </select> &nbsp;&nbsp;
                    标题：<input type="text" value="<?php echo $desc?>" name="desc" />
                    <input type="submit" name="search" class="button" value="搜索" />
                    <input type="button"  class="button" value="新建视频" onclick="addedit(0);">
             </form>
          </th>
        </tr>
      <style type="text/css">
      </style>
        <tr class="tb_header">
          <th style="width: 100px;">视频编号</th>
          <th style="width: 100px;">一级分类</th>
          <th style="width: 100px;">二级分类</th>
          <th style="width: 100px;">类型</th>
          <th style="">封面图</th>
          <th style="padding: 5px;">描述</th>
          <th style="padding: 5px;">mp4</th>
          <th style="padding: 5px;">m3u8</th>
          <th style="width: 100px;">操作</th>
        </tr>
      </thead>
      <!-- 列表 -->
      <? foreach ($models as $model) { ?>
        <tr class="tb_list">
          <td><?= $model['videoid'] ?></td>
          <td><?= $model['f_catalog'] ?></td>
          <td><?= $model['s_catalog'] ?></td>
          <td><?switch (intval($model['video_type'])) {
            case 1:
              echo "直播";
              break;
            case 2:
              echo "课程";
              break;
            default:
               echo "通用";
              break;
          }?></td>
          <td><a id="example1"  title="<?= $model['f_catalog'] ?>  <?= $model['s_catalog'] ?>  <?= $model['desc'] ?>" href="<?= $model['coverpic'] ?>" rel="group"><img src="<?= $model['coverpic'] ?>" style="height: 50px;padding: 3px;" /></a></td>
          <td><?= $model['desc'] ?></td>
          <td><?if($model['sourceurl']){echo '已上传';}else{echo '未上传';}  ?></td>
          <td><?if($model['m3u8url']){echo '已生成';}else{echo '未生成';}  ?></td>
          <td>
           <?if($model['filename']) {?>
            <a name='aedit' href="javascript:" onclick='addedit(<?= $model['videoid'] ?>)' >编辑</a>
            <?}else{?>
            <a name='aedit' href="javascript:"  style="color:red"  onclick='addedit(<?= $model['videoid'] ?>)' >添加视频</a>
          <?}?>


            <?if($model['sourceurl'] && $model['m3u8url']) {?>
            <a name='aedit' href="javascript:" style="color:green" onclick='preview("<?= $model['sourceurl'] ?>")' >预览</a>
            <?}else{?>
           <!--  <a name='aedit' style="color:red" >不可用</a> -->
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
   //iframe层-多媒体
   function preview(videourl){
      layer.open({
        type: 2,
        title: false,
        area: ['630px', '360px'],
        shade: 0.8,
        closeBtn: 1,
        shadeClose: true,
        content: videourl
      });
   }


    //编辑
    function addedit(id){
      var content = '/video/edit';
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
