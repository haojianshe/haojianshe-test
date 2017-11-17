  <?php
  use common\widgets\MyLinkPager;
  use common\service\dict\BookDictDataService;
  use common\service\DictdataService;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
   <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="3" >共有<?=$pages->totalCount;?>条记录</th>
        <th colspan="7" style='text-align:right;'>
               <div id="searchid">
                <form name="searchform" action="/lecture/sel" method="get" >
                  <table width="100%" cellspacing="0" class="search-form">
                    <tbody>
                     <tr>
                      <td>
                        <div style="float:right;" class="explain-col"> 
                         标题:<input name="title" type="text" value="<?php echo $title?>"   size="15px"  class="input-text">&nbsp;&nbsp;
                         置顶类型：
                          <select name="ztop">
                                <option value="0"  >请选择</option>
                                <option value="1" <?php if($ztop==1){ echo 'selected=selected';}?> >未置顶</option>
                                 <option value="2" <?php if($ztop==2){ echo 'selected=selected';}?>>置顶</option>
                          </select>
                           &nbsp;&nbsp;
                          主分类：
                         <?php
                         $array = [];
                         foreach(DictdataService::getLectureMainType() as $key=>$val){
                             $array[$val['maintypeid']] = $val['maintypename'];
                         }
                          echo BookDictDataService::createMenuList('f_catalog_id',$array , $f_catalog_id, 'f_catalog_id','','','','','<option value="0" >请选择</option>');
                         ?>
                          &nbsp;&nbsp;
                          子分类：
                    <?php
                    if(empty($s_catalog_id)){
                         echo '<select name="s_catalog_id" id="s_catalog_id">
                         <option value="" >请选择</option>
                          </select>';    
                         }else{
                             $newArray = DictdataService::getLectureSubType($f_catalog_id);
                             if($newArray){
                                 foreach($newArray as $key=>$val){
                                   $array[$val['subtypeid']] = $val['subtypename'];
                                  }
                              echo BookDictDataService::createMenuList('s_catalog_id',$array , $s_catalog_id, 's_catalog_id','','','','','<option value="0" >请选择</option>');
                             }
                         }
                         ?>
                           <input type="hidden" value="" id="chk_value" value='<?php echo $chk_value;?>' name="chk_value"/>
                            <input type="hidden" id="lecture_tagid" name="lecture_tagid" value="<?php echo $lecture_tagid;?>" />
                            <input type="hidden" id="news_data" name="news_data" value="<?php echo $news_data;?>" />
                          <input type="submit" name="search" class="button" value="搜索" />
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>
        </th>
      </tr>
    <script>
     var index = parent.layer.getFrameIndex(window.name);
      $("#f_catalog_id").change(function () {
        var f_catalog_id = $(this).val();
        if(f_catalog_id==0){
             $("#s_catalog_id option").remove();
              $("#s_catalog_id").append('<option value=0>请选择</option>');
             return;
        } 
        $("#dividid tr").remove();
        var url = '/lecture/select_menu';
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
      
      <tr class="tb_header">
        <th >选择</th>
        <th >精讲编号</th>
        <th >分类</th>
        <th >标题</th>
        <th >点赞数</th>
        <th >发布人</th>
        <th >定时发布</th>
        <th >发布日期</th>
        <th >排序</th>
     
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
          <td style=" width:3%"><input type="checkbox" id="check_<?php echo $model['newsid']?>" value="<?php echo $model['newsid']?>" title="<?php echo $model['title']?>" name="checkname1" <?php if(@$model['type']==1){ echo 'checked=checked';}?> /></td>
          <td style=" width:6%"><?= $model['newsid'] ?></td>
      <td style=" width:6%"><?= $model['lecturetype'] ?></td>
      <td><?= $model['title'] ?></td>
      <td><?= $model['supportcount'] ?></td>
      <td><?= $model['username'] ?></td>
      <td>
        <? if($model['publishtime'] ==0) { ?>
          <span style='color:#79a605'>无</span>
        <? } else {?>
          <span style='color:red'><?= date('Y-m-d H:i',$model['publishtime']); ?></span>
        <? } ?>
      </td>
      <td><?= date('Y-m-d',$model['utime']); ?></td>
       <td><?php if($model['stick_date']==0){ echo "<span style=\"color: red\">未置顶</span>";}else{ echo "<span style=\"color: green\">已置顶</span>";} ?></td>
      </tr>
     <?}?>
     <tr class="operate">
        <td colspan="6">
          <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages]); ?>
          </div>      
        </td>
      </tr>
       <tr>
    	<td></td>
    	<td colspan="10" style='text-align:right;' >
	<div>
            <span class="normalbtn_l"><a id="asave" href="#">保存</a></span>
            <span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>
        </div>
        </td>
    </tr>
  </table>

  <div id="_tips"></div>
<script>
    
     //删除联考活动
        $("input[name=checkname1]").click(function () {
             var newsid = $(this).val();
             var lecture_tagid = $("#lecture_tagid").val();
            var ss = $('#check_' + newsid).is(':checked');
            if (ss == true) {
                var status = 1;
            } else {
                var status = 2;
            }
            var url = '/lecture/delvideosubject';
            var data = {
                lecture_tagid: lecture_tagid,
                status: status,
                newsid: newsid
            }
            $.post(url, data, function (m) {
            }, 'json');
        });

  //保存按钮
        $("#asave").click(function () {
            $("form").submit();
            //jqchk();
            parent.location.reload();
            return false;
        });
        
          //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
            parent.location.reload(); 
            parent.layer.close(index);
        });
        
        
   function jqchk(){ //jquery获取复选框值 
        var chk_value =[]; 
        $('input[name="checkname"]:checked').each(function(){ 
        chk_value.push($(this).val()); 
        });
        var news_data = $("#news_data").val();
        if(news_data !=''){
         var  chk_value =news_data+','+chk_value;
        }
        var url = '/lecture/addadvbook';
        var data = {
            chkval:chk_value
        }
        $.post(url,data,function(){
            
        });
    }
        
//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
    addedit($(this).attr("newsid"),$(this).attr("newstype"));
    return false;
});


//置顶按钮
$("a[name=ztop]").click(function () {
    var newsid = $(this).attr("newsid");
    var value = $(this).attr("value");
    layer.confirm('确定操作吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lecture/ztop",
            data: "newsid=" + newsid+"&value="+value,//要发送的数据                    
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

//审核按钮绑定事件
$("a[name=copyNewData]").click(function () {
   var newsid = $(this).attr("newsid");
   var title = $(this).attr("class");
    layer.confirm('是否添加活动？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lecture/copynewdata",
            data: "newsid=" + newsid+"&title="+title,//要发送的数据                    
            success: function (data) {
                if (data.errno == 0) {
                    layer.msg(data.msg,{icon: 1});
                }else if(data.errno == 2) {
                    layer.msg(data.msg,{icon: 1});
                }else if(data.errno == 1) {
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
//审核按钮绑定事件
$("a[name=a_audit]").click(function () {
    var newsid = $(this).attr("newsid");
    layer.confirm('是否确定通过审核？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lecture/audit",
            data: "newsid=" + newsid,//要发送的数据                    
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



</script>