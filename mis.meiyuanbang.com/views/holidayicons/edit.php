  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
  use common\service\DictdataService;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'roleform']); ?>    
   <table>
    <tbody>
      <?php if(isset($model->iconsid)){?>
      <tr>
       <td style="width: 200px;">编号</td>
       <td style="width:400px;">
         <input type ="hidden" name='isedit' value='1' />
         <input class="inputclass1" name="HolidayIconsService[iconsid]" style="width:50px" type="text" value="<?= $model->iconsid ?>" readonly='true' />
       </td>
     </tr>
     <?php } ?>

    
<tr>
   <td>底部导航<span class='need'>*</span>100*100</td>
   <td>
       <input type ="hidden" id="thumb_bottom_nav1_url" name="HolidayIconsService[bottom_nav1_url]" value="<?= $model->bottom_nav1_url ?>" />     
       <a name='athumb' id='athumb_bottom_nav1_url' data-name="bottom_nav1_url" thumbid='0' href='#'><img id='imgthumb_bottom_nav1_url' src="<? if($model->bottom_nav1_url){echo $model->bottom_nav1_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>


        <input type ="hidden" id="thumb_bottom_nav2_url" name="HolidayIconsService[bottom_nav2_url]" value="<?= $model->bottom_nav2_url ?>" />     
       <a name='athumb' id='athumb_bottom_nav2_url' data-name="bottom_nav2_url" thumbid='0' href='#'><img id='imgthumb_bottom_nav2_url' src="<? if($model->bottom_nav2_url){echo $model->bottom_nav2_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>

        <input type ="hidden" id="thumb_bottom_nav3_url" name="HolidayIconsService[bottom_nav3_url]" value="<?= $model->bottom_nav3_url ?>" />     
       <a name='athumb' id='athumb_bottom_nav3_url' data-name="bottom_nav3_url" thumbid='0' href='#'><img id='imgthumb_bottom_nav3_url' src="<? if($model->bottom_nav3_url){echo $model->bottom_nav3_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>

        <input type ="hidden" id="thumb_bottom_nav4_url" name="HolidayIconsService[bottom_nav4_url]" value="<?= $model->bottom_nav4_url ?>" />     
       <a name='athumb' id='athumb_bottom_nav4_url' data-name="bottom_nav4_url" thumbid='0' href='#'><img id='imgthumb_bottom_nav4_url' src="<? if($model->bottom_nav4_url){echo $model->bottom_nav4_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>

       <input type ="hidden" id="thumb_bottom_nav5_url" name="HolidayIconsService[bottom_nav5_url]" value="<?= $model->bottom_nav5_url ?>" />     
       <a name='athumb' id='athumb_bottom_nav5_url' data-name="bottom_nav5_url" thumbid='0' href='#'><img id='imgthumb_bottom_nav5_url' src="<? if($model->bottom_nav5_url){echo $model->bottom_nav5_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>



   </td>
</tr>

<tr>
  
  <td>
      首页图标：
  </td>
  <td>
    
     
     <input type ="hidden" id="thumb_home_videosubject" name="HolidayIconsService[home_videosubject]" value="<?= $model->home_videosubject ?>" />     
     <a name='athumb' id='athumb_home_videosubject' data-name="home_videosubject" thumbid='0' href='#'><img id='imgthumb_home_videosubject' src="<? if($model->home_videosubject){echo $model->home_videosubject;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>

     <input type ="hidden" id="thumb_home_tweet" name="HolidayIconsService[home_tweet]" value="<?= $model->home_tweet ?>" />     
     <a name='athumb' id='athumb_home_tweet' data-name="home_tweet" thumbid='0' href='#'><img id='imgthumb_home_tweet' src="<? if($model->home_tweet){echo $model->home_tweet;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a> 


    <input type ="hidden" id="thumb_home_lecture" name="HolidayIconsService[home_lecture]" value="<?= $model->home_lecture ?>" />     
     <a name='athumb' id='athumb_home_lecture' data-name="home_lecture" thumbid='0' href='#'><img id='imgthumb_home_lecture' src="<? if($model->home_lecture){echo $model->home_lecture;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>

    <input type ="hidden" id="thumb_home_lesson" name="HolidayIconsService[home_lesson]" value="<?= $model->home_lesson ?>" />     
     <a name='athumb' id='athumb_home_lesson' data-name="home_lesson" thumbid='0' href='#'><img id='imgthumb_home_lesson' src="<? if($model->home_lesson){echo $model->home_lesson;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>
     </br></br>
    <input type ="hidden" id="thumb_home_live" name="HolidayIconsService[home_live]" value="<?= $model->home_live ?>" />     
     <a name='athumb' id='athumb_home_live' data-name="home_live" thumbid='0' href='#'><img id='imgthumb_home_live' src="<? if($model->home_live){echo $model->home_live;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>

    <input type ="hidden" id="thumb_home_book" name="HolidayIconsService[home_book]" value="<?= $model->home_book ?>" />     
     <a name='athumb' id='athumb_home_book' data-name="home_book" thumbid='0' href='#'><img id='imgthumb_home_book' src="<? if($model->home_book){echo $model->home_book;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>

    <input type ="hidden" id="thumb_home_qa" name="HolidayIconsService[home_qa]" value="<?= $model->home_qa ?>" />     
     <a name='athumb' id='athumb_home_qa' data-name="home_qa" thumbid='0' href='#'><img id='imgthumb_home_qa' src="<? if($model->home_qa){echo $model->home_qa;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>   

        <input type ="hidden" id="thumb_home_activity" name="HolidayIconsService[home_activity]" value="<?= $model->home_activity ?>" />     
     <a name='athumb' id='athumb_home_activity' data-name="home_activity" thumbid='0' href='#'><img id='imgthumb_home_activity' src="<? if($model->home_activity){echo $model->home_activity;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;border: 1px solid #cccccc; ' /></a>
  </td>
