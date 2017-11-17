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
<script>
   
    
    function fun() {
        var result = true;
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var subjecttype = $("#subjecttype").val();
      
        var snameid = $("#snameid").val();
       
        if ($.trim(snameid) != '' && subjecttype == 0) {
            layer.msg('请您选择要搜索的用户类型', {icon: 2});
            result = false;
        }
        if($.trim(snameid) == '' && subjecttype>0){
              layer.msg('请您选择要搜索的类型', {icon: 2});
              result = false;
        }
        
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
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="12">
               
                <style type="text/css">
                    .seach_block{
                        display: block;
                        float: left;
                        padding: 2px;
                    }
                </style>
                    <form name="searchform" action="/correct/index" method="get" onsubmit="return fun()">
                        <table width="100%" cellspacing="0" class="search-form">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="explain-col">
                                                                                       创建开始时间:
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
                                            创建结束时间： <input type="text" name="end_time" id="end_time" value="<?php echo @$end_time ?>" class="inputclass1" readonly="readonly" style="width:130px">&nbsp;
                                            
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
                                            <select name='subjecttype' id="subjecttype">
                                                <!-- //0帖子 1用户名 2批改老师 -->
                                                <option  value="0">请选择</option>
                                                <option value=1 <?php if (@$subjecttype == '1') { ?>  selected <?php } ?> >用户名</option>
                                                <option value=2 <?php if (@$subjecttype == '2') { ?>  selected <?php } ?> >批改老师</option>
                                                <option value=3 <?php if (@$subjecttype == '3') { ?>  selected <?php } ?> >用户手机</option>
                                            </select>
                                                 <input name="sname" type="text" size="11px;" value="<?php echo @$sname ?>" id="snameid" class="input-text" />
                                            &nbsp;
                                             <select name='status' id="status">
                                                <option  value="0">请选择</option>
                                                <option value=1 <?php if (@$status == '1') { ?>  selected <?php } ?> >未批改</option>
                                                <option value=2 <?php if (@$status == '2') { ?>  selected <?php } ?> >已批改</option>
                                                <option value=3 <?php if (@$status == '3') { ?>  selected <?php } ?> >已删除</option>
                                            </select>主分类：
                                        <?php
                                        $array = [];
                                        foreach(DictdataService::getTweetMainType() as $key=>$val){
                                            #$array[$val['maintypeid']] = $val['maintypename'];
                                        }
                                         echo BookDictDataService::createMenuList('f_catalog_id',DictdataService::getTweetMainType() , $f_catalog_id, 'f_catalog_id','','','','','<option value="0" >请选择</option>');
                                        ?>
                                         子分类：
                                   <?php
                                   #if(empty($s_catalog_id)){
                                       # echo '<select name="s_catalog_id" id="s_catalog_id"><option value="" >请选择</option></select>';    
                                        #}else{
                                            $newArray = DictdataService::getTweetSubType($f_catalog_id);
                                              # print_r($newArray);
                                             foreach($newArray as $key=>$val){
                                               if($f_catalog_id==$key){
                                                   $array[$key] = $val;
                                               }
                                             }
                                            # print_r($array);
                                             $arr = [];
                                            foreach ($array as $k=>$v){
                                                foreach ($v as $kk=>$vv){
                                                    $arr[$kk]=$vv;
                                                }
                                            }
                                            echo BookDictDataService::createMenuList('s_catalog_id',$arr, $s_catalog_id, 's_catalog_id','','','','','<option value="0" >请选择</option>');
                                      # }
                                        ?>                                            
                                            <input type="submit" name="search" class="button" value="搜索" /> 
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                
            </th>
        </tr>
        <tr><th colspan="12">
                共有<strong><?= $pages->totalCount ?></strong>条记录
            </th></tr>
    <style type="text/css">
        td{
            max-width: 300px;
        }

    </style>
    <tr class="tb_header">
        <th>批改编号</th>
        <th>用户</th>
        <th>批改老师</th>
        <th>标题</th>   
        <th>分类</th>  
        <th>批改状态</th>
        <th>打分</th>
        <th>原图</th>
        <th>批改后图</th>
        <th>提交时间</th>
        <th>批改时间</th>
        <th>操作</th>
    </tr>
</thead>
<?
foreach ($models as $model) { ?>
<tr class="tb_list">
    <td style="width:80px;"><?= $model['correctid'] ?></td>
    <td style="width:100px;" ><?if($model['avatar']){?>
        <img width="40px;" src="<?= json_decode($model['avatar'])->img->n->url ?>">
        <?}?>
        <br/>
        <?= $model['sname'] ?>
        <? if($model['is_vest']){?>
        <span style='color:red;'> (a)</span>
        <?}?>
    </td>
    <td style="width:100px;" >
        <img width="40px;" src="<?= $model['correct_info']['teacher_info']['avatar'] ?>"> 
         <br/>       
        <?= $model['correct_info']['teacher_info']['sname'] ?></td>
    <td style="padding: 5px;"><?= $model['content'] ?></td>
    <td style="width:100px;padding: 5px;"><?= $model['f_catalog'].' '.$model['s_catalog']  ?></td>
	<td width="100px"><?
    switch (intval($model['correct_info']['status'])) {
       // 0未批改  1批改完成  2已删除 3拒批改
        case 0:
            echo "<span style=''> 未批改</span>";
            break;
        case 1:
            echo "<span style=''> 已批改</span>";
            break;  
        case 2:
            if(intval($model['is_del'])==0){
                echo "<span style='color:red;'> 转作品</span><br/>".$model['correct_info']['reasondesc'];
            }else{
                echo "<span style='color:red;'> 已删除</span><br/>".$model['correct_info']['reasondesc'];
            }
            break;
        case 3:
           echo "<span style='color:red;'>已打回</span><br/>".$model['correct_info']['reasondesc'] ;
            break;
    }
     ?></td>
     <td style="width:100px;">
     	<?= $model['correct_info']['scoredetail'] ?>
     </td>
    <td>
        <?foreach ($model['resources'] as $key => $value) { ?>
        <a id="example1" title="<?= $model['correct_info']['scoredetail'] ?>" rel="group<?= $model['tid'] ?>" href="<?= json_decode($value['img'])->s->url ?>">
            <img style="height:50px;width:50px; margin-left:3px;margin-top:3px;" name="prew_resource" src="<?= json_decode($value['img'])->t->url ?>" />
        </a>
        <?}?>
    </td>
    <td>
        <? if($model['type']==4){
        if($model['correct_info']['correct_pic_rid']){
        ?>
        <a id="example1" title="<?= $model['correct_info']['correct_pic']['description'] ?>" rel="group<?= $model['tid'] ?>" href="<?= $model['correct_info']['correct_pic']['img']->n->url ?>">
            <img style="height:50px;width:50px; margin-left:3px;margin-top:3px;" name="prew_resource" src="<?= $model['correct_info']['correct_pic']['img']->n->url ?>" />
        </a>
        <? }}?>
    </td>
    <td width="130px"><?= date("Y-m-d H:i", $model['ctime']) ?></td>
    <td  width="130px"><?if($model['type']==4 && $model['correct_info']['correct_time']){echo date("Y-m-d H:i",$model['correct_info']['correct_time'])   ;}else{echo "";} ?></td>
    <td style="width: 200px;">
         <a onclick="commentList(<?= $model['tid'] ?>,<?= $model['uid'] ?>);"  style="cursor:pointer">评论列表</a> 
         <a onclick="newcomment(<?= $model['tid'] ?>);"  style="cursor:pointer">发评论</a> 
          <?if(intval($model['correct_info']['status']) ==0 or intval($model['correct_info']['status'])==1){?>
         <a  onclick="update_tweet(<?= $model['tid'] ?>)" style="cursor:pointer">变为作品</a> 
         <a onclick="del(<?= $model['tid'] ?>)" style="cursor:pointer">删除</a> 
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
        $("#subjecttype").change(function () {
           $("#snameid").val('');
       });
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
    function del(tid) {
        layer.confirm('是否删除？', {
            btn: ['删除', '否'] //按钮
        }, function () {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/correct/updatestate",
                data: "tid=" + tid + "&is_del=1", //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("访问出错", {icon: 2});
                }
            });

        }, function () {

        });

    }
    //更改批改为作品
    function update_tweet(tid) {
        layer.confirm('是否更改？', {
            btn: ['是', '否'] //按钮
        }, function () {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/correct/updatestate",
                data: "tid=" + tid + "&type=" + 2, //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("访问出错", {icon: 2});
                }
            });
        }, function () {

        });



    }
    //编辑
    $("a[name=aedit]").click(function () {
        addedit($(this).attr("tid"));
        return false;
    });
    //进入编辑页面的弹层函数
    function addedit(tid) {
        var content = '/tweet/edit';
        var title = '编辑帖子';
        if (tid > 0) {
            content = content + '?tid=' + tid;
            title = '编辑帖子';
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: true,
            shadeClose: false, //点击遮罩关闭层
            area: ['450px', '600px'],
            content: content
        });
    }
</script>
<!-- 图片浏览 开始-->
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $("a#example1").fancybox({
            type: 'image',
            afterLoad: function () {
                //this.title = '图片数：' + (this.index + 1) + '/' + this.group.length + (this.title ? ' - ' + this.title : '');
            },
            loop: false,
            padding: 2,
            helpers: {
                title: {
                    type: 'inside'
                }
            }
        });
    });
    
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
</script>
<!-- 图片浏览 结束 -->


