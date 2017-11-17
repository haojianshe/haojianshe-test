<?php

use common\widgets\MyLinkPager;
?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>

<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
<!-- 图片浏览 引入结束-->


<!-- 图片浏览 引入开始-->
  <script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
  <link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
  <!--鼠标控制滚动-->
  <script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
  
<style>
    .button-small{
        height: 25px;
    }
</style>
<table cellspacing="0" cellpadding="0" class="content_list">
    <!--标题  -->
    <thead>
    <input type="hidden" name="typename" id="typeid" value="<?php echo $models['type'] ?>"/> 
    <input type="hidden" name="f_type" id="f_type" value="<?php echo $models['f_type'] ?>"/> 
    <tr>
        <th>
            共有<?= count($models['data']) ?>条记录
        </th>
    </tr>
    <tr >
        <th colspan="4" >
            <div class="button-group" >
                试卷类型：
                <select id="SumiaoClickid">
                    <option  value="1"  <?php if ($models['type'] == 1) {
    echo 'selected=selected';
} ?>>素描</option>
                    <option  value="2" <?php if ($models['type'] == 2) {
    echo 'selected=selected';
} ?>>色彩</option>
                    <option  value="3" <?php if ($models['type'] == 3) {
    echo 'selected=selected';
} ?>>速写</option>
                </select>
            </div>
        </th>

    </tr>
    <tr class="operate" >
        <th colspan="4"  style="padding-top: 50px;">
            <!--共有<?//= count($models['data']) ?>条记录-->
            <div class="button-group" >分档和打分切换:
                <select id="Dafen">
                    <option  value="1"  <?php if ($models['dangid'] == 1) {
    echo 'selected=selected';
} ?>>分档</option>
                    <option  value="2" <?php if ($models['dangid'] == 2) {
                            echo 'selected=selected';
                        } ?>>打分</option>
                </select>

                <span style="<?php if ($models['dangid'] == 2) {
                            echo 'display: none;';
                        } ?>">
                    <select id="select_fendang" style=" margin-left: 150px; ">
                        <option selected="" value="0" <?php
                        if ($models['select_dafen_val'] == 1) {
                            echo "selected=selected";
                        }
                        ?>>未分档列表</option>
                        <option value="9" <?php
                        if ($models['select_dafen_val'] == 9) {
                            echo "selected=selected";
                        }
                        ?> >90分档</option>
                        <option value="8" <?php
                        if ($models['select_dafen_val'] == 8) {
                            echo "selected=selected";
                        }
                        ?>>80分档</option>
                        <option value="7" <?php
                        if ($models['select_dafen_val'] == 7) {
                            echo "selected=selected";
                        }
                        ?>>70分档</option>
                        <option value="6" <?php
                        if ($models['select_dafen_val'] == 6) {
                            echo "selected=selected";
                        }
                        ?>>60分档</option>
                        <option value="5" <?php
                        if ($models['select_dafen_val'] == 5) {
                            echo "selected=selected";
                        }
                        ?>>不合格</option>
                    </select>
                    <button onclick="return insertData(9)">加入90分档</button>
                    <button onclick="return insertData(8)">加入80分档</button>
                    <button onclick="return insertData(7)">加入70分档</button>
                    <button onclick="return insertData(6)">加入60分档</button>
                    <button onclick="return insertData(5)">加入不合格分档</button>

                </span>
                <span id="dafen1" style=" <?php if ($models['dangid'] == 1) {
                            echo 'display: none;';
                        } ?> margin-left: 170px;">
                    <select id="select_dafen">
                        <option  value="0" <?php
                        if ($models['select_dafen_val'] == "") {
                            echo "selected=selected";
                        }
                        ?>>未分档列表</option>
                        <option  value="9"  <?php
                        if ($models['select_dafen_val'] == 9) {
                            echo "selected=selected";
                        }
                        ?>>90分档</option>
                        <option value="8" <?php
                        if ($models['select_dafen_val'] == 8) {
                            echo "selected=selected";
                        }
                        ?>>80分档</option>
                        <option value="7"  <?php
                        if ($models['select_dafen_val'] == 7) {
                            echo "selected=selected";
                        }
                        ?>>70分档</option>
                        <option value="6"  <?php
                        if ($models['select_dafen_val'] == 6) {
                            echo "selected=selected";
                        }
                        ?>>60分档</option>
                        <option value="5"  <?php
                        if ($models['select_dafen_val'] == 5) {
                            echo "selected=selected";
                        }
                        ?>>不合格</option>
                    </select>
                </span>
                <span style=" ">
                    <button id="simulation">统计</button>
                </span>
            </div>
        </th>
    </tr>
