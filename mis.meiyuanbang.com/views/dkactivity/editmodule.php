<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mis\lib\enumcommon\ActivityClickTypeEnum;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

<!-- ueditor 副文本编辑框 -->
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>

<!-- 时间选择框样式 -->
<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css"/>

<!-- 时间选择框js -->
<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>

<!-- 多图上传插件  -->
<link href="/static/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />  
<script src="/static/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>  
<!-- 多图上传插件  -->

<div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
   <table style='width:100%;'>
      <tbody>
          <?php if(isset($model->modulesid)){?>
          <input type ="hidden" name='isedit' value='1' />
          <input type ="hidden" name="DkModulesService[modulesid]" value="<?= $model->modulesid ?>" />
          <?php } ?>
      
<?  
    switch ($model->type) {
      case '1'://副文本
      ?>
        <tr><td colspan='2'></td></tr>
        <tr>
             <td style="width: 150px">标题<span class='need'>*</span></td>
             <td>

                 <input class="inputclass1" name="DkModulesService[title]" style="width:70%" type="text" value="<?= $model->title ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
             </td>
         </tr>
        
         <tr>
             <td style="width: 150px">内容<span class='need'>*</span></td>
             <td>
                <script name='DkModulesService[content]' id="editor" type="text/plain" style="width:770px;height:500px;"></script>
                <script>
                     var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
                      ue.ready(function() {
                            ue.setContent('<?= $model->content ?>');
                        });
                </script>
              
             </td>
            
         </tr>

      <?
        break;
      case '2'://图片
      ?>
      
        <tr><td colspan='2'></td></tr>
        <tr>
             <td style="width: 150px">标题<span class='need'>*</span></td>
             <td>

                 <input class="inputclass1" name="DkModulesService[title]" style="width:70%" type="text" value="<?= $model->title ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
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
            <?if($imglist){
                foreach ($imglist as $key => $value) {
                  //var_dump($value->rid);
                  //  var_dump(json_decode($value->img)->n->url);
                  ?>
                  <span id="example<?=$value->rid?>">
                      <img  onclick="delimg(<?=$value->rid?>);" data-rid='<?=$value->rid?>' src="<?=json_decode($value->img)->n->url?>" style="width:100px;height:100px;margin:2px 2px 2px 2px;" >
                      </span>
                  <?
                }
            }?>
            <input  id="resourcids" name="DkModulesService[content]"  value="<?= $model->content ?>" style="width:300px;display: none;" type="text"   />
          </div>
        </td>
    </tr>



      <?
        break;
      case '3'://视频
      ?>
        <tr><td colspan='2'></td></tr>
        <tr>
             <td style="width: 150px">标题<span class='need'>*</span></td>
             <td>

                 <input class="inputclass1" name="DkModulesService[title]" style="width:70%" type="text" value="<?= $model->title ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
             </td>
         </tr>
        
         <tr>
             <td style="width: 150px">uu<span class='need'>*</span></td>
             <td>
               <input class="inputclass1" name="uu" style="width:70%" type="text" value="<?if($model->content){echo json_decode($model->content)->uu;}?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
             </td>         
         </tr>
       <tr>
            <td style="width: 150px">vu<span class='need'>*</span></td>
             <td>
                <input class="inputclass1" name="vu" style="width:70%" type="text" value="<?if($model->content){echo json_decode($model->content)->vu;}?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
             </td>
      </tr>
      <?
      break;
    }
 ?> 

 


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
<script type="text/javascript">
    //父窗口句柄
     
            var index = parent.layer.getFrameIndex(window.name);
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
               // parent.layer.close(index);
                parent.location.reload();
             }, 1000);
            <? } ?>      //uploadify初始化
    //uploadify初始化
    var resource_id='';
        function delimg(rid){
          var arrList=$("#resourcids").val().split(",");
          for (var i=0;i<arrList.length; i++) {
            if(arrList[i]==rid){
              arrList.splice(i,1);
            }
          }        
          //arrList.splice(jQuery.inArray(rid,arrList),1); 
          $("#resourcids").val(arrList.join(","));
          $("#example"+rid).html("");
        }
  $(function(){  
        $("#uploadify").uploadify({      
            'debug'     : false, //开启调试  
            'auto'           : false, //是否自动上传     
            'swf'            : '/static/js/uploadify/uploadify.swf',  //引入uploadify.swf    
            'uploader'       : '/dkactivity/picupload',//请求路径    
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
               $("#fileQueue").append('<span id="example'+$.parseJSON(data).data.rid+'"><img onclick="delimg('+$.parseJSON(data).data.rid+');"  data-rid='+$.parseJSON(data).data.rid+' src="'+$.parseJSON($.parseJSON(data).data.img).n.url+'" style="width:100px;height:100px;margin:2px 2px 2px 2px;"></span>'); 
              },'onQueueComplete' : function(){//所有文件上传完成    
                  layer.msg('上传完毕', {icon: 1});
                  setTimeout(function (){
                  //window.location.reload();
                 }, 1000);
              }    
            });  
    });       


      //点击缩略图事件
      $("a[name=athumb]").click(function () {
              var content = '/activity/thumbupload';
              var title = '编辑缩略图';
              content = content + '?url='+ encodeURI($('#thumb').val());
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
                  shadeClose: false, //点击遮罩关闭层
                  area : ['600px' , '400px'],
                  content: content
              });
              return false;
          });
      //保存按钮
        $("#asave").click(function () {
            
            //检查缩略图
            t = $('#thumb').val();
            if(t == ''){
              layer.msg('缩略图必须上传', {icon: 2});
                return false;
            }
            $("form").submit();
            return false;
        });

        //添加模块
        function addModule(type){
           switch(type)
            {
            case 1:

              //增加副文本
              break;
            case 2:
              //增加图片
              break;
            case 3:
              //增加乐视视频
              break;
            }
        }
        //删除模块
        function delModule(){
          alert('shanchu');
        }
        function manageModule(modulesid){
          var content = '/dkactivity/modules?modulesid='+modulesid;
              var title = '模块列表';
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
                  shadeClose: false, //点击遮罩关闭层
                  area : ['600px' , '400px'],
                  content: content
              });
              return false;
        }
       
</script>