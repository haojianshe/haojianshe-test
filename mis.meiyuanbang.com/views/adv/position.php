  <?php
  use common\widgets\MyLinkPager;
  use mis\service\AdvRecordService;

  ?>
    <link rel="stylesheet" type="text/css" href='/static/css/pager.css'> 

  <link rel="stylesheet" type="text/css" href="/static/css/buttons.css">

  <link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">

  <script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
  <script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="6">      

            <form name="searchform" action="/adv/position" method="get">
              共有<?= $pages->totalCount ?>条记录
              <!-- 分类筛选 -->
              <input type="checkbox" name="overtime" <? if($search['overtime']==1){echo "checked='checked'";} ?> value="1" ><span style="font-size: 14px;">将过期</span>
              分类： 
              <span id="type_ctalog"></span>
              <span id="fcatalog"></span>
              <span id="scatalog"></span>
              <span id="tcatalog"></span>

              省级：
              <select name="provinceid">
                <option value="">请选择</option>
                <? foreach ($province as $key => $value) {
                  ?><option <?if($search['provinceid']!=NULL && $search['provinceid']==$value['provinceid']){echo "selected='selected'" ;} ?> value="<?=$value['provinceid']?>"><?=$value['provincename']?></option><?
                } ?>
              </select>
                  开始时间: <input type="text" name="stime" id="stime"  class="inputclass1" readonly="readonly"  value="<?= $search['stime'] ?>" style="width:160px">&nbsp;
                  <script type="text/javascript">
                    Calendar.setup({
                      weekNumbers: true,
                      inputField : "stime",
                      trigger    : "stime",
                      dateFormat: "%Y-%m-%d 00:00:00",
                      showTime: true,
                      minuteStep: 1,
                      onSelect   : function() {this.hide();}
                    });
                  </script>         

                  结束时间： <input type="text" name="etime" id="etime"  class="inputclass1" readonly="readonly"  value="<?= $search['etime'] ?>" style="width:160px">&nbsp;
                  <script type="text/javascript">
                    Calendar.setup({
                      weekNumbers: true,
                      inputField : "etime",
                      trigger    : "etime",
                      dateFormat: "%Y-%m-%d 00:00:00",
                      showTime: true,
                      minuteStep: 1,
                      onSelect   : function() {this.hide();}
                    });
                  </script>  

             
            <script type="text/javascript">

            function getFcatalog(arr,typename,type){
                var html='';
                //name="adv_f_catalog_id"
                html+='<select hidden data-type='+type+' class="detail" id="'+arr.typeid+'detail'+'" >';
                html+='<option value="">请选择</option>';
                var s_arr=new Array()
                for(var item1 in arr[typename]) {
                     html+='<option value="'+arr[typename][item1].id+'">'+arr[typename][item1].name+'</option>';
                          if(type==1){
                            var s_arr=arr[typename][item1].catalog;

                            $("#scatalog").append(getLastcatalog(s_arr,type+"-"+arr[typename][item1].id,'adv_s_catalog_id'));
                            //$("#scatalog").append(getLastcatalog(s_arr,"idclass"));
                          }else if(type==2){
                            var s_arr=arr[typename][item1].catalog;
                            var shtml='';
                            //name="adv_s_catalog_id"
                            shtml+='<select hidden data-type='+type+' data-stype="'+type+"-"+arr[typename][item1].id+''+'" id="'+type+"-"+arr[typename][item1].id+''+'" >';
                            shtml+='<option value="">请选择</option>';
                            for(var item2 in s_arr) {
                                shtml+='<option value="'+s_arr[item2].id+'">'+s_arr[item2].name+'</option>';
                                $("#tcatalog").append(getLastcatalog(s_arr[item2].scatalogs,type+"-"+arr[typename][item1].id+"-"+s_arr[item2].id,'adv_t_catalog_id'));
                            }
                            shtml+='</select>';
                            $("#scatalog").append(shtml);
                           /* var arr_s_item=new Array();
                            arr_s_item[arr[typename][item1].id]=arr[typename][item1].name;
                            s_arr.push(arr_s_item);
                            $("#scatalog").append(getLastcatalog(s_arr,"idclass"));*/
                          } 
                     

                }
                
                html+='</select>';
                return html;
            }

            function getLastcatalog(arr,idclass,nameclass){
                var html='';
                //name="'+nameclass+'"
                html+='<select hidden id="'+idclass+'" >';
                html+='<option value="">请选择</option>';
                for(var item1 in arr) {
                     html+='<option value="'+item1+'">'+arr[item1]+'</option>';
                }
                html+='</select>';
                return html;
            }

            var catalog=eval('<?=json_encode($catalog)?>');
            var type_ctalog='';
            type_ctalog+='<select id="pos_type" name="pos_type">';
            type_ctalog+='<option value="">请选择</option>';
            for(var item in catalog) {
                type_ctalog+='<option   value="'+catalog[item].typeid+'">'+catalog[item].typename+'</option>';
                if(catalog[item].typeid==2){
                    $("#fcatalog").append(getFcatalog(catalog[item],'details',2));
                 }else if(catalog[item].typeid==1){
                    $("#fcatalog").append(getFcatalog(catalog[item],'list',1));
                 }
             
           }
           type_ctalog+='</select>';
            $("#type_ctalog").html(type_ctalog);
            $(document).ready(function(){
                //分类选择
                $("#pos_type").click(function(){
                  $(".detail").hide();
                  $(".detail").removeAttr("name");                  
                  $("#"+this.value+"detail").show();
                  $("#"+this.value+"detail").attr("name",'adv_f_catalog_id');
                })
                //一级分类
                $(".detail").click(function(){
                  $("#scatalog select").hide();
                  $("#scatalog select").removeAttr("name");    
                  $("#"+$(this).data("type")+"-"+this.value).show();
                  $("#"+$(this).data("type")+"-"+this.value).attr("name",'adv_s_catalog_id');
                });


                $("#scatalog select").click(function(){
                  $("#tcatalog select").hide();
                  $("#tcatalog select").removeAttr("name");    
                  $("#"+$(this).data("stype")+"-"+this.value).show();
                  $("#"+$(this).data("stype")+"-"+this.value).attr("name",'adv_t_catalog_id');
                });

                var adv_f_catalog_id =<?=$search['adv_f_catalog_id'] ?>;
                var adv_s_catalog_id =<?=$search['adv_s_catalog_id'] ?>;
                var adv_t_catalog_id =<?=$search['adv_t_catalog_id'] ?>;
                var pos_type =<?=$search['pos_type'] ?>;

                if(pos_type>0){
                  $("#pos_type option[value='"+pos_type+"']").attr("selected",true);
                  if(adv_f_catalog_id>0){
                      $("#"+pos_type+"detail").show();
                      $("#"+pos_type+"detail").attr("name",'adv_f_catalog_id');
                      $("#"+pos_type+"detail option[value='"+adv_f_catalog_id+"']").attr("selected",true);
                      if(adv_s_catalog_id>0){
                          $("#"+pos_type+"-"+adv_f_catalog_id).show();
                          $("#"+pos_type+"-"+adv_f_catalog_id).attr("name",'adv_s_catalog_id');
                          $("#"+pos_type+"-"+adv_f_catalog_id+" option[value='"+adv_s_catalog_id+"']").attr("selected",true);
                          if(adv_t_catalog_id>0){
                              $("#"+pos_type+"-"+adv_f_catalog_id+"-"+adv_s_catalog_id).show();
                              $("#"+pos_type+"-"+adv_f_catalog_id+"-"+adv_s_catalog_id).attr("name",'adv_t_catalog_id');
                              $("#"+pos_type+"-"+adv_f_catalog_id+"-"+adv_s_catalog_id+" option[value='"+adv_t_catalog_id+"']").attr("selected",true);
                          }
                      }
                  }
                }
            });
            </script>
            <input type="submit" name="search" class="button button-primary button-tiny" value="搜索">
            </form>
        </th>

      </tr>
      <tr class="tb_header">
        <th style='width:80px'>编号</th>
        <th>广告位id</th>
        <th style='width:80px'>广告主</th>
        <th style='width:80px'>广告标题</th>
        <th>投放区域</th>
        <th>投放时间</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['advrecid'] ?></td>
      <td>
      <? 
        switch ($model['pos_type']) {
          case 1:
            $ids=$model['pos_type'].'-'.$model['adv_f_catalog_id'].'-'.$model['adv_s_catalog_id'];
            $catalog=AdvRecordService::getCatalogById($ids);
             echo $catalog['typename'].' '.$catalog['fcatlog'].' '.$catalog['scatlog'];
            break;
          case 2:
            $ids=$model['pos_type'].'-'.$model['adv_f_catalog_id'].'-'.$model['adv_s_catalog_id'].'-'.$model['adv_t_catalog_id'];
            $catalog=AdvRecordService::getCatalogById($ids);
            echo $catalog['typename'].' '.$catalog['fcatlog'].' '.$catalog['scatlog'].' '.$catalog['tcatlog'];
            break;
          default:
            break;
        }
       ?>
      </td>
      <td><?= $model['name'] ?></td>
      <td><?= $model['title'] ?></td>
      <td ><div onclick="changeHeight(this);" style="width:230px;height: 20px;overflow: hidden; text-overflow: ellipsis;white-space:nowrap;"><?= $model['province'] ?></div></td>
      <td><?= date("Y-m-d", $model['stime']) ?> - <?= date("Y-m-d", $model['etime'])?></td>
      </tr>
     <?}?>
      <tr class="operate">
        <td colspan="6">
          <div class="cuspages right">
            <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
          </div>
        </td>
    </tr>
  </table>
<script>

function changeHeight(obj){
  console.log($(obj).css('height'));
  if($(obj).css('height')=='20px'){
      $(obj).css('height','auto');
       $(obj).css('white-space','');
      
  }else{
      $(obj).css('height','20px');
      $(obj).css('white-space','nowrap');
  }
 
}
//新建按钮绑定事件
$('#btnnew').on('click', function(){
  addedit(0);
});

//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
  addedit($(this).attr("advid"));
    return false;
});

//删除按钮绑定事件
$("a[name=adel]").click(function () {
  var advid = $(this).attr("advid");
    layer.confirm('删除后将不可恢复，确定删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/posid/del",
            data: "advid=" + advid,//要发送的数据                    
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

//编辑或新增用户页面
function addedit(advid){
  var content = '/adv/edit?advuid=';
  var title = '添加推荐位';
  if(advid >0){
    content = content + '&advid=' + advid; 
    title = '编辑推荐位--编号:'+ advid;
  }
  layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '85%'],
        content: content
    });
}

</script>