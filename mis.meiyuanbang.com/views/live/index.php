<?php

use common\widgets\MyLinkPager;
use mis\service\UserService;
use common\service\dict\LiveDictService;
use mis\service\CourseService;
  use common\service\dict\BookDictDataService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />

<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">
<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>



<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
           <th colspan="11" style='text-align:right;' >
               <div id="searchid">
                <form name="searchform" action="/live/index" method="get"  onsubmit="return fun()">
                  <table width="100%" cellspacing="0" class="search-form" >
                    <tbody>
                     <tr>
                         <td>
                        <div style="float:left;" class="explain-col"> 
                           <span class="seach_block">
                                      开始时间:
                                            <input type="text" name="start_time" id="start_time" value="<?php echo @$start_time ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "start_time",
                                                    trigger: "start_time",
                                                    dateFormat: "%Y-%m-%d 00:00:00",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>
                                           结束时间： <input type="text" name="end_time" id="end_time" value="<?php echo @$end_time ?>" class="inputclass1" readonly="readonly" style="width:160px">&nbsp;
                                            </span>
                                            <script type="text/javascript">
                                                Calendar.setup({
                                                    weekNumbers: true,
                                                    inputField: "end_time",
                                                    trigger: "end_time",
                                                    dateFormat: "%Y-%m-%d 00:00:00",
                                                    showTime: true,
                                                    minuteStep: 1,
                                                    onSelect: function () {
                                                        this.hide();
                                                    }
                                                });
                                            </script>
                                             主分类：
                                <?php
                                 echo BookDictDataService::createMenuList('f_catalog_id',LiveDictService::getCorrectMainType() , $f_catalog_id, 'f_catalog_id','','','','','<option value="0" >请选择</option>');
                                ?>
                                 &nbsp;
                          子分类：
                    <?php
                            $array = [];
                             $newArray = LiveDictService::getCorrectSubType();
                              foreach($newArray as $key=>$val){
                               if($f_catalog_id==$key){
                                   foreach($val as $k=>$v){
                                       $array[$k] = $v;
                                   }
                                 }
                              }
                              if($array){
                                   echo BookDictDataService::createMenuList('s_catalog_id',$array , $s_catalog_id, 's_catalog_id','','','','','<option value="0" >请选择</option>');
                              }else{
                                  echo '<select id="s_catalog_id" name="s_catalog_id"> <option value="0">请选择</option></select>';
                              }
                         ?>
                           &nbsp;标题:<input name="title" type="text" value="<?php echo $title?>"   size="15px"  class="input-text">
                          &nbsp; &nbsp;
                          <input type="submit" name="search" class="button" value="搜索" />
                          &nbsp; &nbsp;<input type="button"  name="search" class="button" value="新建直播" onclick="addedit(0);">
                        </div>
                      </td>
                        </tr>
                  </tbody>
                </table>
              </form>
            </div>
        </th>
        <tr class="operate">
            <th colspan="2" >
                 共<strong><?= $pages->totalCount ?></strong>条记录
                 
                共<strong><?php
                echo  $liveCanSum;?></strong>观看过
            </th>
        </tr>
    <style type="text/css">
    </style>
    <tr class="tb_header">
        <th style="width: 30px;">编号</th>
        <th style="width: 80px;">标题</th>
        <th style="width: 80px;">用户名</th>
        <th style="width: 80px;">分类</th>
        <th style="width: 100px;">开始时间</th>
        <th style="width: 100px;">结束时间</th>
        <th style="width: 100px;">创建时间</th>
        <th style="width: 100px;">总观看数(期间/结束)</th>
        <th style="width: 100px;">视频回放</th>
        <th style="width: 100px;">创建人</th>
        <th style="width: 100px;">操作</th>
    </tr>
