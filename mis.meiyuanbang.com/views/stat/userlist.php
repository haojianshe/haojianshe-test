  <?php
  use common\widgets\MyLinkPager;
  use common\service\DictdataService;

  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
     <tr >
      </tr>
      <tr class="operate" >
	      <th colspan="7">

<div id="contentHeader" >
  <div class="searchArea">
    <ul class="action left">
      <li><a href="/stat/user" class=""><span>用户注册统计</span></a></li>
      <li><a href="/stat/user_list" class="current"><span>注册用户查询</span></a></li>
    </ul>
    <div class="search right"> </div>
  </div>
</div>

	      </th>
      </tr>
      <tr class="operate">
        <th colspan="2" >
        <?if($models){echo  '共有'.$pages->totalCount.'条记录' ;} ?>
        </th>
        <th colspan="8" >
       <div  id="searchid" >
        <form name="searchform" action="/stat/user_list" method="get" >
          <table width="100%" cellspacing="0" class="search-form">
            <tbody>
             <tr>
              <td>
                <div class="explain-col" style="float:right" >
                 <input type ="hidden" name='is_search' value='1' />
                  手机号:<input type="text" name="mobile" id="mobile" value="<?=$search['mobile']?>" class="inputclass1" style="width:320px">&nbsp;
                  渠道：<input type="text" name="qd" id="qd" value="<?=$search['qd']?>" class="inputclass1"  style="width:160px">&nbsp;
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
       <!--  <th style="width:8%">头像</th> -->
        <th style="width:8%">用户编号</th>
        <th style="width:8%">昵称</th>
        <th style="width:14%">用户手机号</th>
        <th style="width:8%">登录方式</th>
        <th>使用时长</th>
        <th>身份</th>
        <th>地区</th>
        <th>渠道</th>
        <th style="width:8%">注册日期</th>        
      </tr>
    </thead>
    <? if($models){ foreach ($models as $model) { ?>
      <tr class="tb_list">
    <!--   <td><img src='<?= $model['avatars']?>' style='height:50px;width:50px;'/> </td> -->
      <td><?= $model['uid'] ?></td>
      <td><?= $model['sname'] ?></td>
      <td><?= $model['umobile'] ?></td>
      <td><?= $model['oauth_type'] ?></td>
      <td><?= $model['su'] ?>分钟</td>
      <td><?= DictdataService::getProfessionById(intval($model['professionid'])) ?></td>
      <td><?= DictdataService::getUserProvinceById($model['provinceid'])  ?></td>
      <td><?= $model['qd'] ?></td>
      <td><?= date('Y-m-d H:i:s',$model['create_time']); ?></td>
      </tr>  
     <?}}?>
      <tr class="operate">
	      <td colspan="10">
			<div class="cuspages right">
			<?if($models){echo  MyLinkPager::widget(['pagination' => $pages,]);} ?>
			</div>      
	      </td>
      </tr>
  </table>