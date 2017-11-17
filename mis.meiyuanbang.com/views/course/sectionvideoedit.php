<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.js?v=201605191725"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>

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
            <?php if(isset($model->coursevideoid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="CourseSectionVideoService[coursevideoid]" value="<?= $model->coursevideoid ?>" />
            <?php } ?>

            <tr>
            	<td style="width: 80px">标题<span class='need'>*</span></td>
                <td>
                	<input class="inputclass1" name="CourseSectionVideoService[title]" style="width:80%" type="text" value="<?= $model->title ?>" datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>
           
     
            <tr>
            	<td>序号<span class='need'>*</span></td>
                <td>
        			<input class="inputclass1" name="CourseSectionVideoService[section_video_num]" style="width:100px" type="text" value="<?= $model->section_video_num ?>"  datatype="n" nullmsg="请输入序号！" sucmsg="&nbsp;" />
                </td>
            </tr>


             <tr>
               <td >视频选择<span class="need">*</span></td>
               <td>
                 <input type ="hidden" class="inputclass1" id="videoid" name="CourseSectionVideoService[videoid]" style="width:150px" type="text" value="<?= $model->videoid ?>" />
                  <div>  
                      <span class="videoinfo"><?= $model->videoid ?></span>
                      <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
                  </div>
               </td>
             </tr> 



            <tr>
                <td>原价</td>
                <td>
                    <input class="inputclass1" <? if($course->buy_type==2 || $course->is_free==0 ){ echo 'disabled';}?> name="CourseSectionVideoService[price]" style="width:100px" type="text" value="<?= $model->price?$model->price:0 ?>"  />
                </td>
            </tr>
         
            <tr>
                <td>现价</td>
                <td>
                    <input id='sale_price' class="inputclass1" <? if($course->buy_type==2 || $course->is_free==0 ){ echo 'disabled';}?>  name="CourseSectionVideoService[sale_price]" style="width:100px" type="text" value="<?= $model->sale_price?$model->sale_price:0 ?>"   />
                </td>
            </tr>
           <tr>
                <td>IOS售价</td>
                <td>
                    <input id="ios_price" hidden class="inputclass1" name="CourseSectionVideoService[ios_price]" style="width:100px" type="text" value="<?= $model->ios_price?$model->ios_price:0 ?>"   />
                    <span class="normalbtn_l"><a <? if($course->buy_type==2 || $course->is_free==0 ){ echo 'hidden';}?>  id="iospricesel" href='javascript:;'>选择</a></span>
                    <span id="ios_price_info"><?= $model->ios_price?$model->ios_price:0 ?></span>元
                </td>
            </tr>



            <tr>
                <td>普通渠道佣金价格</td>
                <td>
                    <input id="bounty_fee" class="inputclass1" name="CourseSectionVideoService[bounty_fee]" style="width:100px" type="text" value="<?= $model->bounty_fee?$model->bounty_fee:0 ?>"   />
                </td>
            </tr>

            
            <tr>
                <td>苹果内购佣金价格</td>
                <td>
                    <input id="bounty_fee_ios" class="inputclass1" name="CourseSectionVideoService[bounty_fee_ios]" style="width:100px" type="text" value="<?= $model->bounty_fee_ios?$model->bounty_fee_ios:0 ?>"   />
                </td>
            </tr>


          


            <tr>
            	<td></td>
            	<td>
        	        <div>
        	        	<span class="normalbtn_l"><a id="asave" href='javascript:;'>保存</a></span>
        	        	<span class="normalbtn_l"><a id="aclose" href='javascript:;'>关闭</a></span>	        	
        	        </div>
                </td>
            </tr>
             <tr>
                <td colspan="2">
                温馨提示：请在填写章序号时保证前后两节的节序号为连续的阿拉伯数字，初始第一节节序号为“1”、以此类推第二节节序号为“2”；不要出现节序号不连续，节序号不连续会造成前端展示缺少某一节。例如节序号为“1345”课程的这一章将缺少第二节。
                </td>
            </tr>
        </tbody>
    </table> 
    <?php ActiveForm::end(); ?> 
</div>
<script>
	//父窗口句柄
	var index = parent.layer.getFrameIndex(window.name);
	
    //选择视频
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


  	//选择视频
    $("#selbtn").click(function () {
            var content = '/course/videosel';
            var title = '选择视频';
            content = content + '?videoid='+ encodeURI($("#videoid").val());
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
        var bounty_fee=$("#bounty_fee").val();
        var bounty_fee_ios=$("#bounty_fee_ios").val();
        var ios_price=$("#ios_price").val();
        var sale_price=$("#sale_price").val();
        if(bounty_fee>ios_price || bounty_fee_ios>ios_price ||bounty_fee_ios>sale_price || bounty_fee>sale_price){
           layer.msg('佣金价格不能大于课程价格', {icon: 2});
            return false;
        }

        if(!($("#videoid").val())){
          alert("请选择视频！");
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
    
	//表单验证
    $("#cmsform").Validform({
		tiptype:3,
	});	
</script>