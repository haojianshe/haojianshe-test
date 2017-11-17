<?php 
use common\widgets\MyLinkPager;
use common\service\dict\StudioDictDataService;
#getBookMainType
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
  <table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
      <tr class="operate">
        <th colspan="5" style='text-align:right;'>
            <input type="button"  id="btnnew" value="添加文章" class="button"/>
            <!--<input type="button"  id="addArt" value="添加文本" class="button"/>-->
        </th>
      </tr>
      <tr class="tb_header">
        <th>页面编号</th>
        <th>标题（备注）</th>
        <th>类型</th>
        <th>排序字段</th>
        <th>操作</th>
      </tr>
    </thead>
    <?php foreach ($models['models'] as $model) { ?>
      <tr class="tb_list">
        <td ><?= $model['articleid'] ?></td>
        <td ><?= $model['title'] ?></td>
        <td ><?= $model['article_type']==1?'文章':'文本'; ?></td>
        <td><input type="text" value="<?= $model['listorder'] ?>" name="listorder" size="3" articleid="<?php echo $model['articleid'] ?>" /></td>
       <td>
            <a  name='editcontent' articleid="<?php echo $model['articleid'] ?>" newsid="<?php echo $model['newsid'] ?>" article_type='<?= $model['article_type'] ?>'  uid='<?= $model['uid'] ?>'   href='javascript:;' style="cursor: pointer">编辑</a>&nbsp;&nbsp;
             <a name='listorderdel' articleid="<?php echo $model['articleid'] ?>" newsid="<?php echo $model['newsid'] ?>" uid="<?php echo $model['uid'] ?>"  href='javascript:;' style="cursor: pointer">删除</a>&nbsp;&nbsp;
            </td>
      </tr>
    <?php }?>
    <tr class="operate">
      <td colspan="7">
        <div class="cuspages right">
          <?= MyLinkPager::widget(['pagination' =>$models['pages'],]); ?>
        </div>
      </td>
    </tr>
  </table>
<input type="hidden" id="studiomenuid" value="<?php echo $models['studiomenuid'];?>" />
<input type="hidden" id="menuid" value="<?php echo $menuid;?>" />
<input type="hidden" id="uid" value="<?php echo $uid;?>" />
<script>
       //排序
        $("input[name=listorder]").blur(function () {
        var articleid = $(this).attr("articleid");
        var menuid = $("#menuid").val();
        var uid = $("#uid").val();
        var value = $(this).val();
        layer.confirm('确定要操作吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/studio/articlelistorder",
                data: "articleid=" + articleid+"&value="+value+"&uid="+uid+"&menuid="+menuid, //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("访问出错", {icon: 2});
                }
            });
        }, function () {
            //取消
        });
        return false;
    });
       //删除
        $("a[name=listorderdel]").click(function () {
        var articleid = $(this).attr("articleid");
         var menuid = $("#menuid").val();
        var uid = $("#uid").val();
        var value = '1.11';
        layer.confirm('确定要删除吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            //确定进行删除
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/studio/articlelistorder",
                data: "articleid=" + articleid+"&value="+value+"&uid="+uid+"&menuid="+menuid, //要发送的数据                    
                success: function (data) {
                    if (data.errno == 0) {
                        window.location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("访问出错", {icon: 2});
                }
            });
        }, function () {
            //取消
        });
        return false;
    });
    
    
    //编辑
    $("a[name=editcontent]").click(function () { 
    	addedit($(this).attr("articleid"),$(this).attr("newsid"),$(this).attr("article_type"));
        return false;
    });
    //添加文章
      $("#btnnew").click(function () {
    	addedit(0,0,1);
        return false;
    });
    
      //添加文本
      $("#addArt").click(function () {
    	addedit(0,0,2);
        return false;
    });
    
    
     //编辑或新增
    function addedit(articleid,newsid,s){
        var studiomenuid = $("#studiomenuid").val();
        if(s==1){
            var content = '/studio/editarticle';
        }else{
            var content = '/studio/edittext';
        }
    	var title = '添加';
    	if(articleid >0){
        content = content + '?studiomenuid=' + studiomenuid+"&articleid="+articleid+"&newid="+newsid+"&menuid="+$("#menuid").val()+"&uid="+$("#uid").val(); 
        title = "编辑编号："+articleid;
    	}else{
        content = content + '?studiomenuid=' + studiomenuid+"&articleid=0&newid=0&menuid="+$("#menuid").val()+"&uid="+$("#uid").val(); 
        }
    	layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['80%' , '80%'],
            content: content
      });
    }
</script>