  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'roleform']); ?>    
   <table>
    <tbody>
        <?php if(isset($model->tagid)){?>
        <tr>
            <td style="width: 100px">编号</td>
            <td>
                <input type ="hidden" name='isedit' value='1' />
                <input class="inputclass1" name="TagsService[tagid]" style="width:50px" type="text" value="<?= $model->tagid ?>" readonly='true' />
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td style="width: 100px">名称</td>
            <td style="width: 400px">
                <input class="inputclass1" name="TagsService[tag_name]" style="width:300px" type="text" value="<?= $model->tag_name ?>"  datatype="*1-200" nullmsg="名称不能为空" sucmsg="&nbsp;"/>
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
        //父窗口句柄
        var index = parent.layer.getFrameIndex(window.name);
        $(function () {
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
