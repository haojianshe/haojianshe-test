<?php

use common\widgets\MyLinkPager;
use common\service\dict\SubjectDictService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- 图片浏览 引入结束-->
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>
        <tr class="operate">
            <th colspan="1" >
                共有<?= $pages->totalCount ?>条记录
            </th>
            <th colspan="7" style='text-align:right;'>
                <input type="button" id="btnnew" value="新建专题" class="button"/>
            </th>
        </tr>
    <style type="text/css">
        td{
            max-width: 200px;
        }

    </style>
    <tr class="tb_header">
        <th>专题编号</th> 
        <th>专题名称</th>
        <th>专题分类</th>
        <th>封面图</th>
        <th>点击量</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
</thead>
<? foreach ($models as $model) { ?>
<tr class="tb_list">
    <td><?= $model['subjectid'] ?></td>
    <td><?= $model['title'] ?></td>
    <td>
        
        
        <?php
                          foreach(SubjectDictService::getSubjectMainType()['s_catalog'] as $key=>$val){
                              if($model['subject_typeid']==$val['id']){
                                  echo $val['name'];
                              }
                       
                         }
//        switch ($model['subject_typeid']){
//        case 2:
//            echo '名师';
//            break; 
//        case 3:
//            echo '大师';
//            break;
//        case 4:
//            echo '联考';
//            break; 
//        case 5:
//            echo '校考';
//            break;
//         }
    
    ?>
    
    </td>
    <td>
        <a id="example1" title="<?= $model['title'] ?>" rel="group<?= $model['subjectid'] ?>" href="<?= json_decode($model['picurl'])->n->url ?>">
            <img style="width:80px;height:80px;padding:3px;" src="<?= json_decode($model['picurl'])->n->url ?>">
        </a></td>
    <td><?= $model['hits'] ?></td>
    <td><?= date("Y-m-d H:i:s", $model['ctime']) ?></td>
    <td>
        <?php
        if($model['status']==2){
        ?>
         <a href="javascript:;"  name='shenhe' subjectid='<?= $model['subjectid'] ?>' style="color:red">审核&nbsp;&nbsp;</a>  
         <?php
        }else{
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
         ?>
        <a href="javascript:;"  name='aedit' subjectid='<?= $model['subjectid'] ?>'>编辑&nbsp;&nbsp;</a>           
        <a href="javascript:;"  name='sort' id="sort" subjectid='<?= $model['subjectid'] ?>'>排序&nbsp;&nbsp;</a>   
        <?php
        if($model['stick_date']){
        ?>        
        <a href="javascript:;"  name='stick' id="stick" subjectid='<?= $model['subjectid'] ?>' style="color:red">取消置顶&nbsp;&nbsp;</a>
        <?php
        }else{
        ?>
        <a href="javascript:;"  name='Astick' id="Astick" subjectid='<?= $model['subjectid'] ?>' style=" color: green" >置顶&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
         <?php
        }
         ?>
        <a onclick="del(<?= $model['subjectid'] ?>,3)">删除</a> 

    </td>
</tr> 
<?}?>
<tr class="operate">
    <td colspan="6">&nbsp;
        <div class="cuspages right">
<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>      
    </td>
</tr>
</table>
<div id="_tips"></div>
<script type="text/javascript">
    
       //置顶操作
    $("a[name=Astick]").click(function () {
        del($(this).attr("subjectid"),1);
        return false;
    });
    
    //审核操作
    $("a[name=shenhe]").click(function () {
        del($(this).attr("subjectid"),4);
        return false;
    });
    
    
    
      //取消置顶操作
    $("a[name=stick]").click(function () {
        del($(this).attr("subjectid"),0);
        return false;
    });
    
    
    //删除专题 置顶操作
    function del(subjectid,i) {
        var title='';
        if(i==3){
            title='是否删除？';
        }else if(i==1){
              title='置顶操作？';
        }else if(i==0){
              title='取消置顶操作？';
        }else if(i==4){
              title='是否确定通过审核？';
        }
        layer.confirm(title, {
            btn: ['确定', '否'] //按钮
        }, function () {
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/material/del",
                data: "subjectid=" + subjectid + "&is_del=1&type="+i, //要发送的数据
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

        }, function () {});
    }

    //编辑
    $("a[name=aedit]").click(function () {
        addedit($(this).attr("subjectid"));
        return false;
    });
    //进入编辑页面的弹层函数
    function addedit(subjectid) {
        var content = '/material/edit';
        var title = '编辑专题';
        if (subjectid > 0) {
            content = content + '?subjectid=' + subjectid;
            title = '编辑专题';
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: true,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '80%'],
            content: content
        });
    }

    //排序
    $("a[name=sort]").click(function () {
        sort($(this).attr("subjectid"));
        return false;
    });
    
    




    //进入编辑页面的弹层函数
    function sort(subjectid) {
        var content = '/material/sort';
        var title = '排序';
        if (subjectid > 0) {
            content = content + '?subjectid=' + subjectid;
            title = '排序';
        }
        layer.open({
            type: 2,
            title: title,
            maxmin: true,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '80%'],
            content: content
        });
    }



    $("#btnnew").click(function () {
        var content = '/material/edit';
        layer.open({
            type: 2,
            title: "新建专题",
            maxmin: true,
            shadeClose: false, //点击遮罩关闭层
            area: ['80%', '80%'],
            content: content
        });
    });
</script>


<!-- 图片浏览 开始-->
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $("a#example1").fancybox({
            type: 'image',
            afterLoad: function () {
                this.title = '图片数：' + (this.index + 1) + '/' + this.group.length + (this.title ? ' - ' + this.title : '');
            },
            loop: false,
            padding: 2,
            helpers: {
                title: {
                    type: 'inside'
                }
            }
        });
    });
</script>
<!-- 图片浏览 结束 -->