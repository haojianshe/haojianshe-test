  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <!-- 图片上传 -->
  <link href="/static/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />  
  <script src="/static/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>  
  <!-- 图片上传 -->



  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'addfrom']); ?>    
   <table style="width: 100%;">
    <tbody>

      <tr>
       <td style="width:80px">帖子编号</td>
       <td>
         <input type ="hidden" name='isedit' value='1' />
         <input type ="hidden" class="inputclass1" name="TweetService[tid]" style="width:300px" type="text" value="<?= $model->tid ?>" />
         <?= $model->tid ?>
       </td>
     </tr>
     
    
     <tr>
      <td>标题<span class='need'>*</span></td>
      <td>
         <input class="inputclass1" name="TweetService[title]" style="width:99%;" type="text" value="<?= $model->title ?>" datatype="*1-24" nullmsg="请输入标题，最多24个字！"    sucmsg="&nbsp;" />
      </td>
    </tr>


     <tr>
      <td>内容<span class='need'>*</span></td>
      <td>
        <textarea name="TweetService[content]" style="width:99%;height:100px;" value="<?= $model->content ?>" datatype="*1-120" nullmsg="请输入内容，最多120个字！"    sucmsg="&nbsp;"><?= $model->content ?></textarea>
      </td>
    </tr>

   <tr>
      <td>选择图片</td>
        <td>
          <input type="file" id="uploadify" name="uploadify"> 
        </td>
    </tr>
    <tr>
      <td></td>
        <td>
         <div>
            <span class="normalbtn_l"><a href="javascript:$('#uploadify').uploadify('upload','*')">开始上传</a></span>
            <span class="normalbtn_l"><a href="javascript:$('#uploadify').uploadify('cancel','*')">取消所有上传</a> </span>
           <!--  <span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span> -->
          </div>
        </td>
    </tr>
     <tr>
      <td>已选择图片</td>
        <td>
          <div>
            <div id="fileQueue"></div>      
            <input  id="resourcids" name="TweetService[resource_id]" style="width:300px;display: none;" type="text"   />
          </div>
        </td>
    </tr>
    <tr style="display: none;">
      <td width="80">是否素材</td>
      <td>
        <input type="radio" name="TweetService[type]" value="1" <?if($model->type == '1'){?>checked<? }?> />&nbsp;是 &nbsp;&nbsp;
        <input type="radio"  name="TweetService[type]" value="2" <?if ($model->type == '2' || empty($model->type)){?>checked<? } ?> />&nbsp;否
      </td>
    </tr>
    
    <tr>
       <td width="80">选择用户</td>
         <td>
           <select name="TweetService[uid]" id="uid">
           <?  foreach ($users as $key => $value) {?>
              <option value="<?= $value['uid']?>" key="<?= $value['uid']?>" > <?= $value['sname']?></option>
            <?}?>
           </select>
           </td>
    </tr>
    <tr>

 
      <td width="80">一级分类</td>
      <td>
       <select name="TweetService[f_catalog]" id="f_catalog">
         <?if(empty($model->f_catalog)){ ?>
         <option value="" selected="">一级分类</option>
         <?}?>

         <? foreach ($catalog['imgmgr_level_1'] as $key => $value) {?>            
         <option value="<?=$value?>" key="<?=$key?>" <?if ($value==$model->f_catalog) {?>selected<?} ?>>
          <?=$value?>
        </option>
        <?}?>

      </select>
      <select name="TweetService[s_catalog]" id="s_catalog">
        <?if(empty($model->s_catalog)){ ?>
        <option value="" selected="">二级分类</option>
        <?}else{?>
        <option value="" selected=""><?= $model->s_catalog ?></option>
        <?}?>
      </select>
    </td>
  </tr>

  <!--
  <tr>
    <td width="80">标签</td>
    <td id='tags'>
      <?php 
      //foreach (explode(",", $model->tags) as $tagkey => $tagvalue) {?>
      <select name="tags" >
        <option  value="<?php //echo $tagvalue ?>" selected>
          <?php //if(empty($model->tags)){?>
          无
          <?php //}?>
          <?php //echo $tagvalue?></option>
        </select>
        <?php //} ?>
      </td>
      <input type='hidden' id='tags_value' class="inputclass1" id='tags_hidden' name="TweetService[tags]"  value="<?php //echo $model->tags ?>" />
    </tr>
    -->
      <tr>
       <td >推荐考点id</td>
       <td>
         <input class="inputclass1" name="TweetService[lessonid]" style="width:99%;" type="text" value="<?= $model->lessonid ?>" />
       </td>
     </tr> 
      <tr>
       <td></td>
       <td>
         <div>
          <span class="normalbtn_l"><a id="asave" href="#">保存</a></span>
          <span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>
        </div>
      </td>
    </tr>

