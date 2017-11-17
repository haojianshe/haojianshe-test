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
            <?php if(isset($model->groupbuyid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="GroupbuyService[groupbuyid]" value="<?= $model->groupbuyid ?>" />
            <?php } ?>
          
            <tr>
            	<td style="width: 80px">课程id<span class='need'>*</span></td>
                <td>
            <input class="inputclass1" name="GroupbuyService[courseid]" style="width:10%" type="text" value="<?= $model->courseid ?>" datatype="/^-?[1-9]\d*$/" nullmsg="不能为空"
                   errormsg="必须为数字"  sucmsg="&nbsp;"/><span class='need'>&nbsp;&nbsp;&nbsp;只支持整课购买的课程</span>
                </td>
            </tr>
          <tr>
              <td>安卓团购价格<span class="need">*</span></td>
                <td>
                    <input id='min_price' class="inputclass1" id="course_group_fee" name="GroupbuyService[course_group_fee]" style="width:10%" type="text" value="<?= $model->course_group_fee ?>"  />
                </td>
            </tr>
            
             <tr>
              <td>IOS团购价格
                  <span class="need">*</span></td>
                <td>
                    <select name="GroupbuyService[course_group_fee_ios]">
                        <?php
                        if($course_group_fee_ios){
                            foreach($course_group_fee_ios as $k=>$v){
                                if($model->course_group_fee_ios==$v['price']){
                                    echo "<option value=".$v['price']." selected=selected>".$v['price']."</option>";
                                }else{
                                   echo "<option value=".$v['price'].">".$v['price']."</option>"; 
                                }
                                  
                            }
                        }
                        ?>
                        
                    </select>
                </td>
            </tr>
            <tr>
            	<td style="width: 80px">团购总数<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" id="person_count_totalid" name="GroupbuyService[person_count_total]" style="width:30%" type="text" value="<?= $model->person_count_total ?>"
                           datatype="/^-?[1-9]\d*$/" nullmsg="不能为空" errormsg="必须为数字" sucmsg="&nbsp;"/><span class='need'>&nbsp;&nbsp;&nbsp;&nbsp;实际购买数量不受该数量限制</span>
                </td>
            </tr>
            <tr>
            	<td style="width: 80px">初始参团人数<span class='need'>*</span></td>
                <td>
                	<input class="inputclass1" name="GroupbuyService[person_count_init]" style="width:30%" type="text" value="<?= $model->person_count_init; ?>" 
                               datatype="/^-?[1-9]\d*$/" nullmsg="不能为空" errormsg="必须为数字"  sucmsg="&nbsp;"/><span class='need'>&nbsp;&nbsp;&nbsp;&nbsp;此处数量为团购上线时显示的已参团人数</span>
                </td>
            </tr>
            <tr>
            	<td style="width: 80px">即将售罄人数<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" id="person_count_finalid" name="GroupbuyService[person_count_final]" style="width:30%" type="text" 
                           value="<?= $model->person_count_final; ?>" datatype="/^-?[1-9]\d*$/" nullmsg="不能为空" errormsg="必须为数字"  sucmsg="&nbsp;"/>
                    <span class='need'>&nbsp;&nbsp;&nbsp;当参团人数达到这个值时,前端显示'xxxx人已参团，即将售罄'</span>
                </td>
            </tr>
             
            <tr>
            	<td style="width: 80px">课程名称<span class='need'>*</span></td>
                <td>
               <input class="inputclass1" name="GroupbuyService[title]" style="width:30%" type="text" value="<?= $model->title ?>" datatype="*1-50" 
                      nullmsg="请输入课程名称，最多50个字！" sucmsg="&nbsp;"/><span class='need'>&nbsp;&nbsp;&nbsp;&nbsp;此标题为用户团购成功后，收到的系统通知中显示的课程名称，建议填写真实课程名称.</span>
                </td>
            </tr>
            <tr>
               <td style="width: 80px">起止日期<span class="need">*</span></td>
               <td>
                   <input type="text" name="GroupbuyService[start_time]" id="start_time" value="<?if($model->start_time){echo date('Y-m-d H:i',$model->start_time);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
                   <script type="text/javascript">
                    Calendar.setup({
                       weekNumbers: true,
                       inputField : "start_time",
                       trigger    : "start_time",
                       dateFormat: "%Y-%m-%d %H:%M",
                       showTime: true,
                       minuteStep: 1,
                       onSelect   : function() {this.hide();}
                   });
                </script>
                至
                <input type="text" name="GroupbuyService[end_time]" id="end_time" value="<? if($model->end_time){echo date('Y-m-d H:i',$model->end_time);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
                <script type="text/javascript">
                    Calendar.setup({
                       weekNumbers: true,
                       inputField : "end_time",
                       trigger    : "end_time",
                       dateFormat: "%Y-%m-%d %H:%M",
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
<script>
 
	//父窗口句柄
	var index = parent.layer.getFrameIndex(window.name);
    //保存按钮
    $("#asave").click(function () {
        var start_time=$("#start_time").val();
        var end_time=$("#end_time").val();
        if(start_time>=end_time){
          layer.msg('开始时间不能大于结束时间', {icon: 2});
          return false;
        }
        
         var course_group_fee=$("#course_group_fee").val();
        if(course_group_fee<=0){
             layer.msg('安卓团购价格必须大于1', {icon: 2});
            return false;
        }
        
         //团购总数
        var person_count_totalid=$("#person_count_totalid").val();
        //即将售停
        var person_count_finalid=$("#person_count_finalid").val();
        var su = parseInt(person_count_totalid-person_count_finalid);
         if(su<=0){
          layer.msg('不允许团购总数小于等于即将售罄人数！', {icon: 2});
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
    
       //选择ios价格
    $("#iospricesel").click(function () {
            var content = '/course/ios_price_sel';
            var title = '选择ios价格';
            content = content + '?price='+ encodeURI($("#ios_price").val());
            var search =layer.open({
                type: 2,
                title: title,
                maxmin: true,
                area : ['700px' , '600px'],
                content: content
              });
            layer.full(search);
      });
    
    
    
    
	//表单验证
    $("#cmsform").Validform({
		tiptype:3,
	});	
        
        
        
        
        
        
</script>