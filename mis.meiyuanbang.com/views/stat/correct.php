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
<!--        <th colspan="12" >
          <div class="button-group" >
          <a href="/stat/correct" class="button button-primary  button-small ">批改统计</a>
            <a href="/stat/tweet" class="button  button-small">作品统计</a>
            <a href="/stat/comment" class="button  button-small ">评论统计</a>
          </div>
        </th>-->
      </tr>
      <tr>
      <th  colspan="5" style="color:red;font-size:16px;" >
        <? if($search['is_search']==1){
  ?>
  批改总数:<?=$total_info['totalcount']?>  已批改:<?=$total_info['hadcount']?> 待批改: <?=$total_info['waitcount']?> 已删除: <?=$total_info['delcount']?> 
  </br>
  求批改一次以上人数:<?=$subcount['count1']?> 五次以上: <?=$subcount['count5']?> 十次以上: <?=$subcount['count10']?> 二十次以上: <?=$subcount['count20']?>
  <?}?>
        
      </th>
      <th colspan="7" >
       <div  id="searchid" >
        <form name="searchform" action="/stat/correct" method="get" >
          <table width="100%" cellspacing="0" class="search-form">
            <tbody>
             <tr>
              <td >
                <div class="explain-col" style="float:right" >
                 <input type ="hidden" name='is_search' value='1' />
                 
                  开始时间:

                  <input type="text" name="start_time" id="start_time" value="<?= $search['start_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                  <script type="text/javascript">
                    Calendar.setup({
                      weekNumbers: true,
                      inputField : "start_time",
                      trigger    : "start_time",
                      dateFormat: "%Y-%m-%d %H:%M",
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
                      dateFormat: "%Y-%m-%d %H:%M",
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
  <th style="width:5%">用户编号</th>  
  <th style="width:10%">用户信息</th>
  <th style="width:5%">总数</th>
  <th style="width:5%">已批改</th>
  <th style="width:5%">待批改</th>
  <th style="width:5%">评论总数</th>
  <th style="width:10%">30分钟内完成</th>
  <th style="width:10%">30分钟-1小时内完成</th>
  <th style="width:10%">1小时-3小时内完成</th>
  <th style="width:10%">3小时-6小时内完成</th>
  <th style="width:10%">6小时以上</th>
  <th style="width:5%">总分数</th>
</tr>
</tr>
 </thead>
 <? if($search['is_search']==1){
  ?>
 <? foreach ($models as $model) { ?>
 <tr class="tb_list">
  <td><?= $model['uid'] ?></td>
  <td>  <img  style="width:50px;height:50px;border-radius:50px;" src="<?= json_decode($model['avatar'],true)['img']['n']['url']?>" />&nbsp;&nbsp;<?= $model['sname']?></td>
  <td><?= $model['count'] ?></td>
  <td><?= $model['correctcount'] ?></td>
  <td><?= $model['queuencount'] ?></td> 
  <td><?= $model['commentcount'] ?></td> 
  <td><?= $model['count5'] ?></td>  
  <td><?= $model['count4'] ?></td>  
  <td><?= $model['count3'] ?></td>   
  <td><?= $model['count2'] ?></td>  
  <td><?= $model['count_1'] ?></td>  
  <td><?= $model['grade'] ?></td>   
</tr>          
<?}?>
<? }?>
</table>
<div id="_tips"></div>
