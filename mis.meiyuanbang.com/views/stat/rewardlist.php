<?php
use common\widgets\MyLinkPager;
use common\service\dict\CorrectGiftService;
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
      <th colspan="12" >
       <div  id="searchid" >
        <form name="searchform" action="/stat/reward_list" method="get" >
          <table width="100%" cellspacing="0" class="search-form">
            <tbody  style=" border: 1px solid #ddd;">
                <div class="explain-col" >
                  <tr>
                    <td>
                           开始时间:
                      <input type="text" name="stime" id="stime" value="<?= $search['stime'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;&nbsp;
                      <script type="text/javascript">
                        Calendar.setup({
                          weekNumbers: true,
                          inputField : "stime",
                          trigger    : "stime",
                          dateFormat: "%Y-%m-%d %H:%M",
                          showTime: true,
                          minuteStep: 1,
                          onSelect   : function() {this.hide();}
                        });
                      </script>         

                      结束时间： <input type="text" name="etime" id="etime" value="<?= $search['etime'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;&nbsp;
                      <script type="text/javascript">
                        Calendar.setup({
                          weekNumbers: true,
                          inputField : "etime",
                          trigger    : "etime",
                          /* dateFormat: "%Y-%m-%d %H:%M:%S",*/ 
                          dateFormat: "%Y-%m-%d %H:%M",
                          showTime: true,
                          minuteStep: 1,
                          onSelect   : function() {this.hide();}
                        });
                      </script>  
                      &nbsp;礼物内容：
                      <!-- 订单类型 :1直播  2点播 3画室班型报名方式 -->
                       <select name="subjecttype">
                          <option value="" >请选择</option>
                          <?php
                         if(CorrectGiftService::getGiftData()){
                            
                             foreach(CorrectGiftService::getGiftData() as $key=>$v){
                                 if($search['subjecttype']==$v['gift_id']){
                                     $where = 'selected=selected';
                                 }else{
                                      $where = '';
                                 }
                          ?>
                          <option value="<?php echo $v['gift_id']?>" <?php echo $where; ?>><?php echo $v['gift_name']?></option>
                          <?php
                             }
                         }
                          ?>
                      </select>
                       老师昵称:<input type="text" name="teachername" id="teachername" value="<?=@$search['teachername']?>" class="inputclass1" style="width:100px">&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="submit" name="search" class="button button-primary button-small" value="搜索" />
                    </td>
                  </tr>
                   <tr class="operate" >
                       <th>
                    <?php if($models){
                        echo  '共有<strong>'.$pages->totalCount.'</strong>条记录,' ; 
                        echo '&nbsp;收到礼物次数总计<strong>'.@$array['count'].',</strong>'; 
                        echo '&nbsp;礼物金额总计<strong>'.@$array['money'].'元</strong>'; 
                    
                    } ?>
                 </th>
              </tr>
            </div>
              
          </tbody>
        </table>
      </form>
    </div>
  </th>
 </tr>
      <tr class="tb_header">
        <th style="width:10%">编号</th>
         <th style="width:10%">老师昵称</th>
        <th style="width:10%">收到礼物次数</th>
        <th style="width:10%">礼物金额</th>
      </tr>
    </thead>
    <? if($models){ foreach ($models['models'] as $model) { ?>
      <tr class="tb_list" data-orderid="" >
       <td><?= $model['rewardid'] ?></td>
       <td><?= $model['sname'] ?></td>
        <td><?= $model['cu'] ?></td>
        <td><?= $model['pic'] ?></td>
      </tr>  
     <?}}?>
      <tr class="operate">
        <td colspan="8">
          <div class="cuspages right">
          <?if($models){echo  MyLinkPager::widget(['pagination' => $pages,]);} ?>
          </div>      
        </td>
      </tr>
  </table>
  <script type="text/javascript">
    function showDetail(orderid){
       $.get("/stat/order_detail?orderid="+orderid, function(result){
         layer.open({
          type: 1,
          shadeClose :true,
          skin: 'layui-layer-lan', //加上边框
          area: ['700px', '500px'], //宽高
          content: result
        });
      });
         
    };

    /*$(".tb_list").mouseout(function(){
        layer.closeAll()
    });*/
  </script>