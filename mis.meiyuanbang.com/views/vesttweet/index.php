  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>


<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
<script>
    function fun() {
        var result = true;
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        if (start_time != '' || end_time != '') {
            if (start_time > end_time) {
                layer.msg('开始时间不能大于结束时间，请你重新选择结束时间', {icon: 2});
                $("#end_time").val('');
                $("#end_time").focus();
                result = false;
            }
            if (start_time == end_time) {
               layer.msg('开始时间不能等于于结束时间，请你重新选择', {icon: 2});
                $("#end_time").val('');
                $("#end_time").focus();
                result = false;
            }
        }
        if (start_time == '' && end_time != '') {
           layer.msg('开始时间不能为空，请你选择开始时间', {icon: 2});
            $("#start_time").val('');
            $("#start_time").focus();
            result = false;
        }
        return result;
    }
</script>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="3" >
        	共有<?= $pages->totalCount ?>条记录
          </th>
      
        <th colspan="6" style="text-align: right;">
            <form name="searchform" action="/vesttweet/index" method="get" onsubmit="return fun();">
             
                   <span class="seach_block">
                                            创建开始时间:
                                            <input type="text" name="start_time" id="start_time" value="<?php echo @$start_time ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "start_time",
                                                    trigger: "start_time",
                                                    dateFormat: "%Y-%m-%d 00:00:00",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>   
                                            创建结束时间： <input type="text" name="end_time" id="end_time" value="<?php echo @$end_time ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            </span>
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "end_time",
                                                    trigger: "end_time",
                                                    dateFormat: "%Y-%m-%d 00:00:00",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>   
                    用户名：<input name="sname" type="text" value="<?php echo isset($sname)?$sname:""?>" class="input-text" />
                   <input type="submit" name="search" class="button" value="搜索" />
             </form>
        </th>
          <th colspan="1"  style="text-align: center;">
              <input type="submit"  name="search" class="button" value="发帖" onclick="addedittweet();">
              <input type="submit"  name="search" class="button" value="批量传素材" onclick="addtweetmaterial();">
          </th>

      </tr>
      <style type="text/css">
      td{
        max-width:300px;
      }
      </style>
      <tr class="tb_header">
        <th>帖子编号</th>
        <th>用户</th>
        <th>内容</th>
        <th>类型</th>
        <th>分类</th>   
        <th>图片</th>
        <th>标签</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>评论数</th>
        <th>操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
      <td><?= $model['tid'] ?></td>
      <td><?= $model['sname']?> 
          <? if($model['is_vest']){?>
          <span style='color:red;'> (a)</span>
          <?}?>
      </td>
       <td><?= $model['content'] ?></td>
       <td><?switch (intval($model['type'])) {
         case 1:
           echo '素材';
           break;
         case 2:
           echo '普通作品';
           break;
        case 3:
           echo '未批改';
           break;
        case 4:
           echo '已批改';
           break;
         default:
           # code...
           break;
       } ?></td>

     
      <td><?= $model['f_catalog'].'  '.$model['s_catalog'] ?></td>
      <td>
      <?foreach ($model['resources'] as $key => $value) { ?>
          <a id="example1" title="<?= $value['description'] ?>" rel="group<?= $model['tid'] ?>" href="<?= json_decode($value['img'])->s->url ?>">
          	<img style="height:50px;width:50px; margin-left:3px;margin-top:3px;" name="prew_resource" src="<?= json_decode($value['img'])->t->url ?>" />
          </a>
      <?}?>
      </td>
      <td><?= $model['tags'] ?></td>
      <td><?= date("Y-m-d H:i:s",$model['ctime'])  ?></td>
       <td><?= date("Y-m-d H:i:s",$model['utime'])  ?></td>
       <td><?= $model['cmtcount'] ?></td>
      <td>
        
          <a onclick="commentList(<?= $model['tid'] ?>,<?= $model['uid'] ?>);" >评论列表</a> 
           <a onclick="newcomment(<?= $model['tid'] ?>);"  >发评论</a> 
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
     function newcomment(tid){
        var content = '/vesttweet/newcomment';
        var title = '发表评论';
        if(tid >0){
          content = content + '?tid=' + tid; 
          title = '发表评论';
        }
        layer.open({
          type: 2,
          title: title,
          maxmin: true,
              shadeClose: false, //点击遮罩关闭层
              area : ['450px' , '400px'],
              content: content
          });
     }   
      //用户发帖（马甲）
      function addedittweet(){
        var content = '/vesttweet/add';
        var title = '添加帖子';
        layer.open({
              type: 2,
              title: title,
              maxmin: false,
              shadeClose: false, //点击遮罩关闭层
              area : ['700px' , '800px'],
              content: content
          });
      }
//批量传素材
      function addtweetmaterial(){
        var content = '/vesttweet/addmaterial';
        var title = '添加帖子';
        layer.open({
              type: 2,
              title: title,
              maxmin: false,
              shadeClose: false, //点击遮罩关闭层
              area : ['700px' , '800px'],
              content: content
          });
      }
      //评论列表
      function commentList(tid,uid){
        var content = '/vesttweet/comment?tid='+tid+"&uid="+uid;
        var title = '评论列表';
        layer.open({
              type: 2,
              title: title,
              maxmin: false,
              shadeClose: false, //点击遮罩关闭层
              area : ['50%' , '80%'],
              content: content
          });
      }

</script>


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


