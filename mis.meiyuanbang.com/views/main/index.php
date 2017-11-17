<!doctype html>
<html>
<head>
<title>美院帮mis管理系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="/static/css/manage.css">
<script type="text/javascript" src="/static/js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/static/js/layer/layer.js"></script>		
</head>
<?php use mis\service\MisRoleResourceService;
?>
<body scroll="no">
<div class="header">
  <div class="logo">bagecms.com</div>
  <div class="nav">
    <ul>
      <!-- 主菜单id-->
      <?$mainmenuid=0;?>
      <li index="<?=$mainmenuid++?>">
        <div><a href="/main/default" target="win" hidefocus>首页</a></div>
      </li>

      <? if(MisRoleResourceService::showTopMenu($model["mis_userid"],1)){?>
      <li index="<?=$mainmenuid++?>">
        <div><a href="/operate/default" target="win" hidefocus>运营</a></div>
      </li>
      <?}?>

      <? if(MisRoleResourceService::showTopMenu($model["mis_userid"],2)){?>
      <li index="<?=$mainmenuid++?>">
        <div><a href="/znarticle/index" target="win" hidefocus>正能后台</a></div>
      </li>
      <?}?>

      <? if(MisRoleResourceService::showTopMenu($model["mis_userid"],3)){?>
      <li index="<?=$mainmenuid++?>">
        <div><a href="/misuser" target="win" hidefocus>管理员</a></div>
      </li>
      <?}?>

      <li index="4">
        <div><a href="/misuser/chgownpwd" target="win" hidefocus>个人信息</a></div>
      </li>
      </ul>
  </div>
  <div class="logininfo"><span class="welcome"><img src="/static/images/user_edit.png" align="absmiddle"> 欢迎, <em><?= $realname ?></em> </span><a href="/index/logout">退出登录</a></div>
</div>
<div class="topline">
  <div class="toplineimg left" id="imgLine"></div>
