<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mis\lib\enumcommon\ActivityClickTypeEnum;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<!--添加乐视视频按钮-->
<script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/letv/levedio.js"></script> 
<!-- 时间选择框样式 -->
<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css"/>
<!-- 时间选择框js -->
<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
<div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
   <table style='width:100%;'>
      <tbody>
          <?php if(isset($startpagemodel->pageid)){?>
          <input type ="hidden" name='isedit' value='1' />
          <input type ="hidden" name="StartpageService[pageid]" value="<?= $startpagemodel->pageid ?>" />
          <?php } ?>
          <tr>
           <td style="width: 80px">说明<span class='need'>*</span></td>
           <td>
               <input class="inputclass1" name="StartpageService[desc]" style="width:70%" type="text" value="<?= $startpagemodel->desc ?>" datatype="*1-30" nullmsg="请输入备注，最多30个字！" sucmsg="&nbsp;"/>
           </td>
       </tr>
		<tr class="signup_url click_tr">
		   <td style="width: 80px">跳转地址</td>
		   <td>
		       <input class="inputclass1" name="StartpageService[jumpurl]" style="width:50%" type="text" value="<?= $startpagemodel->jumpurl ?>" ignore="ignore" datatype="url" errormsg="url地址格式错误" sucmsg="&nbsp;"/>
		   </td>
		</tr>
		<tr>
		   <td style="width: 80px">生效日期<span class='need'>*</span></td>
		   <td>
		       <input type="text" name="StartpageService[startdate]" id="btime" value="<?if($startpagemodel->startdate){echo date('Y-m-d H:i',$startpagemodel->startdate);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
		       <script type="text/javascript">
		        Calendar.setup({
		           weekNumbers: true,
		           inputField : "btime",
		           trigger    : "btime",
		           dateFormat: "%Y-%m-%d %H:%M",
		           showTime: true,
		           minuteStep: 1,
		           onSelect   : function() {this.hide();}
		       });
		    </script>
		    至
		    <input type="text" name="StartpageService[expiredate]" id="etime" value="<? if($startpagemodel->expiredate){echo date('Y-m-d H:i',$startpagemodel->expiredate);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
		    <script type="text/javascript">
		        Calendar.setup({
		           weekNumbers: true,
		           inputField : "etime",
		           trigger    : "etime",
		           dateFormat: "%Y-%m-%d %H:%M",
		           showTime: true,
		           minuteStep: 1,
		           onSelect   : function() {this.hide();}
		       });
		    </script>
			</td>
		</tr>
		<tr>
		   <td>启动图<span class='need'>*</span></td>
		   <td>
		       <input type ="hidden" id="thumb" name="thumb" value='<?= $startpagemodel->imginfo ?>' />      	
		       <a name='athumb' id='athumb' thumbid='0' href='#'><img id='imgthumb' src="<? if($thumb_url){echo $thumb_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:200px;' /></a>
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
  		//父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
  		//点击启动图事件
  		$("a[name=athumb]").click(function () {
              var content = '/startpage/thumbupload';
              var title = '编辑启动图';
              url = $('#thumb').val();
			  if(url != ''){
				obj =  JSON.parse($('#thumb').val());
				url = obj.url; 
			  }
              content = content + '?url='+ encodeURI(url);
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
            //检查起止时间,规则为只要输入了截止时间，则截止时间必须大于开始时间
            btime = $('#btime').val();
            etime = $('#etime').val();
            if(etime!='' && etime<btime){
            	layer.msg('截止时间应大于起始时间', {icon: 2});
                return false;
            }
            //检查启动图
            t = $('#thumb').val();
            if(t == ''){
            	layer.msg('启动图必须上传', {icon: 2});
                return false;
            }
            $("form").submit();
            return false;
        });
        //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	parent.layer.close(index);
        });
        //保存成功后自动关闭
        <? if(isset($msg) && $msg<>''){ ?>
        	<?if(isset($isclose) && $isclose){ ?>
        		layer.msg('<?= $msg ?>', {icon: 1});
              setTimeout(function (){
                 parent.location.reload();
             }, 1000);
              <? } else{ ?>
               layer.msg('<?= $msg ?>', {icon: 2});
               <? } ?>
               <? } ?>
		//表单验证
        $("#cmsform").Validform({
          tiptype:3,
      });	      
    </script>