  <?php
  use common\widgets\MyLinkPager;
  use common\service\DictdataService;
  use mis\service\HomePopAdvService;
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
        <th colspan="7" >
          共有<?= $pages->totalCount ?>条记录
        </th>
        <th colspan="3" style='text-align:right;'>
          <input type="button" id="btnnew" value="添加硬广弹窗" class="button-small  button-primary "/>
        </th>
      </tr>
      <tr class="tb_header">
        <th style='width:80px'>编号</th>
        <th style='width:80px' >标题</th>
        <th >跳转类型</th>
        <th >身份</th>
        <th >地区</th>
        <th >有效日期</th>
        <th >状态</th>
        <th >操作</th>
      </tr>
    </thead>
    <? foreach ($models as $model) { ?>
      <tr class="tb_list">
      <td><?= $model['advid'] ?></td>
      <td><?= $model['title'] ?></td>
      <td><?= DictdataService::getPosidHomeTypeById($model['typeid'])['typename'] ?></td>
      <td><?= HomePopAdvService::getProfessionameByIds($model['professionid']) ?></td>
      <td><?= HomePopAdvService::getProvinceameByIds($model['provinceid']) ?></td>
      <td><?= date('Y-m-d H:i:s',$model['btime']); ?>至 <?= date('Y-m-d H:i:s',$model['etime']); ?></td>
      <td><?
        if($model['btime']<time() && time()<$model['etime']){
            echo "使用中";
        }else{ 
            echo "未使用" ;
        }

        ?></td>

      <td>
        <a name='aedit' advid='<?= $model['advid'] ?>' href='#'>编辑</a>
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

//编辑或新增用户页面
function addedit(advid){
  var content = '/homepopadv/edit';
  var title = '新增硬广弹窗';
  if(advid >0){
    content = content + '?advid=' + advid; 
    title = '编辑-编号:'+ advid;
  }
  layer.open({
        type: 2,
        title: title,
        maxmin: false,
        shadeClose: false, //点击遮罩关闭层
        area : ['80%' , '85%'],
        content: content
    });
}
</script>