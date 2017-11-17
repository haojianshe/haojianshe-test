  <?php
  use common\widgets\MyLinkPager;
  ?>
<link rel="stylesheet" type="text/css" href='/static/css/pager.css'>
<table cellspacing="0" cellpadding="0" class="content_list">
    <thead>    
      <tr class="tb_header">
        <th style="width:25%">选择</th>
        <th style="width:25%">课程封面图</th>
        <th style="width:25%">课程标题</th>
        <th style="width:25%">课程描述</th>
      </tr>
    </thead>
      <?php
      foreach ($models['models'] as $model) { ?>
      <tr class="tb_list" name="user">
        <td>
            <input type="checkbox" class="selteacher" name="checkbox" 
                   id="check_<?= $model['courseid'] ?>"
                   value="<?= $model['courseid'] ?>" <?php if($model['type']== 1){echo "checked";}?> 
                   data-sname="<?= $model['title'] ?>" value="<?= $model['courseid'] ?>" > 
        </td>
        <td><img src='<?= $model['thumb_url']?>' style='height:80px;width:80px;'/> </td>
        <td><?= $model['title'] ?></td>
        <td><?php echo $model['teacher_desc'];?></td>
      </tr> 
      <?php }?>
      <tr class="operate">
        <th colspan="3" >
          共有<?= $models['pages']->totalCount ?>条记录
        </th>
        <th colspan="1" style='text-align:right;'>
        </th>
      </tr>
       <!-- 分页 -->
     <tr class="operate">
        <td colspan="4">
        <div class="cuspages right">
        <?= MyLinkPager::widget(['pagination' => $models['pages']]); ?>
        </div>      
        </td>
      </tr>
       <tr>
        <td colspan=8>
          <div style="margin-left:40%;margin-top:30px;">
            <span class="normalbtn_l"><a id="asave" class="button" href="#">确认</a></span>
            <span class="normalbtn_l"><a id="aclose" class="button" href="#">关闭</a></span>
          </div>
        </td>
      </tr> 
     <tr>
      </tr>
      <input type="hidden" value="<?php echo $models['subjectid']?>" id="subjectid" />
  </table>  

<script>
    var index = parent.layer.getFrameIndex(window.name);
      //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
        parent.layer.close(index);
    });
    $("#asave").click(function(){
         parent.location.reload();
               parent.layer.close(index);
               
//         var chk_value =[]; 
//        $('input[name="checkbox"]:checked').each(function(){ 
//        chk_value.push($(this).val()); 
//        });
//       var str = chk_value.join(",");
//        var subjectid = $("#subjectid").val();
//         var url = '/course/delvideosubject';
//        var data = {
//            courseid: str,
//            videostr :'<?php // echo $videostr;?>',
//            subjectid: subjectid
//        }
//        $.post(url, data, function (m) {
//               //layer.msg('添加成', {icon: 1});
//               parent.location.reload();
//               parent.layer.close(index);
//        }, 'json');
        //jqchk();
        //parent.location.reload(); 
        //parent.layer.close(index);
    });
    //关闭按钮,刷新父窗口
    $('#aclose').click(function(){
      parent.layer.close(index);
    });
    
    function jqchk(){ //jquery获取复选框值 
        var chk_value =[]; 
        $('input[name="checkbox"]:checked').each(function(){ 
        chk_value.push($(this).val()); 
        });
       var str = chk_value.join(",");
       
        var subjectid = $("#subjectid").val();
         var url = '/course/delmybbook';
        var data = {
            courseid: str,
            subjectid: subjectid
        }
        $.post(url, data, function (m) {
            //layer.msg(title, {icon: 1});
              // parent.location.reload();
        }, 'json');
        //alert(chk_value.length==0 ?'你还没有选择任何内容！':chk_value); 
 } 
    
    
    //删除联考活动
        $("input[name=checkbox]").click(function () {
             var courseid = $(this).val();
             var subjectid = $("#subjectid").val();
            var ss = $('#check_' + courseid).is(':checked');
            if (ss == true) {
                var status = 1;
               var title = '添加成功!';
            } else {
                var status = 2;
               var  title = '删除成功!';
            }
            var url = '/course/delvideosubject';
            var data = {
                courseid: courseid,
                status: status,
                subjectid: subjectid
            }
            $.post(url, data, function (m) {
                //layer.msg(title, {icon: 1});
                  // parent.location.reload();
            }, 'json');
        });
  
</script>