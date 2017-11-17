  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" src="/static/js/jquery.min.js?v=20151124"></script>
  <link href="/static/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />  
  <script src="/static/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>  
  
  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>    
      <table style="width: 100%">
    <tbody>
        <tr>
            <td style="width:5.3%">用户名</td>
             <td>
           <select name="CapacityModelMaterialService[uid]" id="uid">
           <?  foreach ($users as $key => $value) {?>
              <option value="<?= $value['uid']?>" key="<?= $value['uid']?>" > <?= $value['sname']?></option>
            <?}?>
           </select>
           </td>
        </tr>
        <tr>
             <td>内容</td>
            <td>
                <textarea name="CapacityModelMaterialService[content]" style="width:99%;height:100px;" value="" datatype="*1-120" nullmsg="请输入内容，最多120个字！" id="content" sucmsg="&nbsp;"></textarea>
      <span class="Validform_checktip"></span></td>
        </tr>
     <tr>
       <td >主分类<span class='need'>*</span></td>
       <td>
            <select name="CapacityModelMaterialService[f_catalog_id]" id="f_catalog_id">
            <option value="" selected="">主分类</option>
            <?foreach (json_decode($classtype,true)['maintype'] as $key => $value) {?>
                  <option value="<?= $key ?>"
                      <?if(empty($model->f_catalog_id) &&$model->f_catalog_id==$key){
                        ?>
                        selected="selected"
                      <?}?>><?= $value ?>
                  </option>
            <?}?>
            </select>
       </td>
     </tr>   

     <tr>
      <td>子分类<span class='need'>*</span></td>
      <td>
         <select name="CapacityModelMaterialService[s_catalog_id]" id="s_catalog_id">
             <option value="" selected="">二级分类</option>
            <?if(empty($model->f_catalog_id)){$model->f_catalog_id=1;}?>
              <?  foreach (json_decode($classtype,true)['subtype'][$model->f_catalog_id] as $key => $value) { ?>    
                    <option value="<?= $key ?>" 
                      <?if( empty($model->s_catalog_id) && $key==$model->s_catalog_id){
                       ?>
                       selected="selected"
                      <?}?>
                      ><?= $value?>                  
                    </option>
            <?}?>
          </select>
      </td>
    </tr>

     <tr>
      <td>能力分类<span class='need'>*</span></td>
      <td>
       <select name="CapacityModelMaterialService[item_id]" id="item_id">
        <?if( empty($model->f_catalog_id)){  $model->f_catalog_id=1;}?>
          <?  foreach (json_decode($classtype,true)['captype'][$model->f_catalog_id] as $key => $value) {?>     
              <option value="<?= $value['itemid'] ?>" 
              <?if(empty($model->item_id) && $value['itemid']==$model->item_id){
               ?>
               selected="selected"
              <?}?>
              ><?= $value['itemname']?></option>
          <?}?>
        </select>
      </td>
    </tr>


 <tr>
      <td>选择图片<span class='need'>*</span></td>
        <td>
          <input type="file" id="uploadify" name="uploadify"> 
        </td>
    </tr>
    <tr>
      <td></td>
        <td>
            <div>
                <span class="normalbtn_l"><a href="javascript:$('#uploadify').uploadify('upload','*')"  class="classIds">开始上传</a></span>
                <span class="normalbtn_l"><a href="javascript:$('#uploadify').uploadify('cancel','*')">取消所有上传</a> </span>
                <span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>
             </div>
        </td>
    </tr>
     <tr>
      <td>已选择图片</td>
        <td>
          <div>
            <div id="fileQueue"></div>      
          </div>
        </td>
    </tr>

    <tr>
            <?php
            echo "<table id=\"dividid\" >".$data['models']."  </table>";
            ?> 
    </tr>
     
