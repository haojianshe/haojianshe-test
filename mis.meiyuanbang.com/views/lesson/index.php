  <?php
use common\widgets\MyLinkPager;
  use common\service\dict\BookDictDataService;
  use common\service\DictdataService;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="9" >
           
             <div id="searchid">
                <form name="searchform" action="/lesson/index" method="get"  onsubmit="return fun()">
                  <table width="100%" cellspacing="0" class="search-form" >
                    <tbody  style=" border: 1px solid #ddd;">
                     <tr >
                      <td>
                          <div style="float:left;" class="explain-col"> 
                           &nbsp;
                          主分类：
                         <?php
                         $array = [];
                         foreach(DictdataService::getLessonMainType() as $key=>$val){
                             $array[$val['maintypeid']] = $val['maintypename'];
                         }
                          echo BookDictDataService::createMenuList('f_catalog_id',$array , $f_catalog_id, 'f_catalog_id','','','','','<option value="0" >请选择</option>');
                         ?>
                          &nbsp;
                          子分类：
                    <?php
                    if(empty($s_catalog_id || $f_catalog_id)){
                         echo '<select name="s_catalog_id" id="s_catalog_id">
                         <option value="" >请选择</option>
                          </select>';    
                         }else{
                             $newArray = DictdataService::getLessonSubType($f_catalog_id);
                              foreach($newArray as $key=>$val){
                               $arrays[$val['subtypeid']] = $val['subtypename'];
                              }
                             echo BookDictDataService::createMenuList('s_catalog_id',$arrays , $s_catalog_id, 's_catalog_id','','','','','<option value="0" >请选择</option>');
                         }
                         ?> 
                          &nbsp;
                          标题:
                         <input name="title" type="text" value="<?php echo @$title?>"   size="15px"  class="input-text">
                           &nbsp; &nbsp; 
                             <input type="submit" name="search" class="button" value="搜索" />
                          <td>
                            <div style="float:right;" class="explain-col"> 
                             <input type="button" id="btnnew" value="新建考点" class="button"/>
                            </div> 
                      </td>
                   </tr>
                  </tbody>
                  
                </table>
                    
              </form>
            </div>
        </th>
      
        
        <tr class="operate" >
          <th >  共有<?= '<strong>'.$pages->totalCount.'</strong>' ?>条记录&nbsp;&nbsp;浏览数<?php echo '<strong>'.$counts.'</strong>'?></th>
       </tr>
      
      <tr class="tb_header">
        <th >考点编号</th>
        <th >分类</th>
        <th >标题</th>
        <th >浏览量</th>
        <th >发布人</th>
        <th >创建时间</th>
        <th >当前状态</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['lessonid'] ?></td>
      <td><?= $model['lessontype'] ?></td>
      <td><?= $model['title'] ?></td>
      <td><?= $model['hits'] ?></td>
      <td><?= $model['username'] ?></td>
      <td><?= date('Y-m-d H:i:s',$model['ctime']); ?></td>
      <td>
      	<? if($model['status']==0){echo "<span class='green'>已发布</span>";}else{echo "<span class='need'>未发布</span>";} ?>
      
      </td>
      <td>
      	<? if($model['status']==2){ ?>
      	<a name='apublish' lessonid='<?= $model['lessonid'] ?>' href='#'>发布考点</a>
      	<? }else{ ?>
      	<a name='acancelpublish' lessonid='<?= $model['lessonid'] ?>' href='#'>取消发布</a>
      	<? } ?>
      	&nbsp;&nbsp;&nbsp;&nbsp;
      	<a href='/lesson/dashboard?lessonid=<?= $model['lessonid'] ?>'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='adel' lessonid='<?= $model['lessonid'] ?>' href='#'>删除</a>
      </td>
      </tr>
     <?}?>
     <tr class="operate">
	      <td colspan="7">
			<div class="cuspages right">
			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
			</div>      
	      </td>
      </tr>
  </table>
<div id="_tips"></div>
<script>
//新建按钮绑定事件
$('#btnnew').on('click', function(){
	addedit(0);
});

//删除按钮绑定事件
$("a[name=adel]").click(function () {
	var lessonid = $(this).attr("lessonid");
    layer.confirm('删除后将不可恢复，确定删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lesson/del",
            data: "lessonid=" + lessonid,//要发送的数据                    
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
        //取消
    });
    return false;
});
//发布考点
$("a[name=apublish]").click(function () {
	var lessonid = $(this).attr("lessonid");
    layer.confirm('确定发布考点吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lesson/publish",
            data: "lessonid=" + lessonid,//要发送的数据                    
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
        //取消
    });
    return false;
});
//取消发布
$("a[name=acancelpublish]").click(function () {
	var lessonid = $(this).attr("lessonid");
    layer.confirm('取消发布后，客户端将不能访问，确定取消吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lesson/cancelpublish",
            data: "lessonid=" + lessonid,//要发送的数据                    
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
        //取消
    });
    return false;
});
//编辑或新增考点页面
function addedit(lessonid){
	var content = '/lesson/edit';
	var title = '添加考点';
	if(lessonid >0){
		content = content + '?lessonid=' + lessonid; 
		title = '编辑考点基本信息--编号:'+ lessonid;
	}
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '530px'],
        content: content
    });
}


      var index = parent.layer.getFrameIndex(window.name);
      $("#f_catalog_id").change(function () {
        var f_catalog_id = $(this).val();
        if(f_catalog_id==0){
             $("#s_catalog_id option").remove();
              $("#s_catalog_id").append('<option value=0>请选择</option>');
             return;
        } 
        $("#dividid tr").remove();
        var url = '/lesson/select_menu';
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
</script>