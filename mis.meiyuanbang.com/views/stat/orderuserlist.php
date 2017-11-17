<?php
use common\widgets\MyLinkPager;
use common\service\DictdataService;

?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">

<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>

<div id="contentHeader">
  <h3>订单统计功能</h3>
  <div class="searchArea">
    <ul class="action left">
      <li><a href="/stat/order_list" ><span>按订单</span></a></li>
      <li><a href="/stat/order_user_list" class="current" class=""><span>按用户</span></a></li>
      <li><a href="/stat/order_content_list" class=""><span>按内容</span></a></li>
    </ul>
    <div class="search right"> </div>
  </div>
</div>

<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <th colspan="10" >
       <div  id="searchid" >
        <form name="searchform" action="/stat/order_user_list" method="get" >
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
    /*                        dateFormat: "%Y-%m-%d %H:%M:%S",
    */                      dateFormat: "%Y-%m-%d %H:%M",
                          showTime: true,
                          minuteStep: 1,
                          onSelect   : function() {this.hide();}
                        });
                      </script>  
                      &nbsp;内容类型：
                      <!-- 订单类型 :1直播  2点播 3画室班型报名方式 -->
                      <select name="subjecttype">
                          <option value="" >请选择</option>
                          <option value="1" <?if($search['subjecttype']=='1'){?>  selected <?}?> >直播</option>
                          <option value="2" <?if($search['subjecttype']=='2'){?>  selected <?}?>>点播</option>
                          <option value="3" <?if($search['subjecttype']=='3'){?>  selected <?}?>>画室班型报名</option>
                           <option value="4" <?if($search['subjecttype']=='4'){?>  selected <?}?>>礼物</option>
                          <option value="5" <?if($search['subjecttype']=='5'){?>  selected <?}?>>求批改</option>
                      </select>
                      订单状态：
                      <!-- 订单状态:0未支付  1已支付 -->
                        <select name="status">
                          <option  value="">请选择</option>
                          <option value="0" <?if($search['status']=='0'){?>  selected <?}?> >未支付</option>
                          <option value="1" <?if($search['status']=='1'){?>  selected <?}?>>已支付</option>
                        </select>
                    
                    </td>
                  </tr>
                  <tr>
                    <td>



                         订单名称:<input type="text" name="ordertitle" id="ordertitle" value="<?=$search['ordertitle']?>" class="inputclass1" style="width:165px">&nbsp;
                        

                     &nbsp;&nbsp;&nbsp;&nbsp;用户名：<input type="text" name="username" id="username" value="<?=$search['username']?>" class="inputclass1"  style="width:165px">&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;手机号：<input type="text" name="umobile" id="umobile" value="<?=$search['umobile']?>" class="inputclass1"  style="width:165px">&nbsp;

                  渠道：
                      <!-- 订单状态:0未支付  1已支付 -->
                        <select name="qd">
                          <option value="" >全部</option>
                          <option value="1" <?if($search['qd']=='1'){?>  selected <?}?>>android</option>
                          <option value="2" <?if($search['qd']=='2'){?>  selected <?}?>>ios</option>
                          <option value="3" <?if($search['qd']=='3'){?>  selected <?}?>>微信公众号与手机浏览器</option>
                        </select>


                         省级：
                        <select name="provinceid">
                          <option value="">请选择</option>
                          <? foreach (DictdataService::getProvince() as $key => $value) {
                            ?><option <?if($search['provinceid']!=NULL && $search['provinceid']==$value['provinceid']){echo "selected='selected'" ;} ?> value="<?=$value['provinceid']?>"><?=$value['provincename']?></option><?
                          } ?>
                        </select> 

                        身份：
                        <select name="professionid">
                          <option value="">请选择</option>
                          <? foreach (DictdataService::getProfession() as $key => $value) {
                            ?><option <?if($search['professionid']!=NULL && $search['professionid']==$value['professionid']){echo "selected='selected'" ;} ?> value="<?=$value['professionid']?>"><?=$value['professionname']?></option><?
                          } ?>
                        </select>

                  排序：
                        <select name="orderby">
                          <option  value="">请选择</option>
                          <option value="totalfee desc" <?if($search['orderby']=='totalfee desc'){?>  selected <?}?> >订单金额从高到低</option>
                          <option value="totalfee asc" <?if($search['orderby']=='totalfee asc'){?>  selected <?}?>>订单金额从低到高 </option>
                          <option value="totalcount desc" <?if($search['orderby']=='totalcount desc'){?>  selected <?}?>>订单总数从高到低 </option>
                          <option value="totalcount asc" <?if($search['orderby']=='totalcount asc'){?>  selected <?}?>>订单总数从低到高 </option>
                         
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;

                        <input type="submit" name="search" class="button button-primary button-small" value="搜索" />


                  </td>
              </tr>
                   <tr class="operate" >
                       <th>
                    <?php if($models){echo  '用户共计<strong>'.$pages->totalCount.'</strong>个' ; echo '&nbsp;&nbsp;&nbsp;订单共计<strong>'.$order_count.'&nbsp;&nbsp;&nbsp;<strong></strong>订单金额共计 '.$count.'</strong>元';} ?>
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
        <th style="width:10%">用户id</th>
        <th style="width:10%">用户昵称</th>
        <th style="width:10%">身份</th>
        <th style="width:10%">地区</th>
        <th style="width:5%">手机号</th>
        <th style="width:5%">注册日期</th>
        <th style="width:5%">订单总数</th>
        <th style="width:10%">订单总金额</th>
       
      </tr>
    </thead>
    <? if($models){ foreach ($models as $model) { ?>
      <tr class="tb_list" >
        <td><?= $model['uid'] ?></td>
        <td><?= $model['sname'] ?></td>
        <td><?= DictdataService::getProfessionById(intval($model['professionid'])) ?></td>
        <td><?= DictdataService::getUserProvinceById($model['provinceid'])  ?></td>
        <td><?= $model['umobile'] ?></td>
        <td><?= date('Y-m-d',$model['create_time']); ?></td>
        <td><a href="javascript:;" onclick="showOrderList('<?= $model['uid'] ?>')"><?= $model['totalcount'] ?></a></td>
        <td><?= $model['totalfee'] ?></td>
       
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
    function showOrderList(uid){
   
       layer.open({
          type: 2,
          area: ['90%', '80%'], //
          content:"/stat/order_list?is_hide=1&uid="+uid+"&"+$("form").serialize()+"&orderby="
        });
    }
    /*$(".tb_list").mouseout(function(){
        layer.closeAll()
    });*/
  </script>