</tbody>
<input type="hidden" value="" id="checkboxId" />
</table> 
<?php ActiveForm::end(); ?> 
</div>
<script>
    $(".classIds").click(function(){
       var f_catalog_val =  $("#f_catalog_id").val();
       var s_catalog_val =  $("#s_catalog_id").val();
       
       if(f_catalog_val==''){
            layer.msg('请选择主分类', {icon: 2});
            return;
       }
       
       if(s_catalog_val=='请选择'){
            layer.msg('请选择子分类', {icon: 2});
            return;
       }
       
      var arr = [];
      $(".checkClass:checked").each(function(){
          arr.push($(this).val());
      });
       var str = arr.toString();
       $("#checkboxId").val(str);
       var checkval = $("#checkboxId").val();
       
       
        if(checkval==''){
            layer.msg('请选择标签', {icon: 2});
             return;
        }
        
    })
    
    	//父窗口句柄
    	var index = parent.layer.getFrameIndex(window.name);
        //uploadify初始化
        $(function(){  
              $("#uploadify").uploadify({      
                  'debug'     : false, //开启调试  
                  'auto'           : false, //是否自动上传     
                  'swf'            : '/static/js/uploadify/uploadify.swf',  //引入uploadify.swf    
                  'uploader'       : '/capacity/materialadd?f_catalog_id='+$("#f_catalog_id option:selected").val()+"&s_catalog_id="+$("#s_catalog_id option:selected").val()+"&item_id="+$("#item_id option:selected").val()+"&uid="+$("#uid").val()+"&content="+$("#content").val()+"&checkboxId="+$("#checkboxId").val(),//请求路径    
                  'queueID'        : 'fileQueue',//队列id,用来展示上传进度的    
                  'width'     : '75',  //按钮宽度    
                  'height'    : '24',  //按钮高度  
                  'queueSizeLimit' : 1000,  //同时上传文件的个数    
                  'fileTypeDesc'   : '图片文件',    //可选择文件类型说明  
                  'fileTypeExts'   : '*.png;*.jpg;*.jpeg;*.gif;*.bmp', //控制可上传文件的扩展名 
                  'multi'          : true,  //允许多文件上传    
                  'buttonText'     : '请选择图片',//按钮上的文字    
                  'fileSizeLimit' : '2MB', //设置单个文件大小限制     
                  'fileObjName' : 'uploadify',  //<input type="file"/>的name    
                  'method' : 'post',    
                  'removeCompleted' : true,//上传完成后自动删除队列    
                  'onFallback':function(){      
                      alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");      
                  },   
                   'onUploadStart':function(file){   
                       $('#uploadify').uploadify('settings','uploader','/capacity/materialadd?f_catalog_id='+$("#f_catalog_id option:selected").val()+"&s_catalog_id="+$("#s_catalog_id option:selected").val()
                               +"&item_id="+$("#item_id option:selected").val()+"&uid="+$("#uid").val()+"&content="+$("#content").val()+"&checkboxId="+$("#checkboxId").val());
                  },   
                  'onUploadSuccess' : function(file, data, response){//单个文件上传成功触发 
                    //data就是action中返回来的数据
                    if(JSON.parse(data).errno !=0){
                      alert(JSON.parse(data).msg);
                    }
                  },'onUploadError' : function(file, errorCode, errorMsg, errorString) {//单个文件上传失败触发 
                        alert(file.name + ' 文件上传失败 错误：' + errorString);
                  },'onQueueComplete' : function(){//所有文件上传完成    
                      layer.msg('上传完毕', {icon: 1});
                      setTimeout(function (){
                            //parent.layer.close(index);
                            parent.location.reload();
                           // window.location.reload();
                        }, 1000);
                      }    
                  });  
          });    
         
            //保存按钮
          $("#asave").click(function () {
              $("form").submit();
              return false;
            });
            //关闭按钮,刷新父窗口
            $('#aclose').click(function(){
            	//parent.location.reload(); 
            	parent.layer.close(index);
            });
         
          //保存成功后自动关闭
          <?if(isset($isclose) && $isclose){ ?>
          	parent.layer.msg('<?= $msg ?>');
          	setTimeout(function (){
          		parent.location.reload();
           }, 1000);
          <? } ?>

        //分类选择
        var classtype='<?=$classtype?>';
        $("#f_catalog_id").change(function() {
              $("#dividid tr").remove();
            var fcid=$("#f_catalog_id  option:selected").val();
            var subtypes=$.parseJSON(classtype).subtype[fcid];
            var captypes=$.parseJSON(classtype).captype[fcid];
            var subhtml='';
            var caphtml='';
            var string = '<option>请选择</option>';
            $.each(subtypes, function(i,val){ 
              subhtml=subhtml+'<option value="'+i+'" >'+val+ "</>";  
            });
            $.each(captypes, function(i,val){ 
              caphtml=caphtml+'<option value="'+val['itemid']+'" >'+val['itemname']+ "</>" ;  
            });
            $("#s_catalog_id").html(string+subhtml);
            $("#item_id").html(caphtml);
        });
        
        //二级分类选择
      $("#s_catalog_id").change(function () {
        $("#dividid tr").remove();
        var s_catalog_id = $(this).val();
         
        var materialid = $("#materialid").val();
       
        var url = '/capacity/select_tag';
        var data = {
            s_catalog_id: s_catalog_id,
            materialid: materialid,
            type :1
        }
        $.post(url, data, function (m) {
            $("#dividid").append(m.models);
        }, 'json');
    });

     
</script>
        