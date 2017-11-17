  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
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
 <table style='width:100%;'>
 	<tbody>
 	<?php if(isset($model['advrecid'])){?>
    <input type ="hidden" name='isedit' value='1' />
    <input type ="hidden" name="AdvRecordService[advrecid]" value="<?= $model['advrecid'] ?>" />
    <?php } ?>
    <style type="text/css">
      .pos_type{
        background-color: #868686;
        padding: 2px;
        color:white;
        text-align: center;
        font-size: 16px;
      }
      .list{
       border-bottom: 1px solid #b9b9b9;
       background-color: #dadada;
       font-size: 14px;
      }
      .list_item{
         border-left: 1px solid #b9b9b9;
         border-bottom: 1px solid #b9b9b9;
         border-right: 1px solid #b9b9b9;
         padding:2px;
      }
      .detail_item{
         border: 1px solid #d0d0d0;
       background-color: #fff1f1;
      }
    </style>
    
    <tr>
    	<td style="width: 80px">类型<span class='need'>*</span></td>
        <td>
        	
            <ul style="background-color: white;">
	            <? foreach ($catalog as $typeitem) { ?>
	             <li class="pos_type" data-value="<?= $typeitem['typeid'] ?>"><?= $typeitem['typename'] ?></li>
	             <ul>
		            <? switch ($typeitem['typeid']) {
		            	case 1:
		            		foreach ($typeitem['list'] as $key1 => $value1) {
		            			?><li class='list' data-value="<?= $value1['id'] ?>"><?= $value1['name'] ?></li><ul class="list_item"><?
		            			foreach ( $value1['catalog'] as $key2 => $value2) {
		            				?>
		            				<input  id="<?=$typeitem['typeid'].'-'.$value1['id'].'-'.$key2?>" type="checkbox" name="AdvRecordService[list_catalog][]" value="<?=$typeitem['typeid']?>-<?=$value1['id']?>-<?=$key2 ?>" ><?= $value2?>

		            					<select   id="sortid" name="AdvRecordService[sortid][<?=$typeitem['typeid']?>-<?=$value1['id']?>-<?=$key2 ?>][]"  >
		            						<option value="">请选择</option>
							                <?for ($i=1; $i <=6 ; $i++) { 
							                	 ?><option id="<?= $typeitem['typeid'].'-'.$value1['id'].'-'.$key2.'-'.$i?>" " value="<?= $i ?>"><?= $i ?></option>
                                <script type="text/javascript">
                                 <? if(in_array($typeitem['typeid'].'-'.$value1['id'].'-'.$key2.'-'.$i, $has_advarr)){echo '
                                    $("#'.$typeitem['typeid'].'-'.$value1['id'].'-'.$key2.'-'.$i.'").attr("selected","selected");
                                    $("#'.$typeitem['typeid'].'-'.$value1['id'].'-'.$key2.'").attr("checked","checked");
                                 ';}?> 
                                </script>


							                	 <?
							                }?>
					               
					            		</select>
		            				<?
		            			}
		            			?></ul><?

		            		}
		            		break;
		            	case 2:
		            		foreach ($typeitem['details'] as $key1 => $value1) {
		            			?><li class="list" data-value="<?= $value1['id'] ?>"><?= $value1['name'] ?></li><?
		            			foreach ( $value1['catalog'] as $key2 => $value2) {
		            				?><li class="detail_item" data-value="<?= $value2['id'] ?>"><?= $value2['name'] ?></li>
                        <div><input class="all_check"  type="checkbox"  >全选
                        <?
		            					foreach ( $value2['scatalogs'] as $key3 => $value3) {
												?>
		            							<input <? if(in_array($typeitem['typeid'].'-'.$value1['id'].'-'.$value2['id'].'-'.$key3, $has_advarr)){echo 'checked="checked"';}?>  type="checkbox" name="AdvRecordService[detail_catalog][]" value="<?=$typeitem['typeid']?>-<?=$value1['id']?>-<?=$value2['id']?>-<?=$key3 ?>" ><?= $value3?>
		            							<?
		            					}?>
                          </div>
                          <?
		            			}
		            		}
		            	break;
		            	default:
		            		break;
		            }?>
	             </ul>

	             <?}?>
             </ul>
        </td>
    </tr>

 <tr>
    	<td>投放区域<span class='need'>*</span></td>
        <td>
        <input class="all_check"  type="checkbox"  >全选
        	<?  foreach ($province as $key => $value) {
        		
        		?>
				<input <? if (in_array($value['provinceid'], $has_provinceids_arr)) { echo 'checked="checked"'; }?>  type="checkbox" name="AdvRecordService[provice][]" value="<?=$value['provinceid']?>" ><?= $value['provincename']?>
				<?
        	} ?>
        </td>
    </tr>
   
<tr>
   <td style="width: 150px">生效时段<span class='need'>*</span></td>
   <td>
       <input type="text" name="AdvRecordService[stime]" id="stime" value="<?if($model['stime']){echo date('Y-m-d H:i',$model['stime']);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
       <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "stime",
           trigger    : "stime",
           dateFormat: "%Y-%m-%d 00:00",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
    至
    <input type="text" name="AdvRecordService[etime]" id="etime" value="<? if($model['etime']){echo date('Y-m-d H:i',$model['etime']);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
    <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "etime",
           trigger    : "etime",
           dateFormat: "%Y-%m-%d 00:00",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
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


<script type="text/javascript">
  //全选按钮
  $(".all_check").change(function(){
    if(this.checked){
      $(this).siblings().prop("checked", true);;
    }else{
      $(this).siblings().removeAttr("checked");
    }
  });
</script>

  <script>
  		//父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
  		 //上传图片
    	$("a[name=athumb]").click(function () {
                var content = '/adv/thumbupload';
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
        //保存按钮
        $("#asave").click(function () {
            //检查缩略图
            t = $('#thumb').val();
            if(t == ''){
            	layer.msg('缩略图必须上传', {icon: 2});
          		return false;
            }
            var stime=new Date($("#stime").val()).getTime();
            var etime=new Date($("#etime").val()).getTime();
            //当前0点时间
            var today = new Date();
            today.setHours(0);
            today.setMinutes(0);
            today.setSeconds(0);
            today.setMilliseconds(0);
            var timestamp = Date.parse(today);

            if(stime>etime){
              layer.msg('开始时间必须大于结束时间！', {icon: 2});
               return false;
            }
           /* if(stime<timestamp){
                layer.msg('投放时间必须大于当前时间！', {icon: 2});
               return false;
            }*/
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
	        		parent.location.reload();
	           }, 1000);
	      	<? } else{ ?>
	      		layer.alert('<?= $msg ?>', {icon: 2});
	      	<? } ?>
        <? } ?>
		//表单验证
        $("#cmsform").Validform({
    		tiptype:3,
    	});	      
    </script>