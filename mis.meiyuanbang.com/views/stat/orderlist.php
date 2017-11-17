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

<div id="contentHeader" <? if($search['is_hide']){ ?> hidden<?}?>>
  <h3>订单统计功能</h3>
  <div class="searchArea">
    <ul class="action left">
      <li><a href="/stat/order_list" class="current"><span>按订单</span></a></li>
      <li><a href="/stat/order_user_list" class=""><span>按用户</span></a></li>
      <li><a href="/stat/order_content_list" class=""><span>按内容</span></a></li>
    </ul>
    <div class="search right"> </div>
  </div>
</div>

<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <th colspan="12" <? if($search['is_hide']){ ?> hidden<?}?>>
       <div  id="searchid" >
        <form name="searchform" action="/stat/order_list" method="get" >
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
                        <select name="status">
                          <option  value="">请选择</option>
                          <option value="0" <?if($search['status']=='0'){?>  selected <?}?> >未支付</option>
                          <option value="1" <?if($search['status']=='1'){?>  selected <?}?>>已支付</option>
                        </select>
                        <input type ="hidden" name='ordertitle' value='1' />
                    是否团购：
                        <select name="groupbuyid">
                          <option  value="">请选择</option>
                          <option value="2" <?if($search['groupbuyid']=='2'){?>  selected <?}?> >否</option>
                          <option value="1" <?if($search['groupbuyid']=='1'){?>  selected <?}?>>是</option>
                        </select>
                    </td>
                  </tr>
                  <tr>
                    <td>

                    支付方式：
                      <!-- 支付类型 1微信  2支付宝 3apple内购 4 课程券购买（全部课程卷购买） -->  
                       <select name="paytype">
                          <option  value="">请选择</option>
                          <option value="1" <?if($search['paytype']=='1'){?>  selected <?}?> >微信</option>
                          <option value="2" <?if($search['paytype']=='2'){?>  selected <?}?>>支付宝</option>
                          <option value="3" <?if($search['paytype']=='3'){?>  selected <?}?>>apple内购</option>
                          <option value="4" <?if($search['paytype']=='4'){?>  selected <?}?>>课程券购买</option>
                        </select>
                         订单名称:<input type="text" name="ordertitle" id="ordertitle" value="<?=$search['ordertitle']?>" class="inputclass1" style="width:165px">&nbsp;
                         课程券名称:<input type="text" name="coupon_name" id="coupon_name" value="<?=$search['coupon_name']?>" class="inputclass1" style="width:165px">&nbsp;


                     &nbsp;&nbsp;&nbsp;&nbsp;用户名：<input type="text" name="username" id="username" value="<?=$search['username']?>" class="inputclass1"  style="width:165px">&nbsp;
                        <input type ="hidden" name='orderid' value='1' />
                        &nbsp;&nbsp;订单编号&nbsp;:&nbsp;<input type="text" name="orderid" id="orderid" value="<?=$search['orderid']?>" class="inputclass1" style="width:180px">&nbsp;
                    渠道：
                        <select name="qd">
                          <option value="" >全部</option>
                          <option value="1" <?if($search['qd']=='1'){?>  selected <?}?>>android</option>
                          <option value="2" <?if($search['qd']=='2'){?>  selected <?}?>>ios</option>
                          <option value="3" <?if($search['qd']=='3'){?>  selected <?}?>>微信公众号与手机浏览器</option>
                        </select>
                  排序：
                        <select name="orderby">
                          <option  value="">请选择</option>
                          <option value="fee desc" <?if($search['orderby']=='fee desc'){?>  selected <?}?> >订单金额从高到低</option>
                          <option value="fee asc" <?if($search['orderby']=='fee asc'){?>  selected <?}?>>订单金额从低到高 </option>
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;

                        <input type="submit" name="search" class="button button-primary button-small" value="搜索" />

                  </td>
              </tr>
                   <tr class="operate" >
                       <th>
                    <?php if($models){echo  '共有<strong>'.$pages->totalCount.'</strong>条记录' ; echo '&nbsp;&nbsp;&nbsp;总金额<strong>'.$models['countFee']['totlefee'].'元</strong>'; /*echo '&nbsp;&nbsp;&nbsp;老师分成共计<strong>'.$bounty_fee.'元</strong>';*/ } ?>
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
        <th style="width:10%">订单编号</th>
         <th style="width:10%">订单名称</th>
        <th style="width:10%">内容类型</th>
        <th style="width:10%">订单状态</th>
        <th style="width:5%">渠道</th>
        <th style="width:10%">下单时间</th>

        <th style="width:5%">总价</th>
        <th style="width:5%">优惠</th>
        <th style="width:5%">订单金额</th>

        <th style="width:10%">用户名</th>
        <th style="width:10%">手机号</th>
       <!--  <th style="width:10%">注册日期</th> -->
      </tr>
    </thead>
    <? if($models){ foreach ($models['models'] as $model) { ?>
      <tr class="tb_list" data-orderid="<?= $model['orderid'] ?>" >
       <td><?= $model['orderid'] ?></td>
       <td>

        <? if(intval($model['subjecttype'])==2){?>
          <a href=javascript:void(0);" onclick="showDetail(<?= $model['orderid'] ?>);" ><?= $model['ordertitle'] ?></a>
        <?}else{?>
          <?= $model['ordertitle'] ?>
        <?}?></td>

       
        <?
       // /订单类型 :1直播  2点播 3画室班型报名方式
          switch (intval($model['subjecttype'])) {
            case 1:
            ?>
              <td>直播</td>
            <?
            break;
            case 2:
            ?>
              <td>课程</td>
            <?
            break;
            case 3:
              ?>
              <td>画室班型报名</td>
              <?
              break;
                case 4:
              ?>
              <td>礼物</td>
              <?
              break;
              case 5:
              ?>
              <td>求批改</td>
              <?
              break;
              default:
               
              break;
          }

       ?>
        <td><?
        switch (intval($model['status'])) {
          case 0:
            echo '未支付';
            break;
          case 1:
            if($model['paytype']=='1'){
            	echo "<span class='green'>微信支付</span>";
            }
            if($model['paytype']=='2'){
            	echo "<span class='green'>支付宝</span>";
            }
            if($model['paytype']=='3'){
              echo "<span class='green'>ios内购</span>";
            }
            if($model['paytype']=='4'){
            	echo "<span class='green'>课程券购买</span>";
            }
            break;          
          default:
            # code...
            break;
        }
          ?></td>
        <td><?= $model['order_from'] ?></td>
        <td><?= date('Y-m-d H:i:s',$model['ctime']); ?></td>


        <td><?= $model['fee']+$model['coupon_price'] ?></td>
        <td><?= $model['coupon_price'] ?></td>
        <td><?= $model['fee'] ?></td>
        <td><?= $model['sname'] ?></td>
        
        <td><?= $model['umobile'] ?></td>
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