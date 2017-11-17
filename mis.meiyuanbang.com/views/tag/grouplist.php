  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
      <thead>
        <tr class="operate">
          <th colspan="2" >
          	共有<?= $pages->totalCount ?>条记录
          </th>
           <th  colspan="5">
               <div id="searchid">
                <form name="searchform" action="/tag/group_list" method="get" >
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
                          <input type="submit" name="search" class="button" value="搜索" />
                          <input type="button" id="add" value="添加标签分类" class="button">
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
      </style>
        <tr class="tb_header">
          <th>模块编号</th>
          <th>一级分类</th>
          <th>二级分类</th>
          <th>名称</th>
          <th>类型</th>
          <th style="width: 100px;">操作</th>
        </tr>
      </thead>
      <!-- 列表 -->
      <? foreach ($models as $model) { ?>
        <tr class="tb_list">
          <td><?= $model['taggroupid'] ?></td>
          <td><?= $model['f_catalog'] ?></td>
          <td><?= $model['s_catalog'] ?></td>
          <td><?= $model['tag_group_name'] ?></td>
          <td><?
            //  标签类型 1单选 2多选
            switch (intval($model['tag_group_type'])  ) {
              case 1:
                echo '单选';
                break;
              case 2:
                echo '多选';
                break;
            }
          ?></td>
          <td>
            <a name='aedit' onclick='addedit(<?= $model['taggroupid'] ?>,"<?= $model['f_catalog'] ?>","<?= $model['s_catalog'] ?>","<?= $model['tag_group_name'] ?>")' >编辑</a>
             <a name='aedit' onclick='manageTags(<?= $model['taggroupid'] ?>,"<?= $model['f_catalog'] ?>","<?= $model['s_catalog'] ?>","<?= $model['tag_group_name'] ?>")' >标签管理</a>
          </td>
        </tr>
      <?}?>

     <!-- 分页 -->
     <tr class="operate">
	      <td colspan="4">
  			<div class="cuspages right">
  			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
  			</div>      
	      </td>
      </tr>
  </table>

<!-- 页面操作逻辑  开始-->
<script type="text/javascript">
    //更改分类选择
    var classtype='<?=$classtype?>';
    $("#f_catalog_id").change(function() {
        var fcid=$("#f_catalog_id  option:selected").val();
        var subtypes=$.parseJSON(classtype).subtype[fcid];
        var subhtml='<option value="" >请选择</option>';
        $.each(subtypes, function(i,val){ 
          subhtml=subhtml+'<option value="'+i+'" >'+val+ "</>";  
        });
     
        $("#s_catalog_id").html(subhtml);
    });    
    //添加标签组
    $("#add").click(function(){
        var fcid=$("#f_catalog_id  option:selected").val();
        var scid=$("#s_catalog_id  option:selected").val();
        if(!(fcid && scid)){
          alert('请选择一级二级分类');
          return ;
        }
        var content = '/tag/group_edit';
        content = content + '?f_catalog_id=' + fcid+"&s_catalog_id="+scid; 
        var title = '添加标签分类';
        layer.open({
          type: 2,
          title: title,
          maxmin: true,
              shadeClose: false, //点击遮罩关闭层
              area : ['600px' , '300px'],
              content: content
        });
    });
    //编辑标签组
    function addedit(id,f_catalog,s_catalog,tag_group_name){
      var content = '/tag/group_edit';
      var title = '编辑标签分类';
      if(id >0){
        content = content + '?id=' + id; 
       title = f_catalog+" "+s_catalog+" "+tag_group_name/*+' 标签列表--:'+ id*/ ;
      }
      layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['600px' , '300px'],
            content: content
        });
    }

    //标签管理
    function manageTags(id,f_catalog,s_catalog,tag_group_name){
      var content = '/tag/tag_list';
      var title = '标签列表';
      if(id >0){
        content = content + '?id=' + id; 
        title = f_catalog+" "+s_catalog+" "+tag_group_name/*+' 标签列表--:'+ id*/ ;
      }
      layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['900px' , '500px;'],
            content: content
        });
    }
</script>
<!-- 页面操作逻辑 结束-->

