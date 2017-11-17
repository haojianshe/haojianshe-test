  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>    
   <table>
    <tbody>
      <?php if(isset($model->tid)){?>
      <tr>
       <td style="width: 80px">帖子编号</td>
       <td>
         <input type ="hidden" name='isedit' value='1' />
         <input class="inputclass1" name="TweetService[tid]" style="width:50px" type="text" value="<?= $model->tid ?>" />
       </td>
     </tr>
     
     <tr>
       <td >帖子标题</td>
       <td>
         <input class="inputclass1" name="TweetService[title]" style="width:300px" type="text" value="<?= $model->title ?>" />
       </td>
     </tr>
  <!--   <tr>
    <td>分类一级</td>
      <td>
        <input class="inputclass1" name="TweetService[f_catalog]" style="width:300px" type="text" value="<?= $model->f_catalog ?>" />
      </td>
  </tr>
  
  <tr>
    <td>分类二级</td>
      <td>
        <input class="inputclass1" name="TweetService[s_catalog]" style="width:300px" type="text" value="<?= $model->s_catalog ?>" />
      </td>
    </tr> -->
    
    <tr>
      <td>内容</td>
      <td>
        <textarea name="TweetService[content]" style="width:300px;height:300px;" value="<?= $model->content ?>"><?= $model->content ?></textarea>
        <!--   <input class="inputclass1" name="TweetService[content]" style="width:300px" type="text" value="<?= $model->content ?>" /> -->
      </td>
    </tr>
    <tr>
      <td width="80">是否素材</td>
      <td>
        <input type="radio" name="TweetService[type]" value="1" <?if($model->type == '1'){?>checked<? }?> />&nbsp;是 &nbsp;&nbsp;
        <input type="radio" name="TweetService[type]" value="2" <?if ($model->type == '2'){?>checked<? } ?> />&nbsp;否
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
<script>
  		//父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
      $(function () {
        
        $("#f_catalog").click(function() {
         var key=$("#f_catalog  option:selected").attr("key");
         var catalog_json=<?= json_encode($catalog)?>;
         var s_catalog="<?= $model->s_catalog?>";
         var content='';
         var s_catalogs=catalog_json.imgmgr_level_2[key];
         for(var item in s_catalogs) {
          if(s_catalog==s_catalogs){
            content+="<option selected value="+s_catalogs[item]+">"+s_catalogs[item]+"</option>";
          }else{
            content+="<option value="+s_catalogs[item]+">"+s_catalogs[item]+"</option>";               
          }
          $("#s_catalog").html(content);
        }
        
      });

        
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
          });
        //保存成功后自动关闭
        <?if(isset($isclose) && $isclose){ ?>
        	parent.layer.msg('<?= $msg ?>');
        	setTimeout(function (){
        		parent.location.reload();
         }, 1000);
          <? } ?>


        </script>
        