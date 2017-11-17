  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href="/static/css/buttons.css">

  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
    <tr><th colspan="11" >
          <div class="button-group" >         
           
            <?php
            if($channelid==4){
          ?>
        <a href="/posid/correct?channelid=4" class="button  button-small  button-primary ">能力模型广告</a>   
            <?php
            }else{
            ?>
       <a href="/posid/correct" class="button  button-small  button-primary ">改画顶部广告</a>             
 <?php
            }
              ?>
          </div>
        </th></tr>
      <tr class="operate">
       
        <th colspan="9" >
        	共有<?= count($models) ?>条记录
        </th>
        <th colspan="2" style='text-align:right;'>
        	<input type="button" id="btnnew" value="新建推荐" class="button-small  button-primary"/>
        </th>
      </tr>
      <tr class="tb_header">
        <th >推荐位id</th>
        <th >类型</th>
        <th >图片</th>
        <th   >参数1</th>
        <th >参数2</th>
        <th >参数3</th>
        <th >参数4</th>
        <th >参数5</th>
        <th >排序字段</th>
        <th >创建时间</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model->posidid ?></td>
      <td><?= $model->typeid ?></td>
      <td>
      <a href='<?=$model->topimage ?>' target='_blank'><img src="<?=$model->topimage ?>" style='padding-left:15px;' width='200px' /></a>
      </td>
      <td><div style="width:200px;"><?= $model->param1 ?></div></td>
      <td><?= $model->param2 ?></td>
      <td><?= $model->param3 ?></td>
      <td><?= $model->param4 ?></td>
      <td><?= $model->param5 ?></td>
      <td><?= $model->listorder ?></td>
      <td><?= date('Y-m-d H:i:s',$model->ctime); ?></td>
      <td>
      	<a name='aedit' posidid='<?= $model->posidid ?>' href='#'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
      	<a name='adel' posidid='<?= $model->posidid ?>' href='#'>删除</a>
      </td>
      </tr>
     <?}?>
  </table>
  <input type="hidden" id="channelid" value="<?php echo $channelid;?>" />
<script>
//新建按钮绑定事件
$('#btnnew').on('click', function(){
	addedit(0);
});

//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
	addedit($(this).attr("posidid"));
    return false;
});

//删除按钮绑定事件
$("a[name=adel]").click(function () {
	var posidid = $(this).attr("posidid");
    layer.confirm('删除后将不可恢复，确定删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/posid/del",
            data: "posidid=" + posidid,//要发送的数据                    
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

//编辑或新增用户页面
function addedit(posidid){
     var channelid =  $("#channelid").val();
        if(channelid==4){
            var content = '/posid/edit?channelid=4';
        }else{
           var content = '/posid/edit?channelid=3'; 
        }
	var title = '添加推荐位';
	if(posidid >0){
		content = content + '&posidid=' + posidid; 
		title = '编辑推荐位--编号:'+ posidid;
	}
	layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['700px' , '85%'],
        content: content
    });
}
</script>