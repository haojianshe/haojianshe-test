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
         <th colspan="6" style="text-align: right;" >
                <form name="searchform" action="/sound/sel" method="get" >
                        <input type ="hidden" name='is_search' value='1' />
<input hidden type ="text" name='sound_type'   value="<?=$search['sound_type']?>" />
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
        <th width="80px;">选择</th>
        <th width="80px;">音频编号</th>
        <th >分类</th>
        <th >音频格式</th>
        <th>文件名称</th>
        <th>描述</th>
        <th width="200px;">创建时间</th>
        <th width="80px;">操作</th>
      </tr>
    </thead>
    <!-- 列表 -->
    <?
    foreach ($models as $model) { ?>
    <tr class="tb_list">

        <td><input name="sound" type="radio" value="<?= $model['soundid'] ?>" data-sourceurl="<?=$model['sourceurl']?>" /></td>
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

      <tr>
        <td colspan=7>
          <div style="margin-left:40%;margin-top:30px;">
            <span class="normalbtn_l"><a id="asave" class="button button-primary button-small" href="#asave">确认添加</a></span>
            <span class="normalbtn_l"><a id="aclose" class="button button-primary button-small" href="#aclose">关闭</a></span>
          </div>
        </td>
      </tr> 
  </table>

  <!-- 页面操作逻辑  开始-->
  <script type="text/javascript">
    var index = parent.layer.getFrameIndex(window.name);

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
  $("#asave").click(function(){
        parent.$("#soundid").val($('input:radio:checked').val());
        parent.$("#audio_sound").attr("src",$('input:radio:checked').data("sourceurl"));
        parent.layer.close(index);
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      parent.layer.close(index);
    });

</script>
<!-- 页面操作逻辑 结束-->

