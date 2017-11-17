<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\service\dict\SubjectDictService;
  use common\service\dict\BookDictDataService;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>

<!-- 时间选择框样式 -->
<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css"/>
<!-- 时间选择框js -->
<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>    
   <table style="width:100%;">
    <tbody>
      <tr >
       <td style="width: 80px">专题编号</td>
       <td>
       <?php if(isset($model->subjectid )){?>
         <input type ="hidden" name='isedit' value='1' />
         <input type ="hidden" class="inputclass1" name="MatreialSubjectService[subjectid]" style="width:300px" type="text" value="<?= $model->subjectid ?>" />
          <?php } ?>
         <?= $model->subjectid ?>
       </td>
     </tr>
     <tr>
         <td>
             专题分类
         </td>
         <td>
                         <?php
                         $array = [];
                         foreach(SubjectDictService::getSubjectMainType()['s_catalog'] as $key=>$val){
                             $array[$val['id']] = $val['name'];
                         }
                          echo BookDictDataService::createMenuList('MatreialSubjectService[subject_typeid]',$array , $model->subject_typeid, 'f_catalog_id','','','','','<option value="0" >请选择</option>');
                         ?>
         </td>
         
         
     </tr>
     
     <tr>
       <td >专题标题<span class="need">*</span></td>
       <td>
         <input  class="inputclass1" name="MatreialSubjectService[title]" style="width:500px" type="text"  value="<?= $model->title ?>" datatype="*1-40" errormsg="最多40个字符！" sucmsg="&nbsp;" />
       </td>
     </tr> 
     <tr>
       <td >创建时间<span class="need">*</span></td>
       <td>
       <input type="text" name="MatreialSubjectService[ctime]" id="ctime" value="<?if($model->ctime >0){echo date('Y-m-d H:i:s',$model->ctime);} ?>" class="inputclass1" readonly="readonly" style="width:180px" />&nbsp;
            <script type="text/javascript">
                    Calendar.setup({
                       weekNumbers: true,
                       inputField : "ctime",
                       trigger    : "ctime",
                       dateFormat: "%Y-%m-%d %H:%M:%S",
                       showTime: true,
                       minuteStep: 1,
                       onSelect   : function() {this.hide();}
                   });
	    </script>
      </td>
     </tr> 
     <tr>            
           <td>
               专题描述
            </td>
            <td>
                <textarea name="MatreialSubjectService[material_desc]" style="width: 500px; height: 120px; margin: 0px;" value="<?= $model->material_desc ?>"><?= $model->material_desc ?></textarea>
            </td>
            
        </tr>  
    <tr>
       <td >详情图<span class="need">*</span></td>
       <td>
       <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
         <input type ="hidden" class="inputclass1" id="rids" name="MatreialSubjectService[rids]" style="width:150px" type="text" value="<?= $model->rids ?>" />
       </td>
     </tr> 
     <tr>
       <td></td>
       <td>
         <span class="imglist">
      <?if($imglist){
        foreach ($imglist as $key => $value) { ?>
          <a id="example<?= $value['rid']?>" onClick="del(<?= $value['rid']?>)"  >
            <img class="imginfo" data-id="<?= $value['rid']?>" style="height:80px;width:80px;padding-left:3px;padding-top:3px;" name="prew_resource" src="<?= json_decode($value['img'])->t->url ?>" />
          </a>
      <?}}?>
     </span>
       </td>
     </tr>
        <tr>
           <td>封面图<span class='need'>*</span></td>
           <td>
               <input type ="hidden" id="thumb" name="MatreialSubjectService[picurl]" value="<? if($model->picurl){echo json_decode($model['picurl'])->n->url;}?>" />       
               <a name='athumb' id='athumb' thumbid='0' href='#'><img id='imgthumb' src="<? if($model->picurl){echo json_decode($model['picurl'])->n->url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:80px;width:80px;padding-left:3px;' /></a>
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
      function del(rid){
        var arrList=$("#rids").val().split(",");
        for (var i=0;i<arrList.length; i++) {
          if(arrList[i]==rid){
            arrList.splice(i,1);
          }
        }        
        //arrList.splice(jQuery.inArray(rid,arrList),1); 
        $("#rids").val(arrList.join(","));
        $("#example"+rid).html("");
        console.log(arrList.join(","));
      }

      $("a[name=athumb]").click(function () {
              var content = '/activity/thumbupload';
              var title = '编辑缩略图';
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


    		//父窗口句柄
    		var index = parent.layer.getFrameIndex(window.name); 

//点击缩略图事件
        $("#selbtn").click(function () {
                var content = '/material/sel';
                var title = '选择批改老师';
                content = content + '?rids='+ encodeURI($("[name='MatreialSubjectService[rids]']").val());
                var search =layer.open({
                    type: 2,
                    title: title,
                    maxmin: true,
                    area : ['700px' , '600px'],
                    content: content
                  });
                layer.full(search);
          });

            //保存按钮
          $("#asave").click(function () {

             
              //检查缩略图
              t = $('#thumb').val();
              if(t == ''){
                layer.msg('老师头像必须上传', {icon: 2});
                  return false;
              }     
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
          //表单验证
            $("#cmsform").Validform({
                tiptype:3,
            });
                
</script>