</thead>
<!-- 分页 -->
<tr class="operate">
    <td colspan="6">
        <div class="cuspages right">
<?= MyLinkPager::widget(['pagination' => $pages,]); ?>
        </div>      
    </td>
</tr>
</table>

<div id="fendanliebiao" style="<?php
if ($models['dangid'] == 2) {
    echo "display: none;";
}
?>">
    <!-- 列表 -->
    <? foreach ($models['data'] as $model) { ?>
    <span >
        <div style="border:2px solid #DDD; float: left;margin: 9px;">
            <input type="checkbox" name="checkid" size="1px;" value="<?php echo $model['picid'] ?>" id="fd_<?php echo $model['picid'] ?>" style="margin-left: 2px;"/> 
                   <a id="example1"  rel="group" href="<?php echo $model['img_json'] ?>">
                <img style=" width:auto; height: 300px;" name="prew_resource" src="<?php echo $model['img_json'] . '@400h_2o' ?>" />
              </a>
        </div>
    </span>
    <?}?>
</div>
<div id="dafenliebiao" style=" <?php
     if ($models['dangid'] == 1) {
         echo "display: none;";
     }
?>">
    <!-- 列表 -->
    <? foreach ($models['data'] as $model) { ?>
    <span >
        <div style="border:2px solid #DDD; float: left;margin: 9px;">
            <input class="<?php echo $model['picid'] ?>" type="text" name="valuedafen" maxlength="3" size="3px;" value="<?php echo $model['score'] ?>" id="df_<?php echo $model['picid'] ?>" style="margin-left: 2px;"/>
            <!--<img src="<?///php echo $model['img_json'] ?>" style=" width:auto; height: 300px;"/>-->
             <a id="example2"  rel="group" href="<?php echo $model['img_json'] ?>">
                <img style=" width:auto; height: 300px;" name="prew_resource" src="<?php echo $model['img_json'] . '@400h_2o' ?>" />
              </a>
            
        </div>
    </span>
    <?}?>
</div>
<input type="hidden" id="typeid" value="<?= $models['type'] ?>"/>
<input type="hidden" id="dangid" value="<?= $models['dangid'] ?>"/>
<input type="hidden" id="select_dafen_val" value="<?= $models['select_dafen_val'] ?>"/>

<!-- 图片浏览 开始-->
<script language="javascript" type="text/javascript"> 
     $("a#example1").fancybox({
      type:'image',
      afterLoad : function() {
        this.title = '图片数：' + (this.index + 1) + '/' + this.group.length + (this.title ? ' - ' + this.title : '');
        },
        loop:false,
      padding: 2,
      helpers : {
          title : {
            type : 'inside'
          }
      }
     });
</script>
<!-- 图片浏览 结束 -->

<!-- 图片浏览 开始-->
<script language="javascript" type="text/javascript"> 
     $("a#example2").fancybox({
      type:'image',
      afterLoad : function() {
          this.title = '图片数：' + (this.index + 1) + '/' + this.group.length + (this.title ? ' - ' + this.title : '');
        },
        loop:false,
      padding: 2,
      helpers : {
          title : {
            type : 'inside'
          }
      }
     });
</script>
<!-- 图片浏览 结束 -->

