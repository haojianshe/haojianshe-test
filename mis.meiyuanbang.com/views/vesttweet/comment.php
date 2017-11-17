  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'> 

  <link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

  <script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
  <script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
  <table cellspacing="0" cellpadding="0" class="content_list" style="width:100%;">
    <thead>
      <tr class="operate" >
        <th colspan="7" >
        	 共有<?= $pages->totalCount ?>条记录
        </th>

        <th  colspan="2" style="text-align: right;">
           <input type="add" onclick="newcomment(<?=$tid?>,0,0,0);" name="add" class="button" value="发评论" />
        </th>
      </tr>
        <style type="text/css">
        .content_list td {
          max-width: 400px;
          height: auto;
        }
          </style>
      <tr class="tb_header">
        <th >评论编号</th>
        <th >用户</th>
        <th >回复的用户</th>
        <th >类型</th>        
        <th>内容</th>
        <th >评论对象id</th>
        <th >评论类型</th>
        <th >创建时间</th>
         <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
     <td><?= $model['cid'] ?></td>
      <td><?= "<img width='40px;' src='".json_decode($model['avatar'])->img->n->url ."' >  ". $model['sname'] ?> 
          <? if($model['is_vest']){?>
                <span style='color:red;'> (a)</span>
          <?}?>
      </td>
      <?if($model['rsname'] ){?>
          <td><?= "<img width='40px;' src='".json_decode($model['ravatar'])->img->n->url ."' >  ". $model['rsname'] ?></td>
      <?}else{?>
      <td>无</td>

      <?}?>
      <td><?
 switch ($model['ctype']) {
    case 0:
        echo '文字' ;
        break;
        case 1:
        echo "图片" ;
        break;
        case 2:
        echo "声音" ;
        break;
        default:
          # code...
        break;
 }
      ?></td>
      <td><?
      //0文字 1图片 2声音
      switch ($model['ctype']) {
        case 0:
        echo $model['content'] ;
        break;
        case 1:
        $imgurl = json_decode($model['content'])->n->url . '@200h_2o';
        echo "<img src='" . $imgurl . "' />" ;
        break;
        case 2:
        echo $model['content'] ;
        break;
        default:
          # code...
        break;
      }

      ?>
      </td>
      <td>
       <?= $model['subjectid'];?>
      </td>
      <td><?
        switch ($model['subjecttype']) {
              //0帖子 1专家动态评论 2小组讨论 3精讲 4考点 5活动
          case 0:
          echo '帖子';
          break;
          case 1:
          echo '专家动态评论';
                # code...
          break;
          case 2:
          echo '小组讨论';
                # code...
          break;
          case 3:
          echo '文章';
                # code...
          break;
          case 4:
          echo '考点'; 
                # code...
          break;
          case 5:
          echo  '活动';
                # code...
          break;  
          default:
                # code...
          break;
            }
        ?>
      </td> 
      <td><?= date("Y-m-d H:i:s",$model['ctime'])  ?></td>
      <td>

          <? if( in_array($tweet_uid, $users)){?>
          <a href="#" onclick="del(<?= $model['cid'] ?>,<?= $model['subjecttype'] ?>)">删除</a>
           <a href="#" onclick="newcomment(<?= $model['subjectid'] ?>,<?= $tweet_uid ?>,<?= $model['cid'] ?>,<?= $model['uid'] ?>)">回复</a> 
          <? } ?>
           
          <? if( in_array($model['reply_uid'], $users)){?>
           <a href="#" onclick="newcomment(<?= $model['subjectid'] ?>,<?= $model['reply_uid'] ?>,<?= $model['cid'] ?>,<?= $tweet_uid ?>)">回复</a> 
          <? } ?>
         
      </td>
    </tr> 

         <?}?>

       </table>
       <div id="_tips"></div>
       <script type="text/javascript">  




   function newcomment(tid,uid,reply_cid,reply_uid){
      var content = '/vesttweet/newcomment?tid='+tid+"&uid="+uid+"&reply_uid="+reply_uid+"&reply_cid="+reply_cid;
      var title = '回复评论';
      /*if(tid >0){
        content = content + '?tid=' + tid; 
        title = '回复评论';
      }*/
      layer.open({
        type: 2,
        title: title,
        maxmin: true,
            shadeClose: false, //点击遮罩关闭层
            area : ['450px' , '600px'],
            content: content
        });
   } 

         //删除评论
        function del(cid,subjecttype){
                layer.confirm('是否删除？', {
            btn: ['删除','否'] //按钮
          }, function(){
            $.ajax({
              type: "post",
              dataType: "json",
              url: "/comment/del",
                  data: "cid=" + cid+"&is_del=1"+"&subjecttype="+subjecttype,//要发送的数据                    
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

        //编辑或新增用户页面
        function addedit(newsid){
          var content = '/lecture/edit';
          var title = '添加精讲文章';
          if(newsid >0){
            content = content + '?newsid=' + newsid; 
            title = '编辑精讲文章--编号:'+ newsid;
          }
          top.parent.layer.open({
                type: 2,
                title: title,
                maxmin: false,
                shadeClose: false, //点击遮罩关闭层
                area : ['70%' , '100%'],
                content: content
            });
        }  
</script>