</div>
<div class="main" id="main">
  <div class="mainA">
    <div id="leftmenu" class="menu" style="position:absolute; height:2000px; overflow:auto">
            <!--左侧菜单id-->
            <?$leftmenuid=0;?>
            <ul index="<?=$leftmenuid++?>" class="left_menu">
                <li index="0"><a href="/main/default" target="win">系统首页</a></li>
              </ul>
            <ul index="<?=$leftmenuid++?>" class="left_menu" style="overflow:auto;" >
                   <!--内容管理-->
                <?$operateid=0;?>
                <li index="<?=$operateid++?>"><a style="text-indent:0px;" href="/operate/default" target="win" id="liid1">内容管理</a></li>
                <div id="divmenu1" index="<?=$leftmenuid-1?>">
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_tweet")){?>
                <li index="<?=$operateid++?>" ><a href="/tweet" target="win">帖子&素材管理</a></li>
                <?}?>
                
                <?// if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_vesttweet")){?>
                <!--<li index="<?//=$operateid++?>"><a href="/vesttweet" target="win">发帖工具</a></li>-->
                <?//}?>
                
                 <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_correct")){?>
                <li index="<?=$operateid++?>"><a href="/correct" target="win">批改管理</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_cmt")){?>
                <li index="<?=$operateid++?>"><a href="/comment" target="win">评论管理</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_lecture")){?>
                <li index="<?=$operateid++?>"><a href="/lecture" target="win">精讲管理</a></li>
                <?}?>
              

                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_lesson")){?>
                <li index="<?=$operateid++?>"><a href="/lesson" target="win">跟着画</a></li>
                <?}?>
              
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_capacity_material")){?>
                <li index="<?=$operateid++?>"><a href="/capacity/material" target="win">能力模型素材</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_material")){?>
                <li index="<?=$operateid++?>"><a href="/material/index" target="win">素材专题管理</a></li>
                <?}?>
              
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_video")){?>
                <li index="<?=$operateid++?>"><a href="/video/index" target="win">视频管理</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_video")){?>
                <li index="<?=$operateid++?>"><a href="/sound/index" target="win">音频管理</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_tag")){?>
                <li index="<?=$operateid++?>" ><a href="/tag/group_list" target="win">标签管理</a></li>
                <?}?>
                </div>
                
                <!--课程管理-->
                <li index="<?=$operateid++?>"><a style="text-indent:0px;" href="/operate/default" target="win" id="liid2">课程管理</a></li>
                <div id="divmenu2" index="<?=$leftmenuid-1?>">
                     <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_course")){?>
                    <li index="<?=$operateid++?>">
                        <a href="/course/subject" target="win">一招课程</a>
                    </li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_course")){?>
                    <li index="<?=$operateid++?>">
                        <a href="/course/index" target="win">课程管理</a>
                    </li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_course")){?>
                    <li index="<?=$operateid++?>">
                        <a href="/course/rec_catalog" target="win">课程推荐</a>
                    </li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_video")){?>
                    <li index="<?=$operateid++?>">
                        <a href="/live/index" target="win">直播课管理</a>
                    </li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_video")){?>
                    <li index="<?=$operateid++?>"><a href="/live/recommendlist" target="win">直播课推荐</a></li>
                <?}?>
                  
                </div>
               <!--活动管理-->
                <li index="<?=$operateid++?>" ><a style="text-indent:0px;" href="/operate/default" target="win" id="liid3">活动管理</a></li>
               <div id="divmenu3" index="<?=$leftmenuid-1?>">
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_activity")){?>
                <li index="<?=$operateid++?>"><a href="/invitation" target="win">邀请活动</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_activity")){?>
                <li index="<?=$operateid++?>"><a href="/activity" target="win">活动列表</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_activity")){?>
                <li index="<?=$operateid++?>"><a href="/fastcorrect" target="win">极速批改</a></li>
                <?}?>
               <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_activity")){?>
                <li index="<?=$operateid++?>"><a href="/turntable/prizegame" target="win">抽奖管理</a></li>
                <?}?>
                 <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_activity")){?>
                <li index="<?=$operateid++?>"><a href="/dkactivity/index" target="win">大咖改画</a></li>
                <?}?>
                
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_activity")){?>
                <li index="<?=$operateid++?>"><a href="/groupbuy/index" target="win">团购管理</a></li>
                <?}?>
                
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_activity")){?>
                <li index="<?=$operateid++?>"><a href="/lkactivity/index" target="win">联考活动</a></li>
                <?}?>
                
                 <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_simulation")){?>
                <li index="<?=$operateid++?>"><a href="/lkactivity/testsimulation" target="win">模拟考批卷</a></li>
                <?}?>
                
                 <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_activity")){?>
                <li index="<?=$operateid++?>"><a href="/lkactivity/article" target="win">活动文章管理</a></li>
              
                 <?}?>
                 <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_activity")){?>
                <li index="<?=$operateid++?>"><a href="/lkactivity/questions" target="win">问答管理</a></li>
                <?}?>

                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_coupon")){?>
                <li index="<?=$operateid++?>" ><a href="/coupon/index" target="win">课程券管理</a></li>
                <?}?>


                </div>
                <li index="<?=$operateid++?>" ><a style="text-indent:0px;" href="/operate/default" target="win" id="liid4">广告管理</a></li>
                <div id="divmenu4" index="<?=$leftmenuid-1?>">

                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_adv")){?>
                <li index="<?=$operateid++?>"><a href="/adv/user/" target="win">广告主</a></li>
                <?}?>

                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_adv")){?>
                <li index="<?=$operateid++?>"><a href="/adv/position/" target="win">广告位总览</a></li>
                <?}?>

                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_adv")){?>
                <li index="<?=$operateid++?>"><a href="/adv/record_user/" target="win">投放</a></li>
                <?}?>

                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_posid")){?>
                <li index="<?=$operateid++?>"><a href="/posid/abilitybook/" target="win">能力模型图书推荐</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_posid")){?>
                <li index="<?=$operateid++?>"><a href="/posid/mybbook/" target="win">美院帮图书推荐</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_startpage")){?>
                <li index="<?=$operateid++?>"><a href="/startpage" target="win">启动页管理</a></li>
                <?}?>
                
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_adv")){?>
                <li index="<?=$operateid++?>"><a href="/homepopadv" target="win">首页硬广弹窗</a></li>
                <?}?>

                </div>
                
              <!--合作推广-->
               <li index="<?=$operateid++?>" ><a style="text-indent:0px;" href="/operate/default" target="win" id="liid5">合作画室</a></li>
               <div id="divmenu5" index="<?=$leftmenuid-1?>">
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_studio")){?>
                <li index="<?=$operateid++?>"><a href="/studio/index/" target="win">画室用户管理</a></li>
                <?}?>
                </div>
                  <!--用户管理-->
                <li index="<?=$operateid++?>" ><a style="text-indent:0px;" href="/operate/default" target="win" id="liid6">用户管理</a></li>
                 <div id="divmenu6" index="<?=$leftmenuid-1?>">
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_publish")){?>
                <li index="<?=$operateid++?>"><a href="/user" target="win">成员用户</a></li>
                <?}?>
                  <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_publish")){?>
                <li index="<?=$operateid++?>"><a href="/lesson" target="win">学员课时</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_publish")){?>
                <li index="<?=$operateid++?>"><a href="/user" target="win">学员缴费</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_publish")){?>
                <li index="<?=$operateid++?>"><a href="/user" target="win">双11活动</a></li>
                <?}?>
