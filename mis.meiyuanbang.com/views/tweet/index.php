  <?php
  use common\widgets\MyLinkPager;
    use common\service\dict\BookDictDataService;
  use common\service\DictdataService;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>


<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
      <style type="text/css">
      td{
        max-width: 300px;
      }
      </style>
<script>
    function fun() {
        var result = true;
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        if (start_time != '' || end_time != '') {
            if (start_time > end_time) {
                layer.msg('开始时间不能大于结束时间，请你重新选择结束时间', {icon: 2});
                $("#end_time").val('');
                $("#end_time").focus();
                result = false;
            }
            if (start_time == end_time) {
               layer.msg('开始时间不能等于于结束时间，请你重新选择', {icon: 2});
                $("#end_time").val('');
                $("#end_time").focus();
                result = false;
            }
        }
        if (start_time == '' && end_time != '') {
           layer.msg('开始时间不能为空，请你选择开始时间', {icon: 2});
            $("#start_time").val('');
            $("#start_time").focus();
            result = false;
        }
        return result;
    }
</script>
<!-- 图片浏览 引入结束-->
  <table cellspacing="0" cellpadding="0" class="content_list" >
    <thead>
      <tr class="operate">      
         <th colspan="9">
               <div id="searchid">
                <form name="searchform" action="/tweet/index" method="get" >
                  <table width="100%" cellspacing="0" class="search-form">
                    <tbody>
                     <tr>
                      <td>
                        <div class="explain-col">
                                  <span class="seach_block">
                                      开始时间:
                                            <input type="text" name="start_time" id="start_time" value="<?php echo @$start_time ?>" class="inputclass1" readonly="readonly" style="width:130px">&nbsp;
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "start_time",
                                                    trigger: "start_time",
                                                    dateFormat: "%Y-%m-%d %H:%M",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>
                                           结束时间： <input type="text" name="end_time" id="end_time" value="<?php echo @$end_time ?>" class="inputclass1" readonly="readonly" style="width:130px">&nbsp;
                                            </span>
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "end_time",
                                                    trigger: "end_time",
                                                    dateFormat: "%Y-%m-%d %H:%M",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>
                                            <br/>
                                            用户名：<input name="sname" type="text" size="8px;" value="<?php echo isset($sname)?$sname:""?>" class="input-text" />
                                            主分类：
                         <?php
                         $array = [];
                         foreach(DictdataService::getTweetMainType() as $key=>$val){
                             #$array[$val['maintypeid']] = $val['maintypename'];
                         }
                          echo BookDictDataService::createMenuList('f_catalog_id',DictdataService::getTweetMainType() , $f_catalog_id, 'f_catalog_id','','','','','<option value="0" >请选择</option>');
                         ?>
                          子分类：
                    <?php
                             $newArray = DictdataService::getTweetSubType($f_catalog_id);
                              foreach($newArray as $key=>$val){
                                if($f_catalog_id==$key){
                                    $array[$key] = $val;
                                }
                              }
                              $arr = [];
                             foreach ($array as $k=>$v){
                                 foreach ($v as $kk=>$vv){
                                     $arr[$kk]=$vv;
                                 }
                             }
                             echo BookDictDataService::createMenuList('s_catalog_id',$arr, $s_catalog_id, 's_catalog_id','','','','','<option value="0" >请选择</option>');
                         ?>
                          &nbsp;
                          类别：
                          <select name="newstype">
                                <option value="0"  >请选择</option>
                                <option value="1" <?php if($newstype==1){ echo 'selected=selected';}?> >素材</option>
                               <option value="2" <?php if($newstype==2){ echo 'selected=selected';}?>>普通作品</option>
                          </select>
                        <input type="submit" name="search" class="button" value="搜索" />
                         <input type="type"  name="search" style="height:22px; width:24px;" class="button" value="发帖" onclick="addedit_tweet();">
                         <input type="type"  name="search" style="height:22px; width:60px;"  class="button" value="批量传素材" onclick="addtweetmaterial();">
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>
        </th>
         
      </tr>
    <th colspan="9" >共有<strong><?= $pages->totalCount ?></strong>条记录&nbsp;&nbsp;&nbsp;共<strong><?= $count ?></strong>个点击量</th>
      <tr class="tb_header">
        <th>帖子编号</th>
        <th>用户</th>
        <th>标题</th>
        <th>类型</th> 
        <th>分类</th>   
        <th>图片</th>
        <th>标签</th>
        <th>点击量</th>
        <th>创建时间</th>
        <th>推荐 加精</th>
        <th>操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
      <td><?= $model['tid'] ?></td>
      <td><?= $model['sname']?>
          <? if($model['is_vest']){?>
          <span style='color:red;'> (a)</span>
          <?}?>
      </td>
      <td><?= $model['content'] ?></td>
      <td><?switch (intval($model['type'])) {
         case 1:
           echo '素材';
           break;
         case 2:
           echo '普通作品';
           break;
         default:
           # code...
           break;
       } ?></td>
      
      <td><?= $model['f_catalog'].'  '.$model['s_catalog'] ?></td>
      <td>
      <?foreach ($model['resources'] as $key => $value) { ?>
          <a id="example1" title="<?= $value['description'] ?>" rel="group<?= $model['tid'] ?>" href="<?= json_decode($value['img'])->s->url ?>">
          	<img style="height:50px;width:50px; margin-left:3px;margin-top:3px;" name="prew_resource" src="<?= json_decode($value['img'])->t->url ?>" />
          </a>
      <?}?>
      </td>
      <td><?= $model['tags'] ?></td>
      <td><?= $model['hits'] ?></td>
      <td><?= date("Y-m-d H:i:s",$model['ctime'])  ?></td>
      <td><? if($model['flag']==0){
            echo '无';
          }elseif ($model['flag']==1) {
           echo '加精';
          }elseif ($model['flag']==2) {
          echo '推荐';
           }elseif ($model['flag']==3) {
          echo '已推荐并加精';
          }
          ?>
      </td>
      <td>
          <a onclick="commentList(<?= $model['tid'] ?>,<?= $model['uid'] ?>);"  style="cursor:pointer">评论列表</a> 
         <a onclick="newcomment(<?= $model['tid'] ?>);"   style="cursor:pointer">发评论</a> 
         <a href="javascript:;"  name='aedit' tid='<?= $model['tid']?>' style="cursor:pointer">编辑</a>  
           <?if($model['type']<3) {?>
          <a  onclick="del(<?= $model['tid'] ?>)" style="cursor:pointer">删除</a> 
          <?}?>
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
      var index = parent.layer.getFrameIndex(window.name);
      $("#f_catalog_id").change(function () {
        var f_catalog_id = $(this).val();
        if(f_catalog_id==0){
             $("#s_catalog_id option").remove();
              $("#s_catalog_id").append('<option value=0>请选择</option>');
             return;
        } 
        $("#dividid tr").remove();
        var url = '/tweet/select_menu';
        var data = {
            f_catalog_id: f_catalog_id,
            tag:1,
            type:2
        }
        $.post(url, data, function (m) {
            $("#s_catalog_id option").remove();
           // $("#s_catalog_id").append('<option value=0>子分类</option>');
            $("#s_catalog_id").append(m);
        }, 'json');
    });
     //删除帖子
    function del(tid){
            layer.confirm('是否删除？', {
        btn: ['删除','否'] //按钮
      }, function(){
        $.ajax({
          type: "post",
          dataType: "json",
          url: "/tweet/updatestate",
              data: "tid=" + tid+"&is_del=1",//要发送的数据                    
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
      //alert(tid);

    }
    //更新状态 加精 推荐
    function update_flag(obj,tid,flag) {
     $.ajax({
      type: "post",
      dataType: "json",
      url: "/tweet/updatestate",
              data: "tid=" + tid+"&flag=" + flag,//要发送的数据                    
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

    }
     //编辑
    $("a[name=aedit]").click(function () {
      addedit($(this).attr("tid"));
      return false;
    });
    //进入编辑页面的弹层函数
    function addedit(tid){
      var content = '/tweet/edit';
      var title = '编辑帖子';
      if(tid >0){
        content = content + '?tid=' + tid; 
        title = '编辑帖子';
      }
      layer.open({
        type: 2,
        title: title,
        maxmin: true,
            shadeClose: false, //点击遮罩关闭层
            area : ['90%' , '90%'],
            content: content
          });
      }
</script>
<!-- 图片浏览 开始-->
<script language="javascript" type="text/javascript"> 
  $(document).ready(function() {
     $("a#example1").fancybox({
      type:'image',
      afterLoad : function() {
          this.title = '图片数：' + (this.index + 1) + '/' + this.group.length + (this.title ? ' - ' + this.title : '');
        },
        loop:false,
      padding: 2,
      helpers : {
          title : {
            type : 'inside'
          }
      }
     });
  });
  
      
      //批量传素材
      function addtweetmaterial(){
        var content = '/vesttweet/addmaterial';
        var title = '添加帖子';
        layer.open({
              type: 2,
              title: title,
              maxmin: false,
              shadeClose: false, //点击遮罩关闭层
              area : ['700px' , '800px'],
              content: content
          });
      }
         //评论列表
      function commentList(tid,uid){
        var content = '/vesttweet/comment?tid='+tid+"&uid="+uid;
        var title = '评论列表';
        layer.open({
              type: 2,
              title: title,
              maxmin: false,
              shadeClose: false, //点击遮罩关闭层
              area : ['50%' , '80%'],
              content: content
          });
      }
          function newcomment(tid){
        var content = '/vesttweet/newcomment';
        var title = '发表评论';
        if(tid >0){
          content = content + '?tid=' + tid; 
          title = '发表评论';
        }
        layer.open({
          type: 2,
          title: title,
          maxmin: true,
              shadeClose: false, //点击遮罩关闭层
              area : ['450px' , '400px'],
              content: content
          });
     }
     
      //用户发帖（马甲）
      function addedit_tweet(){
        var content = '/vesttweet/add';
        var title = '添加帖子';
        layer.open({
              type: 2,
              title: title,
              maxmin: false,
              shadeClose: false, //点击遮罩关闭层
              area : ['700px' , '800px'],
              content: content
          });
          return false;
      }
</script>
<!-- 图片浏览 结束 -->


