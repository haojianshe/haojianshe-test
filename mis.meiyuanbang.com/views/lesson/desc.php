<?php
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate" >
            <th colspan="3" >共有<strong>
              <?=$pages->totalCount?></strong>条记录
            </th>
            <th colspan="2" style="text-align:right;" > 
              <input type="button" id="btnnew" value="新建考点描述" class="button"/>
            </th >
         </tr>
        <tr class="tb_header">
          <th >自增id</th>
          <th >考点id</th>
          <th >语音描述</th>
          <th >图片</th>
          <th >操作</th>
        </tr>
    </thead>
    <? foreach ($models as $model) { ?>
        <tr class="tb_list">
        <td><?= $model['lessondescid'] ?></td>
        <td><?= $model['lessonid'] ?></td>
        <td>
            <audio id="audio_sound" src="<?= $model['sourceurl'];?>" controls></audio><?= $model['desc'] ?>
        </td>
        <td><img height="50" src="<?= json_decode($model['imgurl'])->url ?>"></td>
        <td>
        	<a onclick="addedit(<?= $model['lessondescid'] ?>);" href='javascript:;'>编辑</a>
          <a name='adel' lessondescid='<?= $model['lessondescid'] ?>' href='javascript:;'>删除</a>&nbsp;&nbsp;

        </td>
        </tr>
   <?}?>
       <tr class="operate">
    	      <td colspan="7">
    			<div class="cuspages right">
    			<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
    			</div>      
    	      </td>
        </tr>
</table>
<div id="_tips"></div>
<script>
    //新建按钮绑定事件
    $('#btnnew').on('click', function(){
    	addedit(0);
    });
    //编辑或新增考点页面
    function addedit(lessondescid){
    	var content = '/lesson/descedit?lessonid=<?=$lessonid?>';
    	var title = '添加考点描述';
    	if(lessondescid >0){
    		content = content + '&lessondescid=' + lessondescid; 
    		title = '编辑考点描述--编号:'+ lessondescid;
    	}
    	layer.open({
          type: 2,
          title: title,
          maxmin: false,
          shadeClose: false, //点击遮罩关闭层
          area : ['90%' , '80%'],
          content: content
      });
    }


//删除按钮绑定事件
$("a[name=adel]").click(function () {
  var lessondescid = $(this).attr("lessondescid");
    layer.confirm('删除后将不可恢复，确定删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/lesson/descdel",
            data: "lessondescid=" + lessondescid,//要发送的数据                    
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