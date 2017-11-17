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
      <?php if(isset($model->videoid)){?>
      <tr>
       <td style="width: 100px;">编号</td>
       <td style="width:400px;">
         <input type ="hidden" name='isedit' value='1' />
         <input class="inputclass1" name="VideoResourceService[videoid]" style="width:50px" type="text" value="<?= $model->videoid ?>" readonly='true' />
       </td>
     </tr>
     <?php } ?>

     <tr> 
      <td width="80">一级分类</td>
      <td>
       <select name="VideoResourceService[maintype]" id="maintype">
         <?
         //if(empty($model->maintype)){ ?>
         <option value="" key="">一级分类</option>
         <?//}?>
         <? foreach ($catalog['imgmgr_level_1'] as $key => $value) {?>            
         <option value="<?=$key?>" key="<?=$key?>" <?if ($key==$model->maintype) {?>selected<?} ?>>
          <?=$value?>
        </option>
        <?}?>

      </select>
      <select name="VideoResourceService[subtype]" id="subtype">
        <?
         $subtype_name='';
        if($model->maintype){
            $subtype_name=DictdataService::getTweetSubTypeById($model->maintype,$model->subtype);
        }
        
        if(empty($subtype_name)){ ?>
        <option value="" selected="">二级分类</option>
        <?}else{?>
        <option value="<?= $model->subtype?>" selected><?= $subtype_name ?></option>
        <?}?>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="80">视频类型</td>
      <td>
      <select name="VideoResourceService[video_type]" id="video_type">
      <option value="" key="">通用</option>
      <option value="1" key="" <?if(intval($model->video_type)==1){echo 'selected';}?> >直播</option>
      <option value="2" key="" <?if(intval($model->video_type)==2){echo 'selected';}?> >课程</option>     
      </td>

  </tr>
<tr>
   <td>缩略图<span class='need'>*</span></td>
   <td>
       <input type ="hidden" id="thumb" name="VideoResourceService[coverpic]" value="<?= $model->coverpic ?>" />      <input type ="hidden" id="filename" name="VideoResourceService[filename]" value="<?= $model->filename ?>" />     
       <a name='athumb' id='athumb' thumbid='0' href='#'><img id='imgthumb' src="<? if($model->coverpic){echo $model->coverpic;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' /></a>
   </td>
</tr>

     <tr>
       <td style="width: 100px;">描述</td>
       <td style="width:600px;">
         <input class="inputclass1" name="VideoResourceService[desc]" style="width:300px" type="text" value="<?= $model->desc ?>" />
       </td>
     </tr>
     
<?php if(isset($model->videoid)){?>
<input type ="hidden" id="video_length" name="VideoResourceService[video_length]" value="<?= $model->video_length ?>" />
<input type ="hidden" id="video_size" name="VideoResourceService[video_size]" value="<?= $model->video_size ?>" />
<tr>
   <td>视频<span class='need'>*</span></td>
   <td style="width: 600px;">
        <input type ="hidden" id="video" name="VideoResourceService[sourceurl]" value="<?= $model->sourceurl ?>" />
        <? if(empty($model->sourceurl)){?>
          <a name='avideo' id='avideo' videoid='0' href='#'>
            <img id='imgthumb' src="/ueditor/dialogs/image/images/image.png" style='padding-left:15px;height:100px;' />
          </a>
        <?}else{?>
          源文件地址：</br><?=$model->sourceurl?></br>
          App视频文件地址：</br><?=$model->m3u8url?>
        <?}?>
   </td>
</tr>

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

//视频上传
      $("a[name=avideo]").click(function () {
              var content = '/video/videoupload';
              var title = '上传视频';
              content = content + '?url='+ encodeURI($('#video').val());
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['670px' , '250px'],
            content: content
        });
              return false;
          });

//点击缩略图事件
      $("a[name=athumb]").click(function () {
              var content = '/video/thumbupload';
              var title = '编辑缩略图';
              content = content + '?url='+ encodeURI($('#thumb').val());
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['400px' , '300px'],
            content: content
        });
              return false;
          });
    //父窗口句柄
    var index = parent.layer.getFrameIndex(window.name);
    $("#maintype").click(function() {
           var key=$("#maintype  option:selected").attr("key");
           var catalog_json=<?= json_encode($catalog)?>;
           var subtype="<?= $model->subtype?>";
           var content='';
           content+='<option value="" selected >二级分类</option>';
             if(key){
                var subtypes=catalog_json.imgmgr_level_2[key];
                for(var item in subtypes) {
                      if(subtype==subtypes){
                        content+="<option selected value="+item+">"+subtypes[item]+"</option>";
                      }else{
                        content+="<option value="+item+">"+subtypes[item]+"</option>";               
                      }
                }
            } 
             $("#subtype").html(content);
        }); 
    $(function () {

       
      //保存按钮
      $("#asave").click(function () {
        if(!$("#thumb").val()){
          alert("请上传封面图");
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
         layer.msg('<?= $msg ?>', {icon: 1});
         <? } ?>
         <? } ?>
    //表单验证
    $("#roleform").Validform({
      tiptype:3,
    });

  </script>
  