<!--                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_publish")){?>
                <li index="<?//=$operateid++?>"><a href="/publish" target="win">出版社管理</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_teacher")){?>
                <li index="<?//=$operateid++?>"><a href="/teacher" target="win">老师认证管理</a></li>
                <?}?>
                 <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_teacher")){?>
                <li index="<?//=$operateid++?>"><a href="/teacher/redindex" target="win">红笔老师管理</a></li>
                <?}?>

                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_teacher")){?>
                <li index="<?//=$operateid++?>"><a href="/teacher/pay_teacher_arrange" target="win">付费批改老师管理</a></li>
                <?}?>
                  <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_blacklist")){?>
                <li index="<?//=$operateid++?>"><a href="/blacklist" target="win">黑名单管理</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_push")){?>
                <li index="<?//=$operateid++?>"><a href="/push" target="win">消息推送</a></li>
                <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_msg")){?>
                <li index="<?//=$operateid++?>"><a href="/msg" target="win">群发私信</a></li>
                <?}?> -->
                </div>
                <!--统计功能-->
                <li index="<?=$operateid++?>" ><a style="text-indent:0px;" href="/operate/default" target="win" id="liid7">统计功能</a></li>
                <div id="divmenu7" index="<?=$leftmenuid-1?>">
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_stat")){?>
                <li index="<?=$operateid++?>"><a href="/stat/correct" target="win">批改统计</a></li>
                 <?}?>
                  <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_stat")){?>
                <li index="<?=$operateid++?>"><a href="/stat/violations" target="win">批改违规统计</a></li>
                 <?}?>
                   <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_stat")){?>
                <li index="<?=$operateid++?>"><a href="/stat/tweet" target="win">作品统计</a></li>
                 <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_stat")){?>
                <li index="<?=$operateid++?>"><a href="/stat/comment" target="win">评论统计</a></li>
                 <?}?>
                 <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_stat")){?>
                <li index="<?=$operateid++?>"><a href="/stat/userrelation" target="win">批改关系统计</a></li>
                 <?}?>
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_stat")){?>
                <li index="<?=$operateid++?>"><a href="/stat/user" target="win">用户注册统计</a></li>
                <?}?>
              
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_stat")){?>
                <li index="<?=$operateid++?>"><a href="/stat/order_list" target="win">订单统计</a></li>
                <?}?>
                 <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_stat")){?>
                <li index="<?=$operateid++?>"><a href="/stat/invite_list" target="win">邀请统计</a></li>
                <?}?>
                  <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_stat")){?>
                <li index="<?=$operateid++?>"><a href="/stat/reward_list" target="win">礼物统计</a></li>
                <?}?>
                </div>
                
                 <li index="<?=$operateid++?>" ><a style="text-indent:0px;" href="/operate/default" target="win" id="liid8">工具管理</a></li>
                <div id="divmenu8" index="<?=$leftmenuid-1?>">
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_package")){?>
                <li index="<?=$operateid++?>"><a href="/tool/" target="win">渠道包管理</a></li>
                 <?}?>
                 <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_icons")){?>
                <li index="<?=$operateid++?>"><a href="/holidayicons/" target="win">节日图标</a></li>
                 <?}?>
                </div>             
              </ul>
              <ul index="<?=$leftmenuid++?>" class="left_menu">
                <? if(MisRoleResourceService::showLeftMenu($model["mis_userid"],"operation_zhn")){?>
                <li index="0"><a href="/znarticle/index" target="win">文章管理</a></li>
                <?}?>
              </ul>
              
            <ul index="<?=$leftmenuid++?>" class="left_menu">
                <li index="0"><a href="/misuser" target="win">MIS用户管理</a></li>
                <li index="1"><a href="/role" target="win">角色管理</a></li>
                <li index="2"><a href="/resource" target="win">资源管理</a></li>
                <li index="3"><a href="/cache" target="win">清除缓存</a></li>
                <li index="4"><a href="/tweet/import_excel" target="win">自动发帖数据导入</a></li>
              </ul>
            <ul index="<?=$leftmenuid++?>" class="left_menu">
                <li index="0"><a href="/misuser/chgownpwd" target="win">修改密码</a></li>
              </ul>
          </div>
  </div>
  <div class="mainB" id="mainB">
    <iframe src="/admini/default/home" name="win" id="win" width="100%" height="100%" frameborder="0"></iframe>
  </div>
</div>
<script type="text/javascript">
	window.onload =window.onresize= function(){winresize();}
	function winresize()
	{
		function $(s){return document.getElementById(s);}
		var D=document.documentElement||document.body,h=D.clientHeight-90,w=D.clientWidth-160;
		 $("main").style.height=h+"px";
		 $("mainB").style.width=w+"px";
	}
	//初始化
	$(document).ready(function(){
		//调整菜单位置
		$(".left_menu").css("height", $(window).height()-200);
	    var s=document.location.hash;
	    //默认选中第一个顶部导航的第一个左侧菜单
	    if(s==undefined||s==""){
		    s="#0_0";
		}
	    //截取掉前边的#
	    s=s.slice(1);
	    //navIndex[0]顶部导航编号  navIndex[1]代表左侧菜单选中项编号
	    var navIndex=s.split("_");
	    //顶部菜单设定选中状态
	    $(".nav").find("li:eq("+navIndex[0]+")").addClass("active");
	  	//隐藏所有顶部导航对应的左侧菜单,并显示当前选中的左侧菜单对应的ul
	    $(".menu").find("ul").hide().end()
	    		  .find(".left_menu:eq("+navIndex[0]+")").show();
	  	//初始化隐藏所有二级菜单
	   	hidenMenuDiv(0);
	    //检查是否有第三项二级菜单参数，有则展开
	    if(navIndex.length>=3){
	    	hidenMenuDiv(navIndex[2]);
		}
	    //找到对应的具体菜单(li),改为选中状态并取得对应的url地址
	    var targetLink=$(".menu").find(".left_menu:eq("+navIndex[0]+")").find("li:eq("+navIndex[1]+")").addClass("active")
	                             .find("a").attr("href");
        //把框架内容页显示为选中菜单对应的url
	    $("#win").attr("src",targetLink);
	    //********************************以上代码处理url中当前页参数
	  
	  
	    //********************************以下代码初始化控件事件
	    //顶部导航添加click事件
	    $(".nav").find("li").click(function(){
		    //导航点击li时ul里的所有li变为非选中状态，并把点击项设置为选中状态
	        $(this).parent().find("li").removeClass("active").end().end()
	               .addClass("active");
	        var index=$(this).attr("index");
	        //隐藏所有左侧菜单ul,显示当前顶部导航对应的菜单ul，并把第一项设置为选中
	        $(".menu").find(".left_menu").hide();
	        $(".menu").find(".left_menu:eq("+index+")").show()
	                  .find("li").removeClass("active").first().addClass("active");
	      	//默认菜单第一项
	        document.location.hash=index+"_0";
	    });
	    //左侧菜单项添加click事件
	    $(".left_menu").find("li").click(function(){
	    	$(this).parent().find("li").removeClass("active").end().end()
                        .addClass("active");
            //支持二级菜单div编号
            var divindex = $(this).parent().attr("id");
            if(divindex!=undefined && divindex.indexOf('divmenu')>-1){
            	divindex = divindex.replace('divmenu','');
            	hashvalue = $(this).parent().attr("index")+"_"+$(this).attr("index")+'_'+divindex;
            }
            else{
            	hashvalue = $(this).parent().attr("index")+"_"+$(this).attr("index");
            }
	        document.location.hash=hashvalue;
	    });
	});

	//定义共有多少个二级菜单div，从1开始，必须连续，增加新的div以后需要按照填写div的id
	divMenuCount=8;
   	//绑定二级菜单隐藏事件
   	$(function(){
		for(i=1;i<=divMenuCount;i++){
			$('#liid'+i).click(function(){
				//给带二级子菜单的li增加子菜单div显示隐藏控制逻辑
				n=$(this).attr("id").replace('liid', '');
				hidenMenuDiv(n);
			});
		}
    });
    //隐藏所有二级菜单div，只显示当前选中的一个
    function hidenMenuDiv(i) {
        //隐藏
        for(n=1;n<=divMenuCount;n++){
        	$("#divmenu"+n).hide();
        }
        if(i>0){
        	$('#divmenu'+i).slideToggle();
       	}
    }
</script>
</body>
</html>