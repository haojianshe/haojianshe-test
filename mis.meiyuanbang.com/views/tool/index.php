<?php

use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- 图片浏览 引入结束-->
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr  class="operate">
            <th  colspan="5">
                <div id="searchid">
                    <form name="searchform" action="/capacity/material" method="get" >
                        <table width="100%" cellspacing="0" class="search-form">
                            <tbody>
                                <tr>
                                    <td>
                                        <div style="float:right;" class="explain-col">    
                                            <input type="button" id="add" value="添加渠道包" class="button">
                                            <input type="button" id="removeCache" value="清空CDN缓存" class="button">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </th>
        </tr>
        <tr class="tb_header">
            <th>文件名称</th>
            <th>大小</th>
            <th>上传时间</th>
        </tr>
    </thead>
    <?php foreach ($model as $model) { ?>
        <tr class="tb_list">
            <td style='width:60%'><?= $model['Key'] ?></td>
            <td><?= $model['Size'] ?></td>
            <td><?= $model['LastModified'] ?></td>
        </tr> 
    <?php } ?>
	<tr class="tb_list">
    	<td>
	   		<div>
		        <span style='color: red;'>更新主站和qd1-qd5 渠道包步骤</span><br>
		        <span>1. 上传最新版本的渠道包(请保持文件名不变),可以根据上传时间判断是否上传成功 </span><br>
		        <span>2. 上传成功后，点击清cdn缓存按钮，收到成功提示后大约5分钟以后生效</span><br>
		        <span>3. 渠道包的下载地址为 http://img.meiyuanbang.com/download/渠道包文件名 </span><br>
	        </div>
    	</td>
        <td></td>
        <td></td>
    </tr>
</table>
<div id="_tips"></div>
<script type="text/javascript">
    //批量添加能力素材
    $("#add").click(function () {
        var content = '/tool/materialaddh';
        var title = '添加';
        layer.open({
            type: 2,
            title: title,
            maxmin: true,
            shadeClose: false, //点击遮罩关闭层
            area: ['380px', '600px'],
            content: content
        });
    });
    
    //删除按钮绑定事件
$("#removeCache").click(function () {
    layer.confirm('确定要清空CDN缓存吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/tool/removecache",
            success: function (data) {
                if (data.errno == 0) {
                    layer.msg(data.msg,{icon: 1});
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