</thead>
<!-- 列表 -->
<? foreach ($models as $model) { ?>
<tr class="tb_list">
    <td><?= $model['liveid'] ?></td>
    <td><?= $model['live_title'] ?></td>
    <td><?php
        echo UserService::findOne(['uid' => $model['teacheruid']])->sname;
        ?></td>
    <td><?= LiveDictService::getCorrectMainTypeNameById($model['f_catalog_id']); ?>-<?php
        $catalogid = LiveDictService::getCorrectSubType($model['s_catalog_id']);
        foreach ($catalogid as $key => $val) {
            foreach ($val as $k => $v) {
                if ($model['s_catalog_id'] == $k) {
                    echo $v;
                }
            }
        }
        ?></td>
    <td><?= date("Y-m-d H:i:s", $model['start_time']); ?></td>
    <td><?php echo date("Y-m-d H:i:s", $model['end_time']); ?></td>
    <td><?php echo date("Y-m-d H:i:s", $model['ctime']); ?></td>
            <td><?php echo CourseService::CourseCanNum($model['liveid'],1); 
            echo '（'.CourseService::CourseCanNum($model['liveid'],1,$model['start_time'],$model['end_time'],1).'/'.CourseService::CourseCanNum($model['liveid'],1,$model['start_time'],$model['end_time'],2).')'; ?></td>
    <td><?php echo $model['videoid']; ?></td>
    <td><?php
        echo $model['username'];
        ?></td>
    <td>
        <?php
        if(time()<$model['start_time']){
        ?>
        <a name='show' href=<?= Yii::$app->params['msiteurl'] . "video/live/live_trailer?liveid={$model['liveid']}" ?> target="_blank"  >预览</a>&nbsp;&nbsp;
        <?php }elseif (time()>$model['start_time']) {
        ?>
          <a name='show' href=<?= Yii::$app->params['msiteurl'] . "video/live/live_stream?liveid={$model['liveid']}" ?> target="_blank"  >预览</a>&nbsp;&nbsp;
        <?php
        } ?>
        <a name='aedit' href="javascript:"   onclick='addedit(<?= $model['liveid'] ?>)' >编辑</a>&nbsp;&nbsp;
        <a name='del' href="javascript:"   liveid="<?= $model['liveid'] ?>" >删除</a>&nbsp;&nbsp;
        <a name='showAdd' href="javascript:"   liveid="<?= $model['liveid'] ?>" >查看地址</a>&nbsp;&nbsp;
    </td>
</tr>
<?}?>

<!-- 分页 -->
<tr class="operate">
    <td colspan="9">
        <div class="cuspages right">
            <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>      
    </td>
</tr>
</table>

<!-- 页面操作逻辑  开始-->
<script type="text/javascript">
    
      $("#f_catalog_id").change(function () {
        var f_catalog_id = $(this).val();
        if(f_catalog_id==0){
             $("#s_catalog_id option").remove();
              $("#s_catalog_id").append('<option value=0>请选择</option>');
             return;
        } 
        $("#dividid tr").remove();
        var url = '/live/select_menu';
        var data = {
            f_catalog_id: f_catalog_id,
            type:2
        }
        $.post(url, data, function (m) {
            $("#s_catalog_id option").remove();
            $("#s_catalog_id").append('<option value=0>请选择</option>');
            $("#s_catalog_id").append(m);
        }, 'json');
    });
    
    
    function func() {
        window.location.href = "/live/recommendlist";
    }
    //删除按钮绑定事件
    $("a[name=del]").click(function () {
        var liveid = $(this).attr("liveid");
        layer.confirm('删除后将不可恢复，确定删除吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/live/del",
                data: "liveid=" + liveid, //要发送的数据                    
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
            //取消
        });
        return false;
    });

    //iframe层-多媒体
    function preview(videourl) {
        layer.open({
            type: 2,
            title: false,
            area: ['630px', '360px'],
            shade: 0.8,
            closeBtn: 1,
            shadeClose: true,
            content: videourl
        });
    }

    //编辑
    function addedit(id) {
        var content = '/live/edit';
        var title = '编辑直播';
        if (id > 0) {
            content = content + '?id=' + id;
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['90%', '80%'],
            content: content
        });
    }

    $("a[name=showAdd").click(function () {
        var content = '/live/show_url';
        var liveid = $(this).attr("liveid");
       
        var title = '获取推流地址和观看地址';
        content = content + '?liveid=' + liveid;
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['65%', '65%'],
            content: content
        });
        return false;
    });

</script>
<!-- 页面操作逻辑 结束-->