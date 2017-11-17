  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
 <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>


    <link href="/static/js/uploadify/uploadify.css" rel="stylesheet" type="text/css" />  
  <script src="/static/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>  

  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>    
   <table>
    <tbody>
     <tr>
         <td width="80">选择用户</td>
           <td>
             <select name="CommentService[uid]" id="uid">
             <?  foreach ($users as $key => $value) {?>
                <option value="<?= $value['uid']?>" key="<?= $value['uid']?>" > <?= $value['sname']?></option>
              <?}?>
             </select>
             </td>
      </tr> 


     <tr>
      <td>内容</td>
      <td>
        <textarea name="CommentService[content]" style="width:300px;height:120px;" value="<?= $model->content ?>"><?= $model->content ?></textarea>
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

</tbody>
</table> 
<?php ActiveForm::end(); ?> 
</div>
<script>
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
          
</script>
