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
   <table>
    <tbody>
 <tr>
      <td>选择文件<span class='need'>*</span></td>
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
            <span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>
          </div>
        </td>
    </tr>
     <tr>
      <td>已选择文件</td>
        <td>
          <div>
            <div id="fileQueue"></div>      
          </div>
        </td>
    </tr>
</tbody>
</table> 
<?php ActiveForm::end(); ?> 
</div>
<script>
        //父窗口句柄
         var index = parent.layer.getFrameIndex(window.name);
          //uploadify初始化
         $(function(){  
              $("#uploadify").uploadify({      
                  'debug'     : false, //开启调试  
                  'auto'           : false, //是否自动上传     
                  'swf'            : '/static/js/uploadify/uploadify.swf',  //引入uploadify.swf    
                  'uploader'       : '/tool/materialadd',//请求路径    
                  'queueID'        : 'fileQueue',//队列id,用来展示上传进度的    
                  'width'     : '75',  //按钮宽度    
                  'height'    : '24',  //按钮高度  
                  'queueSizeLimit' : 1000,  //同时上传文件的个数    
                  'fileTypeDesc'   : 'apk文件',    //可选择文件类型说明  
                 // 'fileTypeExts'   : '*.png;*.jpg;*.jpeg;*.gif;*.bmp', //控制可上传文件的扩展名 
                  'fileTypeExts'   : '*.apk', //控制可上传文件的扩展名 
                  'multi'          : true,  //允许多文件上传    
                  'buttonText'     : '请选择文件',//按钮上的文字    
                  'fileSizeLimit' : '200000MB', //设置单个文件大小限制     
                  'fileObjName' : 'uploadify',  //<input type="file"/>的name    
                  'method' : 'post',    
                  'removeCompleted' : true,//上传完成后自动删除队列    
                  'onFallback':function(){      
                      alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");      
                  },   
                   'onUploadStart':function(file){                      
                       $('#uploadify').uploadify('settings','uploader','/tool/materialadd');
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
                            parent.location.reload();
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
            	parent.layer.close(index);
            });
         
          //保存成功后自动关闭
          <?if(isset($isclose) && $isclose){ ?>
          	parent.layer.msg('<?= $msg ?>');
          	setTimeout(function (){
          	parent.location.reload();
           }, 1000);
          <? } ?>
     
</script>
        