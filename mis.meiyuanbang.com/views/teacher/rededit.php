  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

  <div class="normaltable">
 <?php $form = ActiveForm::begin(['id' => 'userform']); ?>    
 <table style='width:90%'>
 	<tbody>
 	<input type ="hidden" name='uid' value='<?= $usermodel->uid ?>' />   
 	<?php if(isset($redmodel->uid) && $redmodel->uid>0){?>
      	<input type ="hidden" name='isedit' value='1' />       	 
    <?php } ?>
    <tr>
    	<td style="width: 80px">用户编号</td>
        <td>
        	<span><?= $usermodel->uid ?></span>
        </td>
    </tr>
    <tr>
    	<td style="width: 80px">用户名</td>
        <td>
        	<span><?= $usermodel->sname ?></span>
        </td>
    </tr>
    <tr>
    	<td>擅长科目<span class='need'>*</span></td>
        <td>
        	<input type="checkbox" <? if($redmodel['issketch']==1){ ?> checked="checked" <? } ?> value='1' id="c1" name="UserCorrectService[issketch]" />
        	<label for="c1">速写</label>
        	<input type="checkbox" <? if($redmodel['isdrawing']==1){ ?> checked="checked" <? } ?> value="1" id="c2" name="UserCorrectService[isdrawing]" />
        	<label for="c2">素描</label>
        	<input type="checkbox" <? if($redmodel['iscolor']==1){ ?> checked="checked" <? } ?> value="1" id="c3" name="UserCorrectService[iscolor]" />
        	<label for="c1">色彩</label>

            <input type="checkbox" <? if($redmodel['isdesign']==1){ ?> checked="checked" <? } ?> value="1" id="c5" name="UserCorrectService[isdesign]" />
            <label for="c5">设计</label>
        </td>
    </tr>
    <tr style="display:none">
    	<td>是否私密</td>
        <td>
        	<input type="checkbox" <? if($redmodel['isprivate']==1){ ?> checked="checked" <? } ?> value="1" id='c4' name="UserCorrectService[isprivate]" />
        	<label for="c4">私密老师</label>
        </td>
    </tr>  
    <tr id='trpwd' <? if($redmodel['isprivate']==0){ ?> style="display: none;" <? } ?>>
    	<td>小组密码</td>
        <td>
        	<input class="inputclass1" id='pwd' name="pwd" style="width:100px" type="text" value="<?= $teammodel->password ?>" />
        	<span>&nbsp;&nbsp;请输入四位数字</span>
        </td>
    </tr> 
    <tr >
        <td>付费价格<span class='need'>*</span></td>
        <td>
            Android 及苹果非内购价格：<input class="inputclass1" id='correctfee' name="UserCorrectService[correct_fee]" style="width:100px" type="text" value="<?= $redmodel->correct_fee ?>" />&nbsp;&nbsp;&nbsp;元
        </br>
            苹果内购价格：
          <input id="ios_price" hidden class="inputclass1" name="UserCorrectService[correct_fee_ios]" style="width:100px" type="text" value="<?= $redmodel->correct_fee_ios?$redmodel->correct_fee_ios:0 ?>"   />
          <a  class="normalbtn_l" id="iospricesel" href='javascript:;'>选择</a>
          <span id="ios_price_info"><?= $redmodel->correct_fee_ios?$redmodel->correct_fee_ios:0 ?></span><span>元</span>

        </td>


    </tr>   
    <tr>
    	<td></td>
    	<td>
	        <div>
	        	<span class="normalbtn_l"><a id="asave" href="javascript:;">保存</a></span>
	        	<span class="normalbtn_l"><a id="aclose" href="javascript:;">关闭</a></span>	        	
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
        $(function () {
            //保存按钮
            $("#asave").click(function () {
                //擅长至少要选择一项
                if(!$("#c1")[0].checked && !$("#c2")[0].checked && !$("#c3")[0].checked && !$("#c5")[0].checked){
                	layer.msg('至少选择一项擅长科目', {icon: 2});
					return false;
                }

                if($.trim($("#correctfee").val())==''){
                	layer.msg('请输入付费价格，免费批改老师请输入0', {icon: 2});
                　　　　		return false;
                }
                if (isNaN($("#correctfee").val())) { 
                	layer.msg('请输入正确的付费价格，免费批改老师请输入0', {icon: 2});
		                　　　　return false;
                }
                //如果是私密老师则密码一定要设置，并且为4位数字
                if($("#c4")[0].checked){
                    //var reg = new RegExp("/^\d{4}$/");
					var reg = /^\d{4}$/;       
                    var obj = $('#pwd').val();
	                if(reg.test(obj)==false){
	                	 layer.msg('请输入密码，格式为4位数字!', {icon: 2});
	                     return false;
	                 }                 
                }
                $("form").submit();
                return false;
            });
            //关闭按钮,刷新父窗口
            $('#aclose').click(function(){
            	//parent.location.reload(); 
            	parent.layer.close(index);
            });            
        });

        //给设置密码绑定事件
        $("#c4").click(function(){
            if($("#c4")[0].checked){
                //显示密码输入项
            	$("#trpwd").show();
            }
            else{
                //隐藏密码输入项
            	$("#trpwd").hide();
            }
		});
		
        //保存成功后自动关闭
        <? if(isset($msg) && $msg<>''){ ?>
        	<?if(isset($isclose) && $isclose){ ?>
        		layer.msg('<?= $msg ?>', {icon: 1});
	        	setTimeout(function (){
	        		if(GetQueryString('from')=='list'){
	        			parent.location.reload();
		        	}
	        		else{
	        			parent.parent.location.reload();	
	        		}	        		
	           }, 1000);
	      	<? } else{ ?>
	      		layer.msg('<?= $msg ?>', {icon: 2});
	      	<? } ?>
        <? } ?>
		//表单验证
        $("#userform").Validform({
    		tiptype:3,
    	});

        function GetQueryString(name)
        {
             var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
             var r = window.location.search.substr(1).match(reg);
             if(r!=null)return  unescape(r[2]); return null;
        }


        //选择ios价格
        $("#iospricesel").click(function () {
                var content = '/teacher/ios_price_sel';
                var title = '选择ios价格';
                content = content + '?price='+ encodeURI($("#ios_price").val());
                var search =layer.open({
                    type: 2,
                    title: title,
                    maxmin: true,
                    area : ['700px' , '600px'],
                    content: content
                  });
                layer.full(search);
          });
    </script>
  