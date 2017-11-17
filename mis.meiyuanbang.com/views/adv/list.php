  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
    <link rel="stylesheet" type="text/css" href='/static/css/pager.css'> 

  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <style type="text/css">
          td{
            max-width: 200px;
          }
        </style>
      <tr class="operate">
       
        <th colspan="9" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="3" style='text-align:right;'>
          <input type="button" id="btnnew" value="新增广告" class="button-small  button-primary "/>
        </th>
      </tr>
      <tr class="tb_header">
        <th style='width:80px'>推荐位id</th>
        <th >类型</th>
        <th style='width:80px' >标题</th>
        <th >图片</th>
        <th >参数1</th>
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
      <td><?= $model['advid'] ?></td>
      <td><?= $model['typeid'] ?></td>
      <td><?= $model['title'] ?></td>
      <td>
      <a href='<?=$model['topimage1'] ?>' target='_blank'><img  src="<?=$model['topimage1'] ?>" style='padding-left:15px;width:100px '  /></a>
      </td>
      <td><?= $model['param1'] ?></td>
      <td style="max-width: 100px;"><?= $model['param2'] ?></td>
      <td style="max-width: 100px;"><?= $model['param3'] ?></td>
      <td style="max-width: 100px;"><?= $model['param4'] ?></td>
      <td><?= $model['param5'] ?></td>
      <td><?= $model['listorder'] ?></td>
      <td><?= date('Y-m-d H:i:s',$model['ctime']); ?></td>
      <td>
        <a name='aedit' advid='<?= $model['advid'] ?>' href='#'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a name='adel' advid='<?= $model['advid'] ?>' href='#'>删除</a>
      </td>
      </tr>

     <?}?>

      <tr class="operate">
        <td colspan="12">
            <div class="cuspages right">
<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
            </div>      
        </td>
    </tr>

  </table>
<script>
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
            url: "/adv/del",
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
  var content = '/adv/edit?advuid=<?=$advuid?>';
  var title = '新增广告';
  if(advid >0){
    content = content + '&advid=' + advid; 
    title = '广告编辑-编号:'+ advid;
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