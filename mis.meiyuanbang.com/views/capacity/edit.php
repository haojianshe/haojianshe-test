  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
use common\service\dict\CapacityModelDictDataService;
use common\service\dict\BookDictDataService;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" src="/static/js/jquery.min.js?v=20151124"></script>
 
  <!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/static/css/buttons.css">
  <style>
      #ttt td{
          border:solid 1px #ffffff;
      }
  </style>
  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>    
   <table style="width:100%">
    <tbody >
      <tr>
       <td style="width:5.3%">素材编号</td>
       <td>
         <input type ="hidden" name='isedit' value='1' />
         <input type ="hidden" class="inputclass1" name="CapacityModelMaterialService[materialid]" style="width:300px" type="text" value="<?= $model->materialid ?>" />
         <?= $model->materialid ?>
       </td>
     </tr>
       <tr>
            <td>用户名</td>
             <td>
           <select name="CapacityModelMaterialService[uid]" id="uid">
           <?  foreach ($users as $key => $value) {?>
              <option value="<?php echo  $value['uid']?>" key="<?= $value['uid']?>" <?php if($model->uid==$value['uid']){ echo 'selected=selected';}?> > <?= $value['sname']?></option>
            <?}?>
           </select>
           </td>
        </tr>
        <tr>
             <td>内容</td>
            <td>
                <textarea name="CapacityModelMaterialService[content]" style="width:99%;height:100px;" datatype="*1-120" nullmsg="请输入内容，最多120个字！" id="content" sucmsg="&nbsp;"><?= $model->content ?></textarea>
      <span class="Validform_checktip"></span></td>
        </tr>

    <tr>
       <td>图片<span class='need'>*</span></td>
       <td>
           <input type ="hidden" id="thumb" name="CapacityModelMaterialService[picurl]" value="<?= json_decode($model->picurl)->n->url ?>" />       
           <a name='athumb' id='athumb' thumbid='0' href='#'><img id='imgthumb' src="<? if($model->picurl){echo json_decode($model->picurl)->n->url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' /></a>
       </td>
    </tr>
      <tr>
          <td>预览图片</td>
        <td style="width:350px;">
            <a id="example2"  rel="group" href="<?php echo json_decode($model->picurl)->n->url; ?>">
                <img style=" width:350px; height: auto;" name="prew_resource" src="<?php
                if ($model->picurl) {
                    echo json_decode($model->picurl)->n->url;
                } else
                    echo '/ueditor/dialogs/image/images/image.png';
                ?>" />
            </a>
        </td>
      </tr>
                
                     <tr>
       <td >主分类<span class='need'>*</span></td>
       <td>
          <select name="CapacityModelMaterialService[f_catalog_id]" id="f_catalog_id">
         <!--  <option value="" selected="">主分类</option> -->
          <? foreach (json_decode($classtype,true)['maintype'] as $key => $value) { ?>           
                <option value="<?= $key ?>"
                    <?if(!empty($model->f_catalog_id) && $model->f_catalog_id==$key){ ?> selected="selected" <?}?>  ><?= $value ?>
                  </option> 
          <?}?>
          </select>         
        </td>
     </tr>   

        

     <tr>
      <td>子分类<span class='need'>*</span></td>
      <td>
         <select name="CapacityModelMaterialService[s_catalog_id]" id="s_catalog_id">
              <?if(empty($model->f_catalog_id)){ $model->f_catalog_id=1; }?> 
                <?foreach (json_decode($classtype,true)['subtype'][$model->f_catalog_id] as $key => $value) {?> 
                    <option value="<?= $key ?>" 
                      <?if( !empty($model->s_catalog_id) && $key==$model->s_catalog_id){?>
                       selected="selected"
                      <?}?>
                    ><?= $value?></option>
                <?}?>
          </select>
      </td>
    </tr>
    
     <tr>
      <td>能力分类<span class='need'>*</span></td>
      <td>
         <select name="CapacityModelMaterialService[item_id]" id="item_id">
          <?if( empty($model->f_catalog_id)){  $model->f_catalog_id=1;}?>
            <? foreach (json_decode($classtype,true)['captype'][$model->f_catalog_id] as $key => $value) { ?>
              <option value="<?= $value['itemid'] ?>" 
              <?if(!empty($model->item_id) && $value['itemid']==$model->item_id){
               ?>
               selected="selected"
              <?}?>
              ><?= $value['itemname']?>
              </option>
            <?}?>
          </select>
      </td>
    </tr>
    <tr>
            <?php
            echo "<table id=\"dividid\" >".$data['models']."  </table>";
            ?> 
    </tr>
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
<input type="hidden" value="<?= $model->materialid ?>" id="materialid"/>
</table> 
</div>
<?php ActiveForm::end(); ?> 
  <!-- 图片浏览 开始-->
<script language="javascript" type="text/javascript">
    $("a#example2").fancybox({
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
    
       $("#s_catalog_id").change(function () {
        $("#dividid tr").remove();
        var s_catalog_id = $(this).val();
         
        var materialid = $("#materialid").val();
       
        var url = '/capacity/select_tag';
        var data = {
            s_catalog_id: s_catalog_id,
            materialid: materialid,
            type :1
        }
        $.post(url, data, function (m) {
            $("#dividid").append(m.models);
        }, 'json');
    });
</script>
<script>
  		//父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
      //保存按钮
      $("#asave").click(function () {
          $("form").submit();
          return false;
        });
      //关闭按钮,刷新父窗口
      $('#aclose').click(function(){
      	//parent.location.reload(); 
      	parent.layer.close(index);
      });
       
      //保存成功后自动关闭
      <?if(isset($isclose) && $isclose){ ?>
      	parent.layer.msg('<?= $msg ?>');
      	setTimeout(function (){
      		parent.location.reload();
       }, 1000);
      <? } ?>

      //分类点击
      var classtype='<?=$classtype?>';
      $("#f_catalog_id").change(function() {
           $("#dividid tr").remove();
          var fcid=$("#f_catalog_id  option:selected").val();
          var subtypes=$.parseJSON(classtype).subtype[fcid];
          var captypes=$.parseJSON(classtype).captype[fcid];
          var subhtml='';
          var caphtml='';
           var string = '<option>请选择</option>';
          $.each(subtypes, function(i,val){ 
            subhtml=subhtml+'<option value="'+i+'" >'+val+ "</>";  
          });
          $.each(captypes, function(i,val){ 
            caphtml=caphtml+'<option value="'+val['itemid']+'" >'+val['itemname']+ "</>" ;  
          });
          $("#s_catalog_id").html(string+subhtml);
          $("#item_id").html(caphtml);
      });


    //图片上传
    $("a[name=athumb]").click(function () {
                var content = '/capacity/thumbupload';
                var title = '编辑图片';
                content = content + '?url='+ encodeURI($('#thumb').val());
                layer.open({
                    type: 2,
                    title: title,
                    maxmin: false,
              shadeClose: false, //点击遮罩关闭层
              area : ['600px' , '400px'],
              content: content
          });
            return false;
      });
    
</script>
        