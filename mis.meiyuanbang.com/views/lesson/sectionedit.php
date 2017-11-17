  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
      
  <div class="normaltable">
 <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
 <table style='width:100%;'>
 	<tbody>
 	<?php if(isset($model->sectionid)){?>
    <input type ="hidden" name='isedit' value='1' />
    <input type ="hidden" name="LessonSectionService[sectionid]" value="<?= $model->sectionid ?>" />
    <?php } ?>
    <input type ="hidden" name="LessonSectionService[lessonid]" value="<?= $model->lessonid ?>" />
    <tr>
    	<td style="width: 80px">节点标题<span class='need'>*</span></td>
        <td>
        	<input class="inputclass1" name="LessonSectionService[sectiontitle]" style="width:70%" type="text" value="<?= $model->sectiontitle ?>" datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td>节点说明<span class='need'>*</span></td>
        <td>
        	<textarea name="LessonSectionService[desc]" style="width:70%;height:60px;" datatype="*1-300" errormsg="摘要最多300个字符！" sucmsg="&nbsp;" ><?= $model->desc ?></textarea>
        </td>
    </tr>
    <tr>
    	<td>排序字段<span class='need'>*</span></td>
        <td>
        	<input class="inputclass1" name="LessonSectionService[listorder]" style="width:70px" type="text" value="<?= $model->listorder ?>" datatype="n" nullmsg="请输入排序字段！" sucmsg="&nbsp;"/>
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
  		//保存按钮
        $("#asave").click(function () {
            $("form").submit();
            return false;
        });
        //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	parent.layer.close(index);
        });
        //保存成功后自动关闭
        <? if(isset($msg) && $msg<>''){ ?>
        	<?if(isset($isclose) && $isclose){ ?>
        		layer.msg('<?= $msg ?>', {icon: 1});
	        	setTimeout(function (){
	        		parent.location.href='/lesson/dashboard?lessonid=<?= $lessonid ?>';
	           }, 1000);
	      	<? } else{ ?>
	      		layer.msg('<?= $msg ?>', {icon: 2});
	      	<? } ?>
        <? } ?>
		//表单验证
        $("#cmsform").Validform({
    		tiptype:3,
    	});	
      //选择主类型
        $("#maintype").change(function () {
            var objmain = $('#maintype')[0];
            var objsub = $('#subtype')[0];
            var index = objmain.selectedIndex; //序号，取当前选中选项的序号
            var val = objmain.options[index].value;
            //先清空分类型，然后添加全部选项
            objsub.options.length = 0;
            objsub.add(new Option("选择分类型", ""));
            //首先清空经纪人选项，并且加全部选项
            if (val != '') {
                $.ajax({
                    type: "post",//使用get方法访问后台
                    dataType: "json",//返回json格式的数据
                    url: "/lesson/ajaxsubtype",//要访问的后台地址
                    data: "maintypeid=" + val,//要发送的数据                    
                    success: function (data) {
                        if (data.errno == 0) {
                            for (i = 0; i < data.data.length ; i++) {
                            	objsub.add(new Option(data.data[i].subtypename, data.data[i].subtypeid));
                            }
                        }
                        else {
                          layer.msg('访问错误', {icon: 2});
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                         layer.msg('访问错误', {icon: 2});
                    }
                });
            }
        });


          //选择节点语音
    $("#selbtn").click(function () {
            var content = '/sound/sel';
            var title = '选择音频';
            content = content + '?soundid='+ encodeURI($("#soundid").val())+"&sound_type=2";
            var search =layer.open({
                type: 2,
                title: title,
                maxmin: true,
                area : ['700px' , '600px'],
                content: content
              });
            layer.full(search);
      });
    </script>