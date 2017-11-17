  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
  
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

  <script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
  <script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr >
<!--        <th colspan="10" >
          <div class="button-group" >
             <a href="/stat/correct" class="button  button-small">批改统计</a>
            <a href="/stat/tweet" class="button  button-primary button-small">帖子统计</a>
            <a href="/stat/comment" class="button  button-small ">评论统计</a>
          </div>
        </th>-->
      </tr>
      <tr class="operate">
        <th colspan="2" >
        
        <? if($search['is_search']==1){?>共有<?= $pages->totalCount ?>条记录 <?}else{?>请选择搜索条件<?}?>
        
      </th>
      <th colspan="5">
       <div id="searchid">
        <form name="searchform" action="/stat/tweet" method="get" >
          <table width="100%" cellspacing="0" class="search-form">
            <tbody>
             <tr>
              <td>
                <div class="explain-col">
                 <input type ="hidden" name='is_search' value='1' />
                 排序（多到少）：
                   <select name="order_by">
                    <option value="tweet_count" <?if($search['order_by']=='tweet_count'){?>  selected <?}?> >帖子数</option>
                    <option value="comment_count" <?if($search['order_by']=='comment_count'){?>  selected <?}?>  >评论数</option>
                  </select>
                  <select name="user_type">
                    <option value="1" <?if($search['user_type']=='1'){?>  selected <?}?> >用户名</option>
                    <option value="2"  <?if($search['user_type']=='2'){?>  selected <?}?>  >用户编号</option>
                  </select>
                  <!-- 用户名： --><input name="search_user" type="text" value="<?= $search['search_user'] ?>" class="input-text" /> 
                  开始时间:

                  <input type="text" name="start_time" id="start_time" value="<?= $search['start_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                  <script type="text/javascript">
                    Calendar.setup({
                      weekNumbers: true,
                      inputField : "start_time",
                      trigger    : "start_time",
                      dateFormat: "%Y-%m-%d 00:00:00",
                      showTime: true,
                      minuteStep: 1,
                      onSelect   : function() {this.hide();}
                    });
                  </script>         

                  结束时间： <input type="text" name="end_time" id="end_time" value="<?= $search['end_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                  <script type="text/javascript">
                    Calendar.setup({
                      weekNumbers: true,
                      inputField : "end_time",
                      trigger    : "end_time",
                      dateFormat: "%Y-%m-%d 00:00:00",
                      showTime: true,
                      minuteStep: 1,
                      onSelect   : function() {this.hide();}
                    });
                  </script>  
                  <input type="submit" name="search" class="button button-primary button-small" value="搜索" />

                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </th>
</tr>

<tr class="tb_header">
  <th>用户编号</th>  
  <th>用户头像</th>
  <th>用户昵称</th>
  <th>帖子数</th>
  <th>图片数</th>
  <th>被评论总数</th>
  <th>图片付费点击数</th>
</tr>
 </thead>
 <? if($search['is_search']==1){?>
 <? foreach ($models as $model) { ?>
 <tr class="tb_list">
  <td><?= $model['uid'] ?></td>
  <td> <img  style="width:50px;height:50px;border-radius:50px;" src="<?= json_decode($model['avatar'],true)['img']['n']['url']?>" /></td>
  <td><?= $model['sname']?></td>
  <td><!-- <a name='sname_tweet'href="#" data-sname='<?= $model['sname']?>' > --><?= $model['tweet_count'] ?><!-- </a> --></td>
  <td><?= $model['img_count'] ?></td>
  <td><?= $model['comment_count'] ?></td>
  <td><?= $model['prize_count'] ?></td>   
</tr>          
<?}?>

<script type="text/javascript">
  $('[name="sname_tweet"]').on('click', function(){   
    var content = '/tweet?search=搜索&sname='+this.getAttribute('data-sname');
   // alert(content);
    var title = '帖子';
    layer.open({
          type: 2,
          title: title,
          maxmin: true,
          shadeClose: false, //点击遮罩关闭层
          area : ['1300px' , '700px'],
          content: content
      });
  });
  </script>
<tr class="operate">
 <td colspan="6">&nbsp;
   <div class="cuspages right">
     <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
   </div>      
 </td>
</tr>

<? }?>
</table>
<div id="_tips"></div>
