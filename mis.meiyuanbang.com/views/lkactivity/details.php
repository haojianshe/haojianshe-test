<?php
use common\widgets\MyLinkPager;
use common\service\DictdataService;
use common\service\CommonFuncService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>

<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- 图片浏览 引入结束-->

<table cellspacing="0" cellpadding="0" class="content_list">
    <!--标题  -->
    <thead>
        <tr class="operate">
            <th colspan="7" >
                共有<?= count($models) ?>条记录
            </th>
        </tr>
        <tr class="tb_header">
            <th >报名编号</th>
            <th >姓名</th>
            <th >用户</th>
            <th >画室</th>
            <th >年级</th>
            <th >作品（素描、色彩、速写）总分数</th>
            <th >（素描、色彩、速写）</th>
            <th >创建时间</th>
            <th >操作</th>
        </tr>
    </thead>
    <!-- 列表 -->
    <?php
    foreach ($models as $model) { ?>
    <tr class="tb_list">
        <td><?= $model['paperid'] ?></td>
        <td><?= $model['user_name'] ?></td>
        <td><?= $model['sname'] ?></td>
        <td><?= $model['studio_name'] ?></td>
        <td><?= DictdataService::getProfessionById(intval($model['professionid'])) ?></td>
        <td>
            <?php
            echo "(";
            $i = 1;
            foreach($model['data'] as $key=>$val){
            ?>
            <span id="score"><?php echo $val['score']?><?php
            if($i<3){
                echo ',';
            }
            ?></span>
            <?php
            $i++;
            }
            echo ")";
            ?>&nbsp;&nbsp;&nbsp;&nbsp;
            <?= $model['count'] ?>
        </td>
       
         <td>
            <?php
             foreach($model['data'] as $key=>$val){
                   $img = json_decode($val['img_json'],true);
                   $imgArray =  CommonFuncService::getPicByType($img,'l');
                   echo " <img   width=100px height=100px  src=".$imgArray['n']['url'].">";
             }
            ?>
        </td>
       
        <td><?= date('Y-m-d H:i', $model['ctime']) ?></td>
        <td>
            <a name='adel' onclick='del(<?= $model['paperid'] ?>, 0)' href='#'>删除</a>
        </td>
    </tr>
    <?php }?>
    <!-- 分页 -->
    <tr class="operate">
        <td colspan="6">
            <div class="cuspages right">
                <?= MyLinkPager::widget(['pagination' => $pages,]); ?>
            </div>      
        </td>
    </tr>
</table>

<!-- 页面操作逻辑  开始-->
<script type="text/javascript">
   

    //删除能力素材
    function del(paperid, status) {
        var msg = '';
        if (status == 0) {
            msg = "是否删除？";
        } 
        layer.confirm(msg, {
            btn: ['确定', '否'] //按钮
        }, function () {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/lkactivity/delpic",
                data: "paperid=" + paperid + "&status=" + status, //要发送的数据                    
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

        });
    }
  </script>

