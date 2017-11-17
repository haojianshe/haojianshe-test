  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <link href="/static/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />  
  <script src="/static/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>  
  
  <div class="normaltable">
   <table style='width:100%;'>
 	<tbody>
 	<tr>
    	<td style="width: 80px">节点编号:</td>
        <td>
        	<?= $sectionmodel->sectionid ?>
        </td>
    </tr>
    <tr>
    	<td>节点标题</td>
        <td>
        	<?= $sectionmodel->sectiontitle ?>
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
    </tbody>
 </table> 
  <table style='width:100%;' class="content_list">
  	<thead>
  	  <tr class="operate">
        <th colspan="3" >
        	共有<?= count($imgmodels) ?>个图片
        </th>
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
      <tr class="tb_header">
        <th style='width:80px'>排序字段</th>
        <th style='width:80px'>图片编号</th>
        <th style='text-align: center;'>图片</th>        
        <th style='text-align: center;'>操作</th>
      </tr>
    </thead>    
 	<tbody>
    <input type ="hidden" name="sectionid" value="<?= $sectionmodel->sectionid ?>" />    
    <? foreach ($imgmodels as $model) { ?>
      <tr class="tb_list">
      <td><?= $model->listorder ?></td>
      <td><?= $model->picid ?></td>
      <td style='text-align: center;'>
      	<a href='<?=$model->picurl ?>' target='_blank'><img src="<?=$model->picurl ?>" style='padding-left:15px;' width='150px' /></a>
      </td>
      <td style='text-align: center;'>
      	<a name='alistorder' picid='<?= $model->picid ?>' href='#'>修改顺序</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='adel' picid='<?= $model->picid ?>' href='#'>删除图片</a>
      </td>
      </tr>
     <?}?>
    </tbody>
 </table> 
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
  	            'uploader'       : '/lesson/picupload?sectionid=<?= $sectionmodel->sectionid ?>',//请求路径    
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
  	            'onUploadSuccess' : function(file, data, response){//单个文件上传成功触发    
  	            	//data就是action中返回来的数据
  	            	var retobj = eval(strJSON);
  	            	alert(retobj);
  	            	if(retobj.errno ==1){
  	            		alert(data);
  	            	}
  	            },'onQueueComplete' : function(){//所有文件上传完成    
	  	            	layer.msg('上传完毕', {icon: 1});
	  		        	setTimeout(function (){
	  		        		window.location.reload();
	  		           }, 1000);
  	                }    
  	            });  
  	    });  	    
        //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	//parent.layer.close(index);
        	parent.location.reload(); 
        });
		//表单验证
        $("#cmsform").Validform({
    		tiptype:3,
    	});	
        //修改listorder按钮绑定事件
        //可使用prompt方法
		layer.config({
		    extend: 'extend/layer.ext.js'
		});
        $("a[name=alistorder]").click(function () {
        	var picid = $(this).attr("picid");
        	layer.prompt({
        	    title: '请输入新的序号',
        	    formType:0  //prompt风格，支持0-2
        	}, function(listorder){
        		//检查密码长度
        		if(isNaN(listorder)){
        			layer.msg('请输入数字',{icon: 2});
        			return false;
        		}
            	//确定进行删除
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: "/lesson/setimglistorder",
                    data: "picid=" + picid+"&listorder="+listorder,//要发送的数据
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
        //删除按钮绑定事件
        $("a[name=adel]").click(function () {
        	var picid = $(this).attr("picid");
            layer.confirm('删除后将不可恢复，确定删除吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                //确定进行删除
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: "/lesson/imgdel",
                    data: "picid=" + picid,//要发送的数据                                
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