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


  <script type="text/javascript" src="/static/js/layer/layer.js"></script>   
<script type="text/javascript" src="/static/js/layer/extend/layer.ext.js"></script>   
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>

    <tr ><th colspan='2'>
<div id="contentHeader" >

      <div class="searchArea">
    <ul class="action left">
      <li><a href="/stat/user" class="current"><span>用户注册统计</span></a></li>
      <li><a href="/stat/user_list" class=""><span>注册用户查询</span></a></li>
      <li><a id="get_token"  class=""><span>判断是否同一设备注册</span></a></li>
    </ul>
    <div class="search right"> </div>
  </div>

</div>


       
    </th></tr>
     <tr>
       
      
      <!--<th  colspan="2" style="color:red;font-size:16px;" >注册用户：<?=$counts['total']?>  微信用户：<?=$counts['weixin']?> 微博用户：<?=$counts['weibo']?> QQ用户<?=$counts['qq']?> 手机用户<?=$counts['mobile']?> &nbsp;&nbsp;||&nbsp;&nbsp;  android用户：<?=$counts['android']?> ios用户：<?=$counts['ios']?> 未知用户：<?=$counts['other']?> </th>
      -->

      <th colspan='2'>

       <div  id="searchid" >
        <form name="searchform" action="/stat/user" method="get" >
          <table width="100%" cellspacing="0" class="search-form">
            <tbody>
             <tr>
              <td >
                <div class="explain-col" style="float:right" >
                 <input type ="hidden" name='is_search' value='1' />
                 
                  开始时间:

                  <input type="text" name="start_time" id="start_time" value="<?= $con['start_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
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

                  结束时间： <input type="text" name="end_time" id="end_time" value="<?= $con['end_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
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
    
    <?php if($is_search){?>
   <tr class="tb_header">
   <th width="50">注册来源</th>
  <th width="50">用户数</th>
</tr>
</tr>
 </thead>
  

<tr class="tb_list">



 	<td width="50%" style="color: red;">用户总数：</td>
 	<td width="50%" style="color: red;"><?=$counts['total']?></td> 
  </tr>
  <tr class="tb_list">
 	<td width="50%">手机用户：</td>
 	<td width="50%"><?=$counts['mobile']?> </td> 
  </tr>
  
 	<td width="50%">微信用户：</td>
 	<td width="50%"><?=$counts['weixin']?></td> 
  </tr>
<tr class="tb_list">
 	<td width="50%">微博用户：</td>
 	<td width="50%"><?=$counts['weibo']?> </td>
  </tr>
<tr class="tb_list">
 	<td width="50%">QQ用户：</td>
 	<td width="50%"><?=$counts['qq']?></td> 
  </tr>

 <tr class="tb_header">
   <th width="50">设备类型</th>
  <th width="50">用户数</th>
</tr>

<tr class="tb_list">
 	<td width="50%">ios用户：</td>
 	<td width="50%"><?=$counts['ios']?> </td> 
  </tr>
  <tr class="tb_list">
 	<td width="50%">android用户：</td>
 	<td width="50%"><?=$counts['android']?> </td> 
  </tr>
 <tr class="tb_list">
 	<td width="50%">未知用户：</td>
 	<td width="50%"><?=$counts['other']?> </td> 
  </tr>


<tr class="tb_header">
   <th width="50">渠道类型</th>
  <th width="50">数量</th>
</tr>
<?php
foreach($counts['count'] as $key=>$val){
?>
 <tr class="tb_list">
 	<td width="50%"><?php echo isset($val['qd'])?$val['qd']:"未知"?></td>
 	<td width="50%"><?php echo $val['count']?></td>
  </tr>
    <?php
}
    ?>
</table>
<?php } ?>
<div id="_tips"></div>
<script type="text/javascript">
   $("#get_token").click(function(){
      layer.prompt({
        formType: 2,
        title: '请输入手机号(用,隔开)：',
        maxlength:50000,
      }, function(value, index, elem){
        $.get("/stat/usertoken?umobile="+value, function(result){
          layer.open({
              title: '用户token',
              area: ['700px', '500px'], //宽高
              content: result
            });     
          });
/*        alert(value); //得到value
*/        layer.close(index);
      });
  });
</script>
