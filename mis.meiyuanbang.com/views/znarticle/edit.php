  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
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
 	<?php if(isset($zhnarticlemodel->newsid)){?>
    <input type ="hidden" name='isedit' value='1' />
    <input type ="hidden" name="ZhnArticleService[newsid]" value="<?= $zhnarticlemodel->newsid ?>" />
    <?php } ?>
    <tr>
    	<td style="width: 80px">标题<span class='need'>*</span></td>
        <td>
        	<input class="inputclass1" name="NewsService[title]" style="width:70%" type="text" value="<?= $newsmodel->title ?>" datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td>评论开关</td>
        <td>          
            <input type="checkbox" <? if($zhnarticlemodel->allowcmt==1){ ?> checked="checked" <? } ?> value="1" name="ZhnArticleService[allowcmt]" />
            <label for="ZhnArticleService[allowcmt]">需要评论</label>
        </td>
    </tr>
    <tr>
    	<td>分类标注</td>
        <td>          
            <input class="inputclass1" name="ZhnArticleService[mark]" style="width:70%" type="text" value="<?= $zhnarticlemodel->mark ?>" />
        </td>
    </tr>
    <tr>
    	<td>关键词</td>
        <td>
        	<input class="inputclass1" name="NewsService[keywords]" style="width:300px" type="text" value="<?= $newsmodel->keywords ?>"/>
        	&nbsp;&nbsp;多关键词之间用","隔开
        </td>
    </tr>
    <tr>
    	<td>来源</td>
        <td>
        	<input class="inputclass1" name="NewsDataService[copyfrom]" style="width:300px" type="text" value="<?= $newsdatamodel->copyfrom ?>" />
        </td>
    </tr>
    <tr>
    	<td>摘要</td>
        <td>
        	<textarea name="NewsService[desc]" style="width:70%;height:30px;" datatype="*0-100" errormsg="摘要最多100个字符！" sucmsg="&nbsp;" ><?= $newsmodel->desc ?></textarea>
        </td>
    </tr>
    <tr>
    	<td>点赞数<span class='need'>*</span></td>
        <td>
			<input class="inputclass1" name="NewsDataService[supportcount]" style="width:100px" type="text" value="<?= $newsdatamodel->supportcount ?>"  datatype="n" nullmsg="请输入点赞数！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <tr>
    	<td>浏览数<span class='need'>*</span></td>
        <td>
			<input class="inputclass1" name="NewsDataService[hits]" style="width:100px" type="text" value="<?= $newsdatamodel->hits ?>"  datatype="n" nullmsg="请输入浏览数！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <tr>
    	<td>内容<span class='need'>*</span></td>
        <td>
        	<script name='NewsDataService[content]' id="editor" type="text/plain" style="width:98%;height:500px;"></script>
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
  		//显示富文本框内容
  		  var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
  		ue.ready(function() {
  			ue.setContent('<?= $newsdatamodel->content ?>');   
  		});
  		//保存按钮
        $("#asave").click(function () {
            //检查富文本框
            var html = $.trim(ue.getContent());
            if(html ==''){
            	layer.msg('请输入文章内容', {icon: 2});
          		return false;
            }
            $("form").submit();
            return false;
        });
        //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	//parent.location.reload(); 
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