</tbody>
</table> 
<?php ActiveForm::end(); ?> 
</div>
<script>
	//设置分类
	var index = parent.layer.getFrameIndex(window.name);
  $(function () {        
    $("#f_catalog").click(function() {
           var key=$("#f_catalog  option:selected").attr("key");
           var catalog_json=<?= json_encode($catalog)?>;
           var s_catalog="<?= $model->s_catalog?>";
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
                    for (value in data.data){
                        html +='<select  name="tags" >';
                        html +='<option  selected>请选择</option>';
                              //console.log(data.data[value]['tag']);
                          for (value1 in data.data[value]['tag']){
                              html +='<option value='+data.data[value]['tag'][value1]+'>'+data.data[value]['tag'][value1]+'</option>';
                          }
                              html +="</select>";
                        }
                    $('#tags').html(html);
                     $("#tags_value").val('');
                         $("[name='tags']").click(function() {
                          var tagsval='';
                          $("select[name='tags']").each(function(){     
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
        //保存按钮
        $("#asave").click(function () {
            //检查帖子图片
            var  resourcids = $('#resourcids').val();
            if(resourcids == ''){
              layer.msg('请至少上传一张图片', {icon: 2});
              return false;
            }
            $("form").submit();
            return false;
          });
          //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	parent.layer.close(index);
        });
    });
    //保存成功后自动关闭
    <?if(isset($isclose) && $isclose){ ?>
    	parent.layer.msg('<?= $msg ?>');
    	setTimeout(function (){
    		parent.location.reload();
     }, 1000);
      <? } ?>
</script>

<!-- 图片上传 -->
<script>
  var resource_id='';
    //父窗口句柄
  var index = parent.layer.getFrameIndex(window.name);
  //uploadify初始化
  $(function(){  
        $("#uploadify").uploadify({      
            'debug'     : false, //开启调试  
            'auto'           : false, //是否自动上传     
            'swf'            : '/static/js/uploadify/uploadify.swf',  //引入uploadify.swf    
            'uploader'       : '/vesttweet/picupload',//请求路径    
            'queueID'        : 'fileQueue',//队列id,用来展示上传进度的    
            'width'     : '150',  //按钮宽度    
            'height'    : '24',  //按钮高度  
            'queueSizeLimit' : 1000,  //同时上传文件的个数    
            'fileTypeDesc'   : '图片文件',    //可选择文件类型说明  
            'fileTypeExts'   : '*.png;*.jpg;*.jpeg;*.gif;*.bmp', //控制可上传文件的扩展名 
            'multi'          : true,  //允许多文件上传    
            'buttonText'     : '请选择图片（小于5M）',//按钮上的文字    
            'fileSizeLimit' : '5MB', //设置单个文件大小限制     
            'fileObjName' : 'uploadify',  //<input type="file"/>的name    
            'method' : 'post',    
            'removeCompleted' : true,//上传完成后自动删除队列    
            'onFallback':function(){      
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");      
            },   
            'onUploadSuccess' : function(file, data, response){//单个文件上传成功触发   
              if(resource_id==''){
                 resource_id=$.parseJSON(data).data.rid;
              }else{
                resource_id+=","+$.parseJSON(data).data.rid;
              }
              //console.log($("#resourcids"));
               $("#resourcids").val(resource_id);
               $("#fileQueue").append('<img   data-rid='+$.parseJSON(data).data.rid+' src="'+$.parseJSON($.parseJSON(data).data.img).n.url+'" style="width:100px;height:100px;margin:2px 2px 2px 2px;">'); 
              },'onQueueComplete' : function(){//所有文件上传完成    
                  layer.msg('上传完毕', {icon: 1});
                  setTimeout(function (){
                  //window.location.reload();
                 }, 1000);
              }    
            });  
    });       
    
    //表单验证
    $("#addfrom").Validform({
      tiptype:3,
    }); 
</script>
<!-- 图片上传 -->
        