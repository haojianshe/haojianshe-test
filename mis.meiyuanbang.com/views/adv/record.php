  <?php
  use common\widgets\MyLinkPager;
  use mis\service\AdvRecordService;
  ?>
  <link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
    <link rel="stylesheet" type="text/css" href='/static/css/pager.css'> 

  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="6" >
          共有<?= $pages->totalCount ?>条记录
        </th>
         <th colspan="1" >
          <a class="button button-primary button-tiny" href="/adv/record_user/">返回</a>
        </th>
      </tr>
      <tr class="tb_header">
        <th style='width:80px'>编号</th>
         <th >图片</th>
        <th >标题</th>
        <th style="width:230px;">类型</th>
        <th style="width:150px;">区域</th>
        <th >创建时间</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['advid'] ?></td>
      <td>
      <a href='<?=$model['topimage1'] ?>' target='_blank'><img src="<?=$model['topimage1'] ?>" style='padding-left:15px;' height='50px' /></a>
       </td>
      
      <td><?= $model['title'] ?></td>
      <td >
      <div onclick="changeHeight(this);" style="width:230px;height: 20px;overflow: hidden; text-overflow: ellipsis;white-space:nowrap;"><? 
      if($model['catalog']){
        foreach ($model['catalog'] as $key => $value) {
          echo $value.',';
        } 
      }else{
          echo '未投放';
      }
      ?></div></td>
      <td><div onclick="changeHeight(this);" style="width:150px;height: 20px;overflow: hidden;text-overflow: ellipsis;white-space:nowrap;"><?= $model['provice'] ?></div></td>
      <td><?= date('Y-m-d H:i:s',$model['ctime']); ?></td>
      <td>
        <? if($model['etime'] <time() && $model['etime']>0){
          echo '已过期';
        }else{?>
        <a name='aedit' advid='<?= $model['advid'] ?>' href='return false;'>投放</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <?
          } ?>
      </td>
      </tr>
     <?}?>
      <tr class="operate">
        <td colspan="8">
            <div class="cuspages right">
<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
            </div>      
        </td>
    </tr>
    
  </table>
<script>
function changeHeight(obj){
  console.log($(obj).css('height'));
  if($(obj).css('height')=='20px'){
      $(obj).css('height','auto');
       $(obj).css('white-space','');
      
  }else{
      $(obj).css('height','20px');
      $(obj).css('white-space','nowrap');
  }
 
}
//新建按钮绑定事件
$('#btnnew').on('click', function(){
  addedit(0);
});

//编辑按钮绑定事件
$("a[name=aedit]").click(function () {
  addedit($(this).attr("advid"));
    return false;
});

//删除按钮绑定事件
$("a[name=adel]").click(function () {
  var advid = $(this).attr("advid");
    layer.confirm('删除后将不可恢复，确定删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/posid/del",
            data: "advid=" + advid,//要发送的数据                    
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
function addedit(advid){
  var content = '/adv/record_edit?advuid=<?=$advuid?>';
  var title = '添加推荐位';
  if(advid >0){
    content = content + '&advid=' + advid; 
    title = '编辑推荐位--编号:'+ advid;
  }
  layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['85%' , '85%'],
        content: content
    });
}

</script>