<!-- 页面操作逻辑  开始-->
<script type="text/javascript">

    //统计分档
    $("#simulation").click(function () {
        var content = '/lkactivity/statistical';
        var title = '统计分档分数';
        content = content + '?SumiaoClickid='+ $("#SumiaoClickid").val();
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['60%', '80%'],
            content: content
        });
    });


    function insertData(d) {
        var chk_value = [];
        $('input[name="checkid"]:checked').each(function () {
            chk_value.push($(this).val());
        });
        var type = $("#typeid").val();
        var dangid = $("#dangid").val();
        var select_fendang = $("#select_fendang").val();

        if (chk_value.length == 0) {
            layer.msg('请选择要归属试卷!', {icon: 2});
            return false;
        }
        var url = '/lkactivity/simulationdetail?type=' + type + "&dangid=" + dangid + "&fendang=" + select_fendang + "&choose=" + chk_value + "&status=" + d;
        window.location.href = url;
        //alert(chk_value.length == 0 ? '你还没有选择任何内容！' : chk_value);
    }
    //分档效果处理
    // function fengdang() {
    $("#Dafen").change(function () {
        var val = $(this).val();
        var type = $("#typeid").val();
        var url = '/lkactivity/simulationdetail?type=' + type + "&dangid=" + val;
        window.location.href = url;
    });

    //分档请求
    $("#select_fendang").change(function () {
        var type = $("#typeid").val();
        var dangid = $("#dangid").val();
        var select_fendang = $(this).val();
        var url = '/lkactivity/simulationdetail?type=' + type + "&dangid=" + dangid + "&fendang=" + select_fendang;
        window.location.href = url;

    });

    //分数分档
    $("#select_dafen").change(function () {
        var type = $("#typeid").val();
        var dangid = $("#dangid").val();
        var select_fendang = $(this).val();
        var daf = 1;
        var url = '/lkactivity/simulationdetail?type=' + type + "&dangid=" + dangid + "&fendang=" + select_fendang + "&daf=1";
        window.location.href = url;

    });

    //类型切换素描
    $("#SumiaoClickid").change(function () {
        var dangid = $("#dangid").val();
        var select_fendang = $("#select_fendang").val();
        var type = $(this).val();
        var url = '/lkactivity/simulationdetail?type=' + type + "&dangid=" + dangid + "&fendang=" + select_fendang;
        window.location.href = url;
    });



    $("input[name=valuedafen]").click(function () {
         var val = $(this).val();
           if(val==0){
               $(this).val('');
                return false;
           }
           if(val==''){
               layer.msg('请你录入分数', {icon: 2});
               return false;
           }
    });
    
    $("input[name=valuedafen]").blur(function () {
        var val = $(this).val();
        var select_val = $("#select_dafen").val();
        var id = $(this).attr('class');
        var url = '/lkactivity/updatescore';
        var data_min = select_val + '0';
        var data_max = parseInt(data_min) + 10;
        if (select_val == 5) {
            if (val > 59) {
                layer.msg('打分区间不能大于分档区间,请您从新打分', {icon: 2});
                $("#df_"+id).val(0);
                return false;
            }
            if (val < 0) {
                 layer.msg('分数不能为负数,请您从新打分', {icon: 2});
                return false;
            }
        } else {
            if (select_val == 9) {
                if (val < 90 || val >100) {
                    layer.msg('打分区间不能大于分档区间,请您从新打分', {icon: 2});
                    $("#df_"+id).val(0);
                    return false;
                }
            } else {
                if (val < data_min || val >= data_max) {
                     layer.msg('打分区间不能大于分档区间,请您从新打分', {icon: 2});
                    $("#df_"+id).val(0);
                    return false;
                }
            }
        }
        var json = {
            val: val,
            id: id,
            select_val: select_val
        }

        $.post(url, json, function (m) {
            if (m.errno == 0) {
                 layer.msg('分数录入成功', {icon: 1});
                return;
            } else {
                layer.msg('分数录入失败!', {icon: 2});
                return;
            }
        }, 'json');
    });


</script>
<!-- 页面操作逻辑 结束-->


