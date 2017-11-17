  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- 图片浏览 引入结束-->
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>    
    <tr class="operate">
       
         <th colspan="7">
               <div id="searchid">
                <form name="searchform" action="<?=$model['url']?>" method="get" >
                  <table width="100%" cellspacing="0" class="search-form">
                    <tbody>
                     <tr>
                      <td>
                        <div class="explain-col" style="float:left;margin-bottom:5px;">
                            <span style="font-size:16px;font-weight :700;">分类：</span>
                            <select name="f_catalog" id="f_catalog">
                            <?if(empty($model['f_catalog'])){ ?>
                              <option value="" selected="">一级分类</option>
                              <?}?>
                              <? foreach ($catalog['imgmgr_level_1'] as $key => $value) {?>            
                                <option value="<?=$value?>" key="<?=$key?>" <?if ($value==$model['f_catalog']) {?>selected<?} ?>>
                                  <?=$value?>
                                </option>
                              <?}?>
                            </select>

                            <select name="s_catalog" id="s_catalog">
                              <?if(empty($model['s_catalog'])){ ?>
                                <option value="" selected="">二级分类</option>
                                <?}else{?>
                                <option value="<?= $model['s_catalog'] ?>" selected=""><?= $model['s_catalog'] ?></option>
                              <?}?>
                            </select>
                          
                            <input type='hidden' id='tags_value' class="inputclass1" id='tags_hidden' name="tags"  value="<?= $model['tags'] ?>" />
                            标题：
                            <input  id='content' class="inputclass1" id='tags_hidden' name="content"  value="<?= $model['content'] ?>" />
                             &nbsp;<input type="submit" name="search" class="button" value="搜索" />
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      <div class="explain-col" style="float:left;margin-top:5px;margin-bottom:5px;">
                        <span style="font-size:16px;font-weight :700;">标签：</span>
                          <span id='tags'>
                            <? 
                            foreach (explode(",", $model['tags']) as $tagkey => $tagvalue) {?>
                              <select name="tags1[<?=$tagkey?>]" >
                                <option  value="<?=$tagvalue?>" selected>
                                  <?if(empty($model['tags'])){?>
                                  无
                                  <?}?>
                                  <?=$tagvalue?></option>
                              </select>
                              <?} ?>
                            </span>
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
      <th style="width:5%">选择</th>
      <th style="width:25%">标题</th>
      <th style="width:15%">图片id</th>
      <th style="width:10%">图片</th>      
      <th style="width:10%">一级分类</th>
      <th style="width:15%">二级分类</th>
      <th style="width:15%">标签</th>
        
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list" name="user">
      <td><input type="checkbox" class="selteacher" <?if(in_array( $model['resource_id'] , $rids)){echo "checked='checked'";}?> data-title="<?= json_decode($model['resources'][0]['img'])->s->url ?>" value="<?= $model['resource_id'] ?>"  /></td>
      <td><?= $model['content'] ?></td>     
      <td><?= $model['resource_id'] ?></td>
      <td>
      <?foreach ($model['resources'] as $key => $value) { ?>
          <a id="example1" title="<?= $value['description'] ?>" rel="group<?= $model['tid'] ?>" href="<?= json_decode($value['img'])->s->url ?>">
            <img style="height:50px;width:50px; margin-left:3px;margin-top:3px;" name="prew_resource" src="<?= json_decode($value['img'])->t->url ?>" />
          </a>
      <?}?>
      </td>
      <td><?= $model['f_catalog'] ?></td>
      <td><?= $model['s_catalog'] ?></td>
      <td><?= $model['tags'] ?></td>
      </tr> 
     <?}?>
      <tr class="operate">
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
      <tr class="operate">
         <td colspan="6">&nbsp;
           <div class="cuspages right">
             <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
           </div>      
         </td>
       </tr>
     <tr>
        <td colspan=8>
          <div style="margin-left:40%;margin-top:30px;">
            <span class="normalbtn_l"><a id="asave" href="#asave">确认添加</a></span>
            <span class="normalbtn_l"><a id="aclose" href="#aclose">关闭</a></span>
          </div>
        </td>
      </tr> 
  </table>  

