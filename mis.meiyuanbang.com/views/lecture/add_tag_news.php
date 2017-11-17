  <?php
  use common\widgets\MyLinkPager;
  use common\service\dict\BookDictDataService;
  use common\models\myb\Resource;
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
        <th colspan="3" >共有<?=$pages->totalCount;?>条记录</th>
        <th colspan="7" style='text-align:right;'>
               <div id="searchid">
              
            </div>
        </th>
      </tr>
      <tr class="tb_header">
        <th >精讲编号</th>
        <th >标题</th>
        <th >图片</th>
        <th >排序</th>
        <!--<th >操作</th>-->
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['newsid'] ?></td>
      <td><?= $model['title'] ?></td>
      <td><?php 
             if(strpos($model['thumb'],',') ===false){
               $res = Resource::find()->select('img')->where(['rid'=>$model['thumb']])->asArray()->one();
               echo '<a id="example1"  rel="group" href="'.$res['img'].'"><img id="img'.$model['thumb'].'" src="'.$res['img'].'" style="height:100px;width:100px;"></a>';
            }else{
                $imgRes = explode(',', $model['thumb']);
                foreach($imgRes as $v){
                     $img = Resource::find()->select('img')->where(['rid'=>$v])->asArray()->one();
                     #echo '<a id="example1"  rel="group" href="'.$res['img'].'">';
                     #echo '<img id="img'.$v.'" src="'.$img['img'].'" style="height:100px;width:100px;">';
                       echo '<a id="example1"  rel="group" href="'.$res['img'].'"><img id="img'.$model['thumb'].'" src="'.$img['img'].'" style="height:100px;width:100px;"></a>';
                }
            }
      ?></td>
       <td><input type="text" value="<?php echo $model['listorder']; ?>" name="tag_news_id" size="3" tag_news_id="<?php echo $model['tag_news_id'] ?>"/>  </td>
      <td>
        <!--<a name='del' newsid='<?//= $model['newsid'] ?>' tag_news_id="<?//= $model['tag_news_id'] ?>" href='javascript:;' style="color:green">删除</a>&nbsp;&nbsp;-->
      </td>
      </tr>
     <?}?>
     <tr class="operate">
	      <td colspan="6">
			<div class="cuspages right">
			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
			</div>      
	      </td>
      </tr>
  </table>
  
  <input type="hidden" id="inputid" value="" />
  <div id="_tips"></div>
<script>

//审核按钮绑定事件
$("a[name=del]").click(function () {
    var tag_news_id = $(this).attr("tag_news_id");
    var value =2;
    var type =1;
    layer.confirm('是否确定删除？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lecture/sort",
            data: "tag_news_id=" + tag_news_id+"&value="+value+"&type="+type,//要发送的数据                    
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
});


//审核按钮绑定事件
$("input[name=tag_news_id]").blur(function () {
    var tag_news_id = $(this).attr("tag_news_id");
    var value = $(this).val();
    var type =2;
    if(value==0){
           return;
    }
    layer.confirm('是否确定操作？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lecture/sort",
            data: "tag_news_id=" + tag_news_id+"&value="+value+"&type="+type,//要发送的数据                    
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
});

//删除按钮绑定事件
$("a[name=adel]").click(function () {
	var newsid = $(this).attr("newsid");
    layer.confirm('删除后将不可恢复，确定删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lecture/del",
            data: "newsid=" + newsid,//要发送的数据                    
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
});



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