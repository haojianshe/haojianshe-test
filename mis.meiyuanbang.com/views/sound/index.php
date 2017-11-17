  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="2" >
           共有<?= $pages->totalCount ?>条记录 
        </th>
         <th colspan="5" style="text-align: right;" >
                <form name="searchform" action="/sound" method="get" >
                        <input type ="hidden" name='is_search' value='1' />

                          <select name="sound_type" >
                                    <option value="1"  <? if($search['sound_type']==1){echo "selected";}?>>精讲文章</option>
                                    <option value="2" <? if($search['sound_type']==2){echo "selected";}?> >跟着画</option>
                          </select>
                         文件名：<input type ="text" name='filename'   value="<?=$search['filename']?>" />
                          描述：<input type ="text" name='desc'  value="<?=$search['desc']?>" />
                          <input type="submit" name="search" class="button button-primary button-small" value="搜索" />
                          <input type="button" onclick='addedit(0)' value="新建" class="button">
                </form>
        </th>
      </tr>
      <style type="text/css">
      </style>
      <tr class="tb_header">
        <th width="80px;">音频编号</th>
        <th >分类</th>
        <th >音频格式</th>
        <th >文件名称</th>
        <th>描述</th>
        <th width="200px;">创建时间</th>
        <th width="80px;">操作</th>
      </tr>
    </thead>
    <!-- 列表 -->
    <?
    foreach ($models as $model) { ?>
    <tr class="tb_list">
      	<td><?= $model['soundid'] ?></td>
      	<td><?
			switch (intval($model['sound_type'])) {
				//1=>精讲文章 2=>跟着画
				case 1:
					echo '精讲文章';
					break;
				case 2:
					echo '跟着画';
					break;
			}
       	?>
       	</td>

       	<td>
	       	<?
	       		switch (intval($model['filetype'])) {
	       			//1=>Mp3,2=>Amr
	       			case 1:
	       				echo 'Mp3';
	       				break;
	       			case 2:
	       				echo 'Amr';
	       				break;

	       		}
	       	?>
       	</td>
       	<td><?= $model['filename'] ?></td>
       	<td><?= $model['desc'] ?></td>
       	<td><?= date("Y-m-d H:i:s",$model['ctime']) ?></td>
		<td style="width: 100px;">
        <a name='aedit' onclick='addedit(<?= $model['soundid'] ?>)' href="javascript:;" >编辑</a>
      </td>
    </tr>
    <?}?>
    <!-- 分页 -->
    <tr class="operate">
      <td colspan="7">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>      
      </td>
    </tr>
  </table>

  <!-- 页面操作逻辑  开始-->
  <script type="text/javascript">
  //编辑标签
  function addedit(soundid){
    var content = '/sound/edit';
    var title = '编辑声音文件';
    if(soundid >0){
      content = content + '?soundid=' + soundid; 
      title = '编辑声音--编号:'+ soundid;
    }
    layer.open({
      type: 2,
      title: title,
      maxmin: false,
          shadeClose: false, //点击遮罩关闭层
          area : ['800px' , '600px'],
          content: content
        });
  }
  
</script>
<!-- 页面操作逻辑 结束-->