<script>
      //获取url参数
      function getQueryString(name)
      {  
         var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
         var r = window.location.search.substr(1).match(reg);
         if(r!=null)return  unescape(r[2]); return null;
      }
      /**
       * 判断数组中是否包含元素
       * @param  {[type]} str [description]
       * @param  {[type]} arr [description]
       * @return {[type]}     [description]
       */
      function in_arr(str,arr){
        var ret=false;
        for (var i = arr.length - 1; i >= 0; i--) {
          if(arr[i]==str){
            ret=true;
          }
        }
        return ret;
      }
      $("#asave").click(function(){
          //获取图片id url存储到二维数组
          var imginfos=$(parent.$(".imginfo"));
          var sources=new Array();
          var source=new Array();
          for (var i = 0; i < imginfos.length; i++) {
                source['rid']=$(imginfos[i]).data("id");
                source['url']=$(imginfos[i]).attr('src');
                sources.push(source);
                source= new Array();
          }
         
          var index = parent.layer.getFrameIndex(window.name);
          //获取uid 数组
          var rids=$(parent.$("#rids")).val().split(",");
          //var title=$(parent.$(".imglist")[0]).html();
          //用于存储新增的单个图片rid url数组
          var newimg=new Array();
          $('input:checkbox').each(function() {
                  if ($(this).is(':checked')) {
                      //判断是否已经有该图片 若没有加入二维数组
                      if(!in_arr($(this).val(),rids) ){
                          newimg['rid']=$(this).val();
                          newimg['url']=$(this).data("title");
                          sources.push(newimg);
                          newimg=new Array();
                      }
                     
                  }
          });
          //获取最终填充数值及展示图片字符串
          var rids_r='';
          var img_r="";
          for (var i = sources.length - 1; i >= 0; i--) {
              rids_r=rids_r+sources[i]['rid']+",";
              img_r=img_r+"<a id='example"+sources[i]['rid']+"' onClick='del("+sources[i]['rid']+")'><img class='imginfo' data-id='"+sources[i]['rid']+"' style='width:80px;height:80px;padding-left:3px;padding-top:3px;' src='"+sources[i]['url']+"' /></a>";
          }
          sources=new Array();
          //设置rids返回值
          parent.$("[name='MatreialSubjectService[rids]']").val(rids_r.substring(0,rids_r.length-1));
          //设置展示图片
          $(parent.$(".imglist")[0]).html(img_r);
          //关闭窗口
          if($('input:checkbox').is(':checked')){
              layer.msg('添加成功！！', {icon: 1,time: 1200});
          }else{
              layer.msg('请选择！！', {icon: 2,time: 1200});
          }
          parent.layer.close(index);
      });

    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      var index = parent.layer.getFrameIndex(window.name);
      //parent.location.reload(); 
      parent.layer.close(index);
    });

    $(function () {        
              $("#f_catalog").click(function() {
               var key=$("#f_catalog  option:selected").attr("key");
               var catalog_json=<?= json_encode($catalog)?>;
               var s_catalog="<?= $model['s_catalog']?>";
               var content='';
               var s_catalogs=catalog_json.imgmgr_level_2[key];
               for(var item in s_catalogs) {
                      if(s_catalog==s_catalogs){
                        content+="<option selected value="+s_catalogs[item]+">"+s_catalogs[item]+"</option>";
                      }else{
                        content+="<option value="+s_catalogs[item]+">"+s_catalogs[item]+"</option>";               
                      }
                  $("#s_catalog").html(content);
              }
          
      });       
       
      $("#s_catalog").click(function() {
            $.ajax({
                 type: "post",
                 url: "/tweet/gettags",
                 data: "s_catalog="+$("#s_catalog  option:selected").attr("value")+"&f_catalog="+$("#f_catalog  option:selected").attr("value"),
                 dataType: "json",
                 success: function(data){
                            $('#tags').empty();   //清空tags里面的所有内容
                            var html = ''; 
                            if(data.errno==0){
                                var i=0;
                              for (value in data.data){
                                  html +='<select  name="tags1['+i+']" >';
                                  html +='<option  selected>请选择</option>';
                                        //console.log(data.data[value]['tag']);
                                    for (value1 in data.data[value]['tag']){
                                        html +='<option value='+data.data[value]['tag'][value1]+'>'+data.data[value]['tag'][value1]+'</option>';
                                    }
                                        html +="</select>";
                                        i++;
                                  }
                              $('#tags').html(html);
                               $("#tags_value").val('');
                                   $("[name='tags1']").click(function() {
                                    var tagsval='';
                                    $("select[name='tags1']").each(function(){     
                                    if($(this).val()!='请选择'){
                                         tagsval = tagsval + $(this).val()+",";
                                    }
                                     });
                                    tagsval=tagsval.substring(0,tagsval.length-1);
                                    $("#tags_value").val(tagsval);
                                  });
                            }else{
                              
                            }

                    }
              });
          });          
      });
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