</tr>


 <tr>
       <td style="width: 200px;">底部字体颜色<span class='need'>*</span></td>
       <td style="width:600px;">
         <input class="inputclass1" name="HolidayIconsService[bottom_nav_color]" style="width:300px" type="text" value="<?= $model->bottom_nav_color ?>"  datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
         &nbsp;&nbsp;备注：RGB颜色 不带#
       </td>
     </tr>

 <tr>
   <td style="width: 200px;">描述</td>
   <td style="width:600px;">
     <input class="inputclass1" name="HolidayIconsService[desc]" style="width:300px" type="text" value="<?= $model->desc ?>"  />
   </td>
 </tr>
     
<?php if(isset($model->iconsid)){?>


<?php } ?>
    <tr>
      <td></td>
      <td>
       <div>
        <span class="normalbtn_l"><a id="asave" href="#">保存</a></span>
        <span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>
        
      </div>
    </td>
  </tr>
</tbody>
</table> 
<?php ActiveForm::end(); ?> 
</div>
<script>


//上传图片
    $("a[name=athumb]").click(function () {
                var content = '/holidayicons/thumbupload';
                var title = '编辑缩略图';
                content = content + '?url='+ encodeURI($('#thumb_'+$(this).data('name')).val())+'&name='+ $(this).data('name');
                layer.open({
                    type: 2,
                    title: title,
                    maxmin: false,
              shadeClose: false, //点击遮罩关闭层
              area : ['550px' , '300px'],
              content: content
          });
            return false;
        });
    //父窗口句柄
    var index = parent.layer.getFrameIndex(window.name);
    $(function () {

       
      //保存按钮
      $("#asave").click(function () {
       /* if(!$("#thumb_activity_img_url").val()){
          alert("请上传活动图");
          return ;
        }

      if(!$("#thumb_person_top_background_url").val()){
        alert("请上传活动图");
        return ;
      }*/


      for (var i = 1; i < 6; i++) {
        if(!$("#thumb_bottom_nav"+i+"_url").val()){
          alert("请上传第"+i+"张底部图！");
          return ;
        }
      }


      if(!$("#thumb_home_videosubject").val()){
          alert("请上传一招图标");
          return ;
      }
      if(!$("#thumb_home_tweet").val()){
          alert("请上传图库图标");
          return ;
      }

      if(!$("#thumb_home_lecture").val()){
          alert("请上传精讲图标");
          return ;
      }

      if(!$("#thumb_home_lesson").val()){
          alert("请上传跟着画图标");
          return ;
      }

      if(!$("#thumb_home_live").val()){
          alert("请上传直播课图标");
          return ;
      }

      if(!$("#thumb_home_book").val()){
          alert("请上传图书图标");
          return ;
      }

      if(!$("#thumb_home_qa").val()){
          alert("请上传名师问答图标");
          return ;
      }

      if(!$("#thumb_home_activity").val()){
          alert("请上传活动图标");
          return ;
      }
      
      $("form").submit();
      return false;
    });
      //关闭按钮,刷新父窗口
      $('#aclose').click(function(){
        //parent.location.reload(); 
        parent.layer.close(index);
      });            
    });
    //保存成功后自动关闭
    <? if($msg<>''){ ?>
      <?if(isset($isclose) && $isclose){ ?>
        layer.msg('<?= $msg ?>', {icon: 1});
        setTimeout(function (){
          parent.location.reload();
        }, 1000);
        <? } else{ ?>
         layer.msg('<?= $msg ?>', {icon: 2});
         <? } ?>
         <? } ?>
    //表单验证
    $("#roleform").Validform({
      tiptype:3,
    });

  </script>
  