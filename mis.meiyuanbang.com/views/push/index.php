
  <?php
  use common\widgets\MyLinkPager;
  ?>

<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<script type="text/javascript" src="/static/js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/static/js/layer/layer.js"></script>   
<script type="text/javascript" src="/static/js/layer/extend/layer.ext.js"></script>   
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list" >
    <thead>  
      <tr class="operate">
              
        <form name="searchform" action="/push/index" method="get" >
        <th colspan="3" style="text-align:left;padding-left: 10px;" > 
         共有<?= $pages->totalCount ?>条记录
         </th><th colspan="7" style="text-align:right;padding-right: 10px;">
        标题：<input name="title" type="text" value="" class="input-text" />
        <select name='device_open_detail'>
          <option value="">推送类型</option>
          <option value="wap">H5页面</option>
          <option value="lecture">文章</option>
          <option value="lesson">考点</option>
          <option value="activities">活动</option>
          <option value="home">个人主页</option>
          <option value="tweet">作品主页</option>
        </select>
        <input type="submit" name="search" class="button button-primary button-tiny" value="搜索" />
          </form>
        <input type="button" id="get_token" class="button button-primary button-tiny" value="获取用户设备号" />

        </th>
</tr>
         <tr class="operate">
        <th colspan="10" style='text-align:right;padding-right: 10px'> 
          <!-- active -->
        <a name='add_button'  data-url="/push/editwap" class='button button-primary button-tiny' role='button'>推送页面H5</a>
        <a name='add_button' data-url='/push/editlecture' class='button button-primary button-tiny' role='button'>推送文章</a>
        <a name='add_button' data-url='/push/editlesson' class='button button-primary button-tiny' role='button'>推送考点</a>
        <a name='add_button'  data-url="/push/editactivites" class="button button-primary button-tiny" role='button'>推送活动</a>
        <a name='add_button'  data-url="/push/edithome" class="button button-primary button-tiny" role='button'>推送个人主页</a>
        <a name='add_button'  data-url="/push/edittweet" class="button button-primary button-tiny" role='button'>推送作品主页</a>
        <a name='add_button'  data-url="/push/editsearch" class="button button-primary button-tiny" role='button'>推送搜索页</a>   
         <a name='add_button'  data-url="/push/editlive" class="button button-primary button-tiny" role='button'>推送直播页</a> 
          <a name='add_button'  data-url="/push/editcourse" class="button button-primary button-tiny" role='button'>推送课程页</a>              
        </th>
      </tr>
      <style type="text/css">
        td{
          max-width: 300px;
        }
      </style>
      <tr class="tb_header">      
        <th width="80px;">编号</th>
         <th  width="100px;">推送类型</th>
        <th >标题</th>
        <th >内容</th>
        <th width="200px;">详情</th>       
        <th width="80px;">发送人群</th>
        <th width="80px;">发送设备</th>
        <th width="150px;">发送时间</th>
        <th width="100px;">状态</th>   
        <th width="100px;">操作</th>    
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['id']?></td>
       <td >
      <? switch ($model['device_open_detail']) {
         case 'wap':
           echo 'H5页面';
           break;
         case 'activities':
           echo '活动';
           break;
         case 'home':
           echo '个人主页';
           break;
         case 'lecture':
           echo '文章';
           break;
        case 'tweet':
           echo '帖子';
           break;
        case 'lesson':
           echo '考点';
           break;
        case 'search':
           echo '搜索';
        case 'live':
           echo '直播';
        case 'course':
           echo '课程';
           break;
         default:
           break;
        }?>
      </td>
      <td><?= $model['title'] ?></td>
      <td><?= $model['content'] ?></td>
      <td >
      <? switch ($model['device_open_detail']) {
         case 'wap':
           echo "<a href=".urldecode($model['url'])." target='_blank'>页面连接</a>";
           break;
         case 'activities':
           echo "<a href=".urldecode($model['url'])." target='_blank'>活动连接</a>".'</br>活动编号'.$model['activityid'];
           break;
         case 'home':
           echo '个人编号:'.$model['uid'];
           break;
         case 'lecture':
           echo "<a href=".urldecode($model['url'])." target='_blank'>文章连接</a>";
           break;
        case 'tweet':
           echo '帖子编号:'.$model['tid'];
           break;
        case 'lesson':
           echo '考点编号:'.$model['lessonid'];
           break;
        case 'search':
           echo '分类:'.$model['wd'].'</br>标签:'.$model['tags'];
           break;
         default:
           # code...
           break;
        }?>
      </td>
      <td>
      <? switch ($model['push_person']) {
         case '1':
          echo '群发';
           break;
         case '2':
           echo '个人';
           break;
         case '3':
           echo '标签';
           break;
         default:
           # code...
           break;
        }?>
      </td>

       <td>
      <? switch ($model['push_device']) {
         case '1':
           echo 'android';
           break;
         case '2':
           echo 'ios';
           break;
         case '3':
           echo '所有设备';
           break;
         default:
           # code...
           break;
        }?>
      </td>
      <td><? 
        if(!empty($model['send_time'])){
            echo date("Y-m-d H:i:s",$model['send_time']); 
        }else{ 
            echo '实时发送';
        } 
      ?></td>
       <td>
       <? switch ($model['state']) {
         case '0':
           if ($model['send_time'] >time()){
              echo '<span style="color:red;">未发送</span>';
            }else{
              echo '<span style="color:green;">已发送</span>';
            }           
           break;
         case '1':
           echo '已发送';
           break;
           case '2':
           echo '已取消';
           break;
         default:
           break;
        }?>
       </td>
      <td>
      <? if ($model['send_time'] >time() && $model['state']==0) {
       ?>
        <a href="#" name="cancelpush" data-params="<?=$model['android_push_id']?>,<?=$model['ios_push_id']?>" data-id="<?=$model['id']?>"> 取消发送</a>
      
        <?}else{?>
            无
        <?}?>
      </td>
      </tr>
     <? }?>
          <tr class="operate">
        <td colspan="10">
      <div class="cuspages right">
      <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
      </div>      
        </td>
      </tr>
  </table>  
<div id="_tips"></div>
<script type="text/javascript">
  $('#btnadd').on('click', function(){
  var content = '/push/edit';
  var title = '添加认证老师';
  layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['800px' , '520px'],
        content: content
    });
});
  $('[name="add_button"]').on('click',function(){
  var content = this.getAttribute('data-url');
  var title = '添加'+this.text;
  layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['800px' , '500px'],
        content: content
    });
  });

  $('[name="cancelpush"]').on('click',function(){
    var params = this.getAttribute('data-params');
    var id= this.getAttribute('data-id');
     layer.confirm('取消后将不可恢复，确定取消吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/push/cancel_push",
            data: "params=" + params+'&id='+id,//要发送的数据                    
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

    
  });
  $("#get_token").click(function(){
      layer.prompt({
        formType: 0,
        title: '请输入手机号：',
      }, function(value, index, elem){
        $.get("/push/get_xg_token?umobile="+value, function(result){
          layer.open({
              title: '用户token'
              ,content: result
            });     
          });
/*        alert(value); //得到value
*/        layer.close(index);
      });
  });
</script>
 