  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
  use mis\service\ResourceService;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <style>
     
      #ttt td{
          border:solid 1px #ffffff;
      }
      
  </style>
  <div class="normaltable" style="position: relative;">
    <div style="width: 400px; min-height: 200px; position: fixed; right: 10px; top: 0; border:1px solid #000">
        <?php
         if($model->resource_id){
             $rid =  explode(',', $model->resource_id)[0];
             $res =  json_decode(ResourceService::findOne(['rid'=>$rid])->img,1)['n']['url'];
         }
        ?>
      <img style=" width: 400px;" src="<?php echo $res;?>">
    </div>
   <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>    
   <table  width="69.6%">
    <tbody>
      <?php if(isset($model->tid)){?>
      <tr>
       <td style="width:7.6%">帖子编号</td>
       <td>
         <input type ="hidden" name='isedit' value='1' />
         <input type ="hidden" class="inputclass1" name="TweetService[tid]" style="width:300px" type="text" value="<?= $model->tid ?>" />
         <?= $model->tid ?>
       </td>
           <input type="hidden" id="materialid" value="<?= $model->tid ?>" />
     </tr>
     
     <tr>
       <td >帖子标题</td>
       <td>
         <input class="inputclass1" name="TweetService[title]" style="width:478px" type="text" value="<?= $model->title ?>" />
       </td>
     </tr>   

     <tr>
      <td>内容</td>
      <td>
        <textarea name="TweetService[content]" style="width: 478px; height: 120px; margin: 0px;" value="<?= $model->content ?>"><?= $model->content ?></textarea>
      </td>
    </tr>
    <tr>
      <td>是否素材</td>
      <td>
        <input type="radio" name="TweetService[type]" value="1" <?if($model->type == '1'){?>checked<? }?> />&nbsp;是 &nbsp;&nbsp;
        <input type="radio" name="TweetService[type]" value="2" <?if ($model->type == '2'){?>checked<? } ?> />&nbsp;否
      </td>
    </tr>
       <tr>
       <td >推荐考点id</td>
       <td>
         <input class="inputclass1" name="TweetService[lessonid]" style="width:478px" type="text" value="<?= $model->lessonid ?>" />
       </td>
     </tr>  
    <tr>
        

      <td width="80">一级分类</td>
      <td>
       <select name="TweetService[f_catalog]" id="f_catalog">
         <?if(empty($model->f_catalog)){ ?>
         <option value="" selected="">一级分类</option>
         <?}?>
         <? foreach ($catalog['imgmgr_level_1'] as $key => $value) {?>        
         <option value="<?=$value?>" key="<?=$key?>" <?if ($value==$model->f_catalog) {?>selected<?} ?>>
          <?=$value?>
        </option>
        <?}?>

      </select>
      <select name="TweetService[s_catalog]" id="s_catalog">
        <?if(empty($model->s_catalog)){ ?>
        <option value="" selected="">二级分类</option>
        <?}else{?>
        <option value="" selected=""><?= $model->s_catalog ?></option>
        <?}?>
      </select>
    </td>
  </tr>
  
  <tr width="69.7%">
            <?php
            echo '<table id="dividid" ><tbody>'.$data['models']."</tbody></table>";
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
<?php } ?>
</tbody>
</table> 
<?php ActiveForm::end(); ?> 
</div>
     <?php
   # print_r($catalog);
    ?>
<script>
    
 
//           $("#f_catalog").change(function() {
//                $("#dividid tr").remove();
//              $("#tags select option").remove(); 
//              $("#tags select ").prepend("<option value=''>请选择</option>");
//            });
    		//父窗口句柄
    	   var index = parent.layer.getFrameIndex(window.name);
            $(function () {  
                
                 $("#f_catalog").change(function () {
                     $("#dividid tr").remove();
                    var f_catalog = $("#f_catalog  option:selected").attr("key");
                    var url = '/tweet/select_menu';
                    var data = {
                        f_catalog_id : f_catalog
                    }
                 $.post(url,data,function(m){
                      $("#s_catalog option").remove();
                     $("#s_catalog").append(m);
                 },'json');
                });
         
//              $("#f_catalog").change(function() {
//              $("#dividid tr").remove();
//               var key=$("#f_catalog  option:selected").attr("key");
//               var catalog_json=<?//= json_encode($catalog)?>;
//               var s_catalog="<?//= $model->s_catalog?>";
//               var content='';
//               var s_catalogs=catalog_json.imgmgr_level_2[key];
//               var string = '<option>请选择</option>';
//               for(var item in s_catalogs) {
//                      if(s_catalog==s_catalogs){
//                        content+="<option selected value="+s_catalogs[item]+">"+s_catalogs[item]+"</option>";
//                      }else{
//                        content+="<option value="+s_catalogs[item]+">"+s_catalogs[item]+"</option>";               
//                      }
//                  $("#s_catalog").html(string+content);
//              }
//          
//            });   
            
        $("#s_catalog").change(function(){
              var s_catalogSid = $('#s_catalogSid').val();
              var s_catalogSids = $(this).val();
              if(s_catalogSid !=s_catalogSids){
                  $('#tagsSid').val('');
              }
        });
        
        
    $("#s_catalog").change(function () {
        $("#dividid tr").remove();
        var s_catalog_id = $(this).val();
        var materialid = $("#materialid").val();
        var f_catalog = $("#f_catalog").val();
        var url = '/capacity/select_tag';
        var data = {
            s_catalog_id: s_catalog_id,
            f_catalog: f_catalog,
            materialid: materialid,
            type :2
        }
        $.post(url, data, function (m) {
            $("#dividid").append(m.models);
        }, 'json');
    });
       
       /*
        $("#s_catalog").click(function() {
                if($('#tagsSid').val()==1){
                    return false;
                }
              $.ajax({
                   type: "post",
                   url: "/tweet/gettags",
                   data: "s_catalog="+$("#s_catalog  option:selected").attr("value")+"&f_catalog="+$("#f_catalog  option:selected").attr("value"),
                   dataType: "json",
                   success: function(data){
                              $('#tags').empty();   //清空tags里面的所有内容
                              var html = ''; 
                              if(data.errno==0){
                                for (value in data.data){
                                    html +='<select  name="tags" >';
                                    html +='<option  selected>请选择</option>';
                                          //console.log(data.data[value]['tag']);
                                      for (value1 in data.data[value]['tag']){
                                          html +='<option value='+data.data[value]['tag'][value1]+'>'+data.data[value]['tag'][value1]+'</option>';
                                      }
                                          html +="</select>";
                                    }
                                $('#tags').html(html);
                                 $("#tags_value").val('');
                                     $("[name='tags']").click(function() {
                                      var tagsval='';
                                      $("select[name='tags']").each(function(){     
                                      if($(this).val()!='请选择'){
                                           tagsval = tagsval + $(this).val()+",";
                                      }
                                       });
                                      tagsval=tagsval.substring(0,tagsval.length-1);
                                      $("#tags_value").val(tagsval);
                                    });
                              }else{
                                       //alert('huoqushibai');
                              }

                      }
                });
            });
            */
            //保存按钮
          $("#asave").click(function () {
              var s_catalog = $("#s_catalog option:selected").text();
              if(s_catalog !='二级分类'){
              $("#s_catalog").html('<option value="'+s_catalog+'"></option>');
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
          <?if(isset($isclose) && $isclose){ ?>
          	parent.layer.msg('<?= $msg ?>');
          	setTimeout(function (){
          		parent.location.reload();
           }, 1000);
            <? } ?>
</script>
        