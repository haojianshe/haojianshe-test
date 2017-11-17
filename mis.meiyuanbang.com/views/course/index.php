<?php 
use common\widgets\MyLinkPager;
use mis\service\CourseService;
use common\service\dict\CourseDictDataService;#课程住分类
  use common\service\dict\BookDictDataService;
?>

<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
          <th colspan="11" style='text-align:right;' >
               <div id="searchid">
                <form name="searchform" action="/course/index" method="get"  onsubmit="return fun()">
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
                                 echo BookDictDataService::createMenuList('f_catalog_id',CourseDictDataService::getCourseMainType() , $f_catalog_id, 'f_catalog_id','','','','','<option value="0" >请选择</option>');
                                ?>
                                 &nbsp;
                          子分类：
                    <?php
                       $array =[];
                             $newArray = CourseDictDataService::getCourseSubType();
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
                          &nbsp; &nbsp;<input type="button" onclick="addedit(0);"  id="btnnew" value="新建课程" class="button"/>
                        </div>
                      </td>
                        </tr>
                  </tbody>
                </table>
              </form>
            </div>
        </th>
      <tr class="operate">
        <th colspan="7" >
            共<strong><?= $pages->totalCount ?></strong>条记录
          共<strong><?= $courseCanSum;?></strong>观看过
        </th>
      </tr>
      <tr class="tb_header">
        <th>编号</th>
        <th>用户名</th>
        <th>标题</th>
        <th>浏览量（基础/真实）</th>
        <th>观看人次</th>
        <th>封面</th>
        <th>分类</th>
        <th>管理员</th>
        <th>创建时间</th>
        <th>状态</th>
        <th>操作</th>
      </tr>
    </thead>

    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
        <td><?= $model['courseid'] ?></td>
        <td><?= $model['sname'] ?></td>
        <td><?= $model['title'] ?></td>
        <td><?= $model['hits_basic'] ?>/<?= $model['hits'] ?></td>
        <td><?= CourseService::CourseCanNum($model['courseid']); ?></td>
        <td><img src="<?=$model['thumb_url'] ?>" height="50px" ></td>
        <td><?= $model['f_catalog'] ?>&nbsp;<?= $model['s_catalog'] ?></td>
        <td><?= $model['username'] ?></td>
        <td><?= date('Y-m-d',$model['ctime']); ?></td>
        <td>
          <?if($model['status']==1){?>
            <a name='upadtestatus' onclick="upadtestatus(<?= $model['courseid'] ?>,2);" courseid='<?= $model['courseid'] ?>' href='javascript:;'><span style="color: red;">审核</span></a>&nbsp;&nbsp;&nbsp;&nbsp;
          <?}else{?>
            <span>已审核</span>
          <?}?>
        </td>
        <td width="250px">
          <a name='adel' courseid='<?= $model['courseid'] ?>' target="_black"  href="<?=Yii::$app->params['msiteurl']?>course/detail?courseid=<?= $model['courseid'] ?>">预览</a>&nbsp;&nbsp;&nbsp;&nbsp;
          <a name='aedit' courseid='<?= $model['courseid'] ?>' href='javascript:;'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
          <a  href='/course/section?courseid=<?= $model['courseid'] ?>'>章节管理</a>&nbsp;&nbsp;&nbsp;&nbsp;
          <a name='upadtestatus' onclick="upadtestatus(<?= $model['courseid'] ?>,3);" href='javascript:;'>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
      </tr>
    <?}?>
    <tr class="operate">
      <td colspan="11">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>
      </td>
    </tr>
  </table>
<script>
      $("#f_catalog_id").change(function () {
        var f_catalog_id = $(this).val();
        if(f_catalog_id==0){
             $("#s_catalog_id option").remove();
              $("#s_catalog_id").append('<option value=0>请选择</option>');
             return;
        } 
        $("#dividid tr").remove();
        var url = '/course/select_menu';
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
    
    //编辑按钮绑定事件
    $("a[name=aedit]").click(function () {
    	addedit($(this).attr("courseid"));
        return false;
    });
    //编辑或新增
    function addedit(courseid){
    	var content = '/course/edit';
    	var title = '添加';
    	if(courseid >0){
    		content = content + '?courseid=' + courseid; 
    		title = '编辑--编号:'+ courseid;
    	}
    	layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['90%' , '90%'],
            content: content
      });
    }
    //删除 审核
    function upadtestatus(courseid,status){
      var title;
      if(status==2){
        title="确认通过审核？";
      }else{
        title="是否删除？";
      }
      layer.confirm(
        title, 
        {
          btn: ['是','否']
        },
        function(){
          $.ajax({
            type: "post",
            dataType: "json",
            url: "/course/del",
              data: "courseid=" + courseid+"&status="+status,//要发送的数据
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
        });
    }
</script>