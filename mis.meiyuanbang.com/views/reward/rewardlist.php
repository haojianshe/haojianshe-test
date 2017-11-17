<html >
<?php
use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
 <link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
 <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
         <th colspan="12" >
        </th>
        <tr class="operate">
            <th colspan="2" >
                共有<?php echo $pages->totalCount ?>条记录
            </th>
        </tr>
        <tr class="tb_header">
            <th ></th>
            <th >活动编号11</th>
            <th >标题</th>
            <th >图片</th>
            <th >类型</th>
            <th >创建时间</th>
        </tr>
    </thead>
    <?php 
    foreach ($models as $model) { ?>
        <tr class="tb_list">
            <td>
                <input type="radio" id="redioName"  <?php if($id==$model['prizesid']){ echo 'checked=checked';}?> name="redio" value="<?php echo $model['img'].','.$model['prizesid']; ?>">
                <input type="hidden" id="redioVal" name="hidden" value="<?php echo $model['prizesid']; ?>">
            </td>
            <td><?php echo $model['prizesid']; ?></td>
            <td><?php echo $model['title']; ?></td>
            <td>
                <img style="width:40px;height:40px;padding:3px;" src="<?php echo $model['img']; ?>">
            </td>
            <td><?php
                if ($model['type'] == 1) {
                    echo '金币';
                } elseif ($model['type'] == 2) {
                    echo '虚拟物品';
                } elseif ($model['type'] == 3) {
                    echo '实物';
                }
                ?></td>
            <td><?php
                if ($model['ctime']) {
                    echo date('Y-m-d H:i', $model['ctime']);
                }
                ?> 
            </td>
        </tr>
        <?php
    }
    ?>
    <tr class="operate">
        <td colspan="6">
            <div class="cuspages right">
                <?= MyLinkPager::widget(['pagination' => $pages]); ?>
            </div>      
        </td>
    </tr>
    <tr>
        <td>
            <div>
                <span class="normalbtn_l" style=" margin-left: 20px;"><a id="asave" href="#">确定选择</a></span>
          </div>
        </td>
    </tr>
</table>
 <div>
     <input type="hidden" id="imgid" value='<?php echo $v;?>' />
     <input type="hidden" id="valid" value='<?php echo $i;?>' />
     <input type="hidden" id="valid" value='<?php echo $id;?>' />
 </div>
<div id="_tips"></div>
<script>
//新建按钮绑定事件
 $("#asave").click(function(){
        var index = parent.layer.getFrameIndex(window.name);
        var radioName = $("input[type='radio']:checked").val();
        if(radioName==undefined){
           layer.msg('请至少选择一项奖品', {icon: 2});
            return;
        }
        var str = new String();
        var arr = new Array();
        var arr = radioName.split(',');
        var imgid = $("#imgid").val();
        var valid = $("#valid").val();
        parent.$("#"+imgid).attr("src",arr[0]);
        parent.$("#"+valid).val(arr[1]);
        parent.layer.close(index);
    });
</script>