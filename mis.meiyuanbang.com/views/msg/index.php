  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <div class="normaltable">
	 <table style='width:100%'>
	 	<tbody>
	    <tr>
	    	<td style="width: 80px">发信人<span class="need">*</span></td>
	        <td>
	        	<input class="inputclass1" id='senduser' style="width:100px" type="text" value=""  datatype="*1-100" errormsg="发信人名称请不要超过100字符！" nullmsg="请输入发信人！" sucmsg="&nbsp;"/>
	        	<input id='senduserinfo' value=""  type="hidden" />
	        	<span>&nbsp;&nbsp;请输入发信人昵称，并确保发信人在app端是登录状态</span>
	        </td>
	    </tr>
	    <tr>
	    	<td style="width: 80px">收信人<span class="need">*</span></td>
	        <td>
	        	<textarea id='receiveusers' style="width:60%;height:50px;" datatype="*1-1000" errormsg="请输入收信人，多收信人请用英文  , 隔开！" nullmsg="请输入收信人，多收信人请用英文  , 隔开！" sucmsg="&nbsp;"></textarea>
	        	<span>&nbsp;&nbsp;请输入收信人昵称，多收信人之间请用英文    ,  隔开</span>
	        </td>
	    </tr>
	    <tr>
	    	<td>私信内容<span class="need">*</span></td>
	        <td>
	        	<textarea id='msgcontent' style="width:60%;height:50px;" datatype="*1-400" errormsg="私信最多400字！" sucmsg="&nbsp;" nullmsg="请输入私信内容！" ></textarea><span>&nbsp;&nbsp;请不要使用特殊字符，并且使用中文标点符号</span> 
	        </td>
	    </tr>
	    <tr>
	    	<td></td>
	    	<td>
		        <div> 
		        	<input type="button" id="btnsave" value="发送私信" class="button">      	
		        </div>
	        </td>
	    </tr>
	    <tr>
	    	<td></td>
	    	<td>
		    	<div id="nav">
				 	<ul id="colist">
				    </ul>
				 </div>
	        </td>
	    </tr>
	    </tbody>
	 </table>
  </div>
  <script>
  		//可使用prompt方法
  		layer.config({
  		    extend: 'extend/layer.ext.js'
  		});

        $(function () {
            //保存按钮
            $("#btnsave").click(function () {
				//检查参数不能为空
				var senduser = $('#senduser').val();
				var receiveusers = $('#receiveusers').val();
				var msgcontent = $('#msgcontent').val();
				if(senduser=='' || receiveusers=='' || msgcontent==''){
					layer.msg('发件人、收件人、私信内容都不能为空', {icon: 2});
					return;
				}
				//检查发信人是否合法
				senderobj = $('#senduserinfo').val();
				if(senderobj==''){
					layer.msg('检查发信人信息时出现非法错误!', {icon: 2});
					return;	
				}
				senderobj = $.parseJSON(senderobj);
				if(senderobj.errno ==1){
					layer.msg(senderobj.msg, {icon: 2});
					return;
				}				
				//用户确认是否发送
				layer.confirm('群发后将不可撤销，确定群发吗？', {
			        btn: ['确定','取消'] //按钮
			    }, function(index){
			    	//确定
			    	layer.close(index);
					//禁用发送按钮
	            	$('#btnsave').attr('disabled',true);
					//开始发送私信
					//清空上次发送的结果
					$("#colist").find("li").remove(); 
					var arrreceive = receiveusers.split(',');
					for(var i=0; i<arrreceive.length;i++)
					{
						var receiveuser = arrreceive[i];
						if(receiveuser!=''){
							sendmsg(senderobj.uid,receiveuser,msgcontent,senderobj.token);
						}					
					}
					//恢复发送按钮
					$('#btnsave').attr('disabled',false);
			    }, function(){
			        //取消
			        return;
			    });
            });

            //发件人失去焦点事件
            $("#senduser").blur(function () {
            	checksender();
            });
        });        
        
        //发送私信
        function sendmsg(uid,to_username,content,token){
            //发送
        	$.ajax({
	            type: "post",
	            dataType: "json",
	            url: "/msg/ajaxcheck",
	            data: "ajaxtype=send&uid=" + uid +'&receivername='+encodeURIComponent(to_username)+'&msgcontent='+encodeURIComponent(content)+'&token='+token ,//要发送的数据                    
	            success: function (data) {
	                if (data.errno == 0) {
	                	msg = "<li>"+to_username+"<span class='green'>&nbsp;&nbsp;&nbsp;&nbsp;发送成功</span></li>";
	                }
	                else {
	                	msg = "<li>"+to_username+"<span class='need'>&nbsp;&nbsp;&nbsp;&nbsp;"+data.msg+"</span></li>";
	                }
	                $("#colist").prepend(msg);
	            },
	            error: function (XMLHttpRequest, textStatus, errorThrown) {
	            	msg = "<li>"+to_username+"<span class='need'>&nbsp;&nbsp;&nbsp;&nbsp;意外错误</span></li>";
	            	$("#colist").prepend(msg);
	            }
	        });
        }
        
        //检查发信人是否合法
        function checksender(){
        	var sendername = $('#senduser').val();
        	if(sendername ==''){
        		$('#senduserinfo').val('');
        		return;
        	}
			$.ajax({
	            type: "post",
	            dataType: "text",
	            url: "/msg/ajaxcheck",
	            data: "ajaxtype=sendercheck&sname=" + encodeURIComponent(sendername),//要发送的数据 
	            success: function (data) {
	            	$('#senduserinfo').val(data);
	            },
	            error: function (XMLHttpRequest, textStatus, errorThrown) {
	            	$('#senduserinfo').val('');;
	            }
	        });
        }
        
  </script>