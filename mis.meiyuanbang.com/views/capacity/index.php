  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- 图片浏览 引入结束-->
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr  class="operate">
        <th colspan="2" >
        	共有<?= $pages->totalCount ?>条记录
        </th>
         <th  colspan="5">
               <div id="searchid">
                <form name="searchform" action="/capacity/material" method="get" >
                  <table width="100%" cellspacing="0" class="search-form">
                    <tbody>
                     <tr>
                      <td>
                        <?$classtype_arr=json_decode($classtype,true);?>
                        <div style="float:right;" class="explain-col">    
                          主分类：
                         <select name="f_catalog_id" id="f_catalog_id">
                         <option value="" >请选择</option>
                          <? foreach($classtype_arr['maintype'] as $key=>$value){?>
                            <option <?if($search_arr['f_catalog_id']==$key){echo "selected='selected'";} ?> value="<?=$key?>"><?=$value?></option>
                          <?}?>
                          </select>

                          子分类：
                         <select name="s_catalog_id" id="s_catalog_id">
                         <option value="" >请选择</option>
                         <?if($search_arr['f_catalog_id']){?>
                            <? foreach($classtype_arr['subtype'][$search_arr['f_catalog_id']] as $key=>$value){?>
                              <option <?if($search_arr['s_catalog_id']==$key){echo "selected='selected'";} ?> value="<?=$key?>"><?=$value?></option>
                            <?}?>
                          <?}?>
                          </select>

                          能力分类：
                         <select name="item_id" id="item_id">
                         <option value="" >能力分类</option>
                         <?if($search_arr['f_catalog_id']){?>
                            <? foreach($classtype_arr['captype'][$search_arr['f_catalog_id']] as $key=>$value){?>
                              <option <?if($search_arr['item_id']==$value['itemid']){echo "selected='selected'";} ?> value="<?=$value['itemid']?>"><?=$value['itemname']?></option>
                            <?}?>
                          <?}?>
                          </select>

                          <input type="submit" name="search" class="button" value="搜索" />
                          <input type="button" id="add" value="添加" class="button">
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
        <th>能力素材编号</th>
        <th>一级分类</th>
        <th>二级分类</th>
        <th>能力分类</th>   
        <th>图片</th>
        <th>创建时间</th>
        <th>操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
    <tr class="tb_list">
      <td><?= $model['materialid'] ?></td>
      <td><?= $model['f_catalog']?></td>
      <td><?= $model['s_catalog'] ?></td>
      <td><?= $model['itemname'] ?></td>
      <td>
          <a id="example1" title="<?= $model['f_catalog'].' '.$model['s_catalog'].' '. $model['itemname'] ?>" rel="group<?= $model['materialid']?>" href="<?= json_decode($model['picurl'])->n->url ?>">
      <img style="width:80px;height:80px" src="<?= json_decode($model['picurl'])->n->url ?>"/>
      </a></td>

      <td><?=date("Y-m-d H:i:s",$model['ctime']) ?></td>
      <td>
         <a href="javascript:;"  name='aedit' materialid='<?= $model['materialid']?>'>编辑</a>  
          <a onclick="del(<?= $model['materialid'] ?>)">删除</a> 
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
        //批量添加能力素材
        $("#add").click(function(){
            var content = '/capacity/materialaddh';
            var title = '添加';
            layer.open({
              type: 2,
              title: title,
              maxmin: true,
                  shadeClose: false, //点击遮罩关闭层
                  area : ['90%' , '90%'],
                  content: content
            });
        });

        //更改分类选择
        var classtype='<?=$classtype?>';
        $("#f_catalog_id").change(function() {
             $("#dividid tr").remove();
            var fcid=$("#f_catalog_id  option:selected").val();
            var subtypes=$.parseJSON(classtype).subtype[fcid];
            var captypes=$.parseJSON(classtype).captype[fcid];
            var subhtml='<option value="" >请选择</option>';
            var caphtml='<option value="" >能力分类</option>';
            $.each(subtypes, function(i,val){ 
              subhtml=subhtml+'<option value="'+i+'" >'+val+ "</>";  
            });
            $.each(captypes, function(i,val){ 
              caphtml=caphtml+'<option value="'+val['itemid']+'" >'+val['itemname']+ "</>" ;  
            });
            $("#s_catalog_id").html(subhtml);
            $("#item_id").html(caphtml);
        });    
         //删除能力素材
        function del(materialid){
            layer.confirm('是否删除？', {
              btn: ['删除','否'] //按钮
            }, function(){
              $.ajax({
                type: "post",
                dataType: "json",
                url: "/capacity/materialdel",
                    data: "materialid=" + materialid,//要发送的数据                    
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
   
         //编辑能力模型
        $("a[name=aedit]").click(function () {
          addedit($(this).attr("materialid"));
          return false;
        });
        //编辑方法
        function addedit(materialid){
          var content = '/capacity/materialedit';
          var title = '编辑帖子';
          if(materialid >0){
            content = content + '?materialid=' + materialid; 
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
</script>

<!-- 图片浏览 结束 -->


