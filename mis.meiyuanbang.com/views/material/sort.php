<?php

use common\widgets\MyLinkPager;
use common\models\myb\Resource;
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
    <tr class="tb_header">
        <th>老师头像</th>
        <th>排序</th>
    </tr>
</thead>
<form action="/material/insert" method="post" >
<?php
if($models['data']){
foreach ($models['data'] as $model) {
    foreach($model as $key=>$val){
    ?>
<tr class="tb_list">
    <td>
        <?php
        ?>
    <a id="example1"  rel="group<?= $key ?>" href="<?= json_decode($val['img'])->n->url ?>">
            <img style="width:80px;height:80px;padding:3px;" src="<?= json_decode($val['img'])->n->url ?>">
        </a>
    </td>
    <td><input type="text" name="name[<?php echo $val['rid'];?>]" size="7px;"  value="0"/></td>
</tr> 
<?php } } } ?>
  <tr class="operate">
  <input type="hidden" value="<?php echo $models['id']?>" name="id" />
  <!--<input type="submit" id="asave" class="button" />-->
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
</form>

</table>
<div id="_tips"></div>
<script type="text/javascript">
    	var index = parent.layer.getFrameIndex(window.name);
       //保存按钮
        $("#asave").click(function () {
            $("form").submit();
            return false;
        });
           //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	parent.layer.close(index);
        });
</script>
<!-- 图片浏览 结束 -->

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
  
     //保存成功后自动关闭
        <? if($msg<>''){ ?>
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
<!-- 图片浏览 结束 -->