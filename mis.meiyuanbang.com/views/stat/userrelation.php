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
   
      <tr>
      
      <th colspan="5" >
       <div  id="searchid" >
        <form name="searchform" action="/stat/userrelation" method="get" >
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
  <th style="width:10%">学生编号</th>  
  <th style="width:25%">学生信息</th>
  <th style="width:10%">老师编号</th>
  <th style="width:45%">老师信息</th>
  <th style="width:10%">批改总数</th>

</tr>
</tr>
 </thead>
 <? if($search['is_search']==1){
  ?>
 <? foreach ($models as $model) { ?>
 <tr class="tb_list">
  <td><?= $model['submit'][0]['uid'] ?></td>
  <td><img  style="width:50px;height:50px;border-radius:50px;" src="<?= json_decode($model['submit'][0]['avatar'],true)['img']['n']['url']?>" />&nbsp;&nbsp;<?= $model['submit'][0]['sname']?></td>
  <td><?= $model['teacher'][0]['uid'] ?></td> 
  <td><img  style="width:50px;height:50px;border-radius:50px;" src="<?= json_decode($model['teacher'][0]['avatar'],true)['img']['n']['url']?>" />&nbsp;&nbsp;<?= $model['teacher'][0]['sname']?></td>
  <td><?= $model['count'] ?></td>  
   
</tr>      
    
<?}?>
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
