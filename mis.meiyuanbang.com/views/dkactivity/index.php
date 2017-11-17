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
          <th colspan="6" >
          	共有<?= $pages->totalCount ?>条记录
          </th>
          <th colspan="2" style='text-align:right;'>
          <a target="_blank" href="<?=Yii::$app->params['msiteurl']?>mactivity/dk/teacher_list"><input type="button" id="btnnew" value="预览活动" class="button"/></a>
          	<input type="button" onclick="addedit(0);" id="btnnew" value="新建活动" class="button"/>
                 <a  href="/reward/prizegame"><input type="button" id="btnnew" value="抽奖管理" class="button"/></a>
          </th>
        </tr>

        <tr class="tb_header">
          <th >活动编号</th>
          <th >标题</th>
          <th >活动老师</th>
          <th >活动图片</th>
          <th >活动开始时间</th>
          <th >活动结束时间</th>
          <th >创建时间</th>
          <th >操作</th>
        </tr>
      </thead>
      <!-- 列表 -->
      <? foreach ($models as $model) { ?>
        <tr class="tb_list">
          <td><?= $model['activityid'] ?></td>
          <td><?= $model['title'] ?></td>
          <td><?= "<img width='40px;' src='".json_decode($model['avatar'])->img->n->url ."' >  ". $model['sname'] ?></td>
          <td>
            <a id="example1" title="<?= $model['title'] ?>" rel="group" href="<?= $model['activity_img'] ?>">
                <img style="height:50px;width:50px; margin-left:3px;margin-top:3px;" name="prew_resource" src="<?= $model['activity_img'] ?>" />
              </a>
          </td>
          <td><?= date('Y-m-d H:i',$model['activity_stime'])  ?></td>
          <td><?= date('Y-m-d H:i',$model['activity_etime'])  ?></td>
          <td><?= date('Y-m-d H:i',$model['ctime'])  ?></td>
          <td>
          <?if($model['status']==3){?>
                 <a style="color:red;" name='adel' onclick='del(<?= $model['activityid'] ?>,1)' >发布</a>
            <?}?>
            <a href='<?= Yii::$app->params['msiteurl'].'mactivity/dk/teacher_detail?activityid='.$model['activityid'] ?>' target='_blank'>预览</a>
            <a name='aedit' onclick='addedit(<?= $model['activityid'] ?>)' >编辑</a>
            <a name='adel' onclick='del(<?= $model['activityid'] ?>,2)' >删除</a>
            <a onclick="manageModule(<?=$model['activityid']?>)" >模块管理</a>
            <a onclick="prize(<?=$model['activityid']?>)" >中奖用户</a>
             <a onclick="submitlist(<?=$model['activityid']?>)" >求批改</a>
          </td>
        </tr>
      <?}?>

     <!-- 分页 -->
     <tr class="operate">
	      <td colspan="8">
  			<div class="cuspages right">
  			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
  			</div>      
	      </td>
      </tr>
  </table>



<!-- 页面操作逻辑  开始-->
<script type="text/javascript">
function prize(activityid){
  var content = '/reward/userlist?activityid='+activityid;
              var title = '中奖用户';
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
                  shadeClose: false, //点击遮罩关闭层
                  area : ['80%' , '80%'],
                  content: content
              });
    return false;
}
function submitlist(activityid){
  var content = '/dkactivity/submitlist?activityid='+activityid;
              var title = '活动批改';
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
                  shadeClose: false, //点击遮罩关闭层
                  area : ['80%' , '80% '],
                  content: content
              });
    return false;
}

//http://yii.meiyuanbang.cn/dkactivity/submitlist?activityid=12

        //删除能力素材
        function del(activityid,status){
          var msg='';
          if(status==2){
              msg="是否删除？";
          }else if(status==1){
              msg="是否发布？";
          }
            layer.confirm(msg, {
              btn: ['确定','否'] //按钮
            }, function(){
              $.ajax({
                type: "post",
                dataType: "json",
                url: "/dkactivity/del",
                    data: "activityid=" + activityid+"&status=" + status,//要发送的数据                    
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
 function manageModule(activityid){

          var content = '/dkactivity/modules?activityid='+activityid;
          window.location.href='/dkactivity/modules?activityid='+activityid;
              /*var title = '模块列表';
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
                  shadeClose: false, //点击遮罩关闭层
                  area : ['700px' , '500px'],
                  content: content
              });
              return false;*/
        }
  //编辑或新增改画活动
  function addedit(activityid){
    var content = '/dkactivity/edit';
    var title = '添加改画活动';
    if(activityid >0){
      content = content + '?activityid=' + activityid; 
      title = '编辑改画活动--编号:'+ activityid;
    }
   layer.open({
          type: 2,
          title: title,
          maxmin: false,
          shadeClose: false, //点击遮罩关闭层
          area : ['60%' , '80%'],
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

