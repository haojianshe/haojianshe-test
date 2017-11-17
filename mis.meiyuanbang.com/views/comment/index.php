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
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate" >
        <th colspan="1" >
        	 共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="8" >
               <div id="searchid" >
                <form name="searchform" action="/comment/index" method="get" >
                  <table width="100%" cellspacing="0" class="search-form">
                    <tbody>
                     <tr >
                      <td>
                        <div class="explain-col" style="text-align: right;">
                        <select name='subjecttype'>
                        <!-- //0帖子 1专家动态评论 2小组讨论 3精讲 4考点 5活动 -->
                        <option  >请选择</option>
                        <option value='0' <?if($search_arr['subjecttype']=='0'){?>  selected <?}?> >帖子</option>
                        <option value='1' <?if($search_arr['subjecttype']=='1'){?>  selected <?}?> >专家动态评论</option>
                        <option value='2' <?if($search_arr['subjecttype']=='2'){?>  selected <?}?>>小组讨论</option>
                        <option value='3' <?if($search_arr['subjecttype']=='3'){?>  selected <?}?>>文章</option>
                        <option value='4' <?if($search_arr['subjecttype']=='4'){?>  selected <?}?>>考点</option>
                        <option value='5' <?if($search_arr['subjecttype']=='5'){?>  selected <?}?>>活动</option>
                      <!--  评论主题的类型 0帖子 1专家动态评论 2小组讨论 3 文章 4考点 5活动 6 正能文章7、活动文章 8、活动问答 9、能力模型素材 10、直播11、课程 -->
                        <option value='6' <?if($search_arr['subjecttype']=='6'){?>  selected <?}?>>正能文章</option>
                        <option value='7' <?if($search_arr['subjecttype']=='7'){?>  selected <?}?>>活动文章</option>
                        <option value='8' <?if($search_arr['subjecttype']=='8'){?>  selected <?}?>>活动问答</option>
                        <option value='9' <?if($search_arr['subjecttype']=='9'){?>  selected <?}?>>能力模型素材</option>
                        <option value='10' <?if($search_arr['subjecttype']=='10'){?>  selected <?}?>>直播</option>
                        <option value='11' <?if($search_arr['subjecttype']=='11'){?>  selected <?}?>>课程</option>
                        </select>
                          内容id：<input name="subjectid" style="width:80px" type="text" value="<?= $search_arr['subjectid']?>" class="input-text" />
                          用户名：<input name="sname" style="width:80px" type="text" value="<?= $search_arr['sname']?>" class="input-text" />

                          开始时间:

                  <input type="text" name="start_time" id="start_time" value="<?= $search_arr['start_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
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

                  结束时间： <input type="text" name="end_time" id="end_time" value="<?= $search_arr['end_time'] ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
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
                          <input type="submit" name="search" class="button" value="搜索" />

                          <input type="button" id="btnnew" value="增加评论" size="1px" class="button">
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>
        </th>
      </tr>
        <style type="text/css">
        .content_list td {
          max-width: 400px;
          height: auto;
        }
          </style>
      <tr class="tb_header">
        <th>评论编号</th>
        <th>用户</th>
        <th>类型</th>        
        <th>内容</th>
        <th>评论对象id</th>
        <th>评论类型</th>
        <th>创建时间</th>
        <th>操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
     <td><?= $model['cid'] ?></td>
      <td>
      <?if($model['avatar']){echo  "<img width='40px;' src='".json_decode($model['avatar'])->img->n->url ."' >  ". $model['sname']; }?></td>
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

          case 6:
          echo '正能文章';
          break;
          
          case 7:
          echo '活动文章';
          break;
          
          case 8:
          echo '活动问答';
          break;
          
          case 9:
          echo '能力模型素材';
          break;
          
          case 10:
          echo '直播';
          break;
          
          case 11:
          echo '课程';
          break;
          
          default:
                # code...
          break;
            }
        ?>
      </td> 
      <td><?= date("Y-m-d H:i:s",$model['ctime'])  ?></td>
      <td>
          <a  onclick="del(<?= $model['cid'] ?>,<?= $model['subjecttype'] ?>)">删除</a> 
      </td>
    </tr> 

         <?}?>
         <tr class="operate">
           <td colspan="6">&nbsp;
             <div class="cuspages right">
               <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
             </div>      
           </td>
         </tr>
       </table>
       <div id="_tips"></div>
       <script type="text/javascript">    
       $("#btnnew").click(function(){
          var subjecttype=($("[name='subjecttype']").val());
          var subjectid=($("[name='subjectid']").val());
          var sname=($("[name='sname']").val());


          var content = '/comment/edit?subjecttype='+subjecttype+"&subjectid="+subjectid;
          var title = '添加评论';
          layer.open({
              type: 2,
              title: title,
              maxmin: true,
                  shadeClose: false, //点击遮罩关闭层
                  area : ['800px' , '500px'],
                  content: content
          });
       });   
         //删除帖子
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
</script>