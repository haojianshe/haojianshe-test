  <?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js?v=20170526"></script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.js?v=201605191725"> </script>
  <script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
  <!--添加自定义按钮 阿里视频 模板 标题...-->
  <script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/edittool/edittool.js?d=20170517"></script>  
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
 	<?php if(isset($lecturemodel->newsid)){?>
    <input type ="hidden" name='isedit' value='1' />
    <input type ="hidden" name="LectureService[newsid]" value="<?= $lecturemodel->newsid ?>" />
    <?php } ?>
    <tr>
    	<td style="width: 80px">标题<span class='need'>*</span></td>
        <td>
        	<input class="inputclass1" name="NewsService[title]" style="width:30%" type="text" value="<?= $newsmodel->title ?>" datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
        </td>
    </tr>
    <tr>
    	<td>文章类型</td>
        <td>
        	<select id="maintype" name="LectureService[lecture_level1]" class="valid" value="1" >
                <? foreach ($maintypemodel as $mainitem) { ?>
                <option value="<?= $mainitem['maintypeid'] ?>"><?= $mainitem['maintypename'] ?></option>
                <?}?>
            </select>
            <select id="subtype" name="LectureService[lecture_level2]" class="valid" value="1">
                
                <?php 
                if(!empty($subtypemodel)){
                foreach ($subtypemodel as $subitem) { ?>
                <option value="<?= $subitem['subtypeid'] ?>"><?= $subitem['subtypename'] ?></option>
                <?php }}else{
                    echo "<option value=\"\">请选择</option>";
                } ?>
            </select>
            <script>
		//选中类型
            	$('#maintype').val('<?=$lecturemodel->lecture_level1 ?>');
            	$('#subtype').val('<?=$lecturemodel->lecture_level2 ?>');
            </script>
        </td>
    </tr>
    <tr>
    	<td>关键词</td>
        <td>
        	<input class="inputclass1" name="NewsService[keywords]" style="width:300px" type="text" value="<?= $newsmodel->keywords ?>"/>
        	&nbsp;&nbsp;多关键词之间用","隔开
        </td>
    </tr>
    <?php
    if($type!=2){
    ?>
    <tr>
    	<td>来源</td>
        <td>
        	<input class="inputclass1" name="NewsDataService[copyfrom]" style="width:300px" type="text" value="<?= $newsdatamodel->copyfrom ?>" />
        </td>
    </tr>
  
    <tr>
    	<td>摘要</td>
        <td>
        	<textarea name="NewsService[desc]" style="width: 750px; height: 81px; margin: 0px;" datatype="*0-100" errormsg="摘要最多100个字符！" sucmsg="&nbsp;" ><?= $newsmodel->desc ?></textarea>
        </td>
    </tr>
    <tr>
    	<td>点赞数<span class='need'>*</span></td>
        <td>
			<input class="inputclass1" name="NewsDataService[supportcount]" style="width:100px" type="text" value="<?= $newsdatamodel->supportcount ?>"  datatype="n" nullmsg="请输入点赞数！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <tr>
    	<td>浏览数<span class='need'>*</span></td>
        <td>
			<input class="inputclass1" name="NewsDataService[hits]" style="width:100px" type="text" value="<?= $newsdatamodel->hits ?>"  datatype="n" nullmsg="请输入浏览数！" sucmsg="&nbsp;" />
        </td>
    </tr>
    <tr>
     <?php
    }
    ?>
    	<td>发布时间<span class='need'>*</span></td>
        <td>
        	<!-- 如果是发布状态的文章，并且定时已经为0，则不可修改 -->
        	<? if ($lecturemodel->status==0 && $lecturemodel->publishtime==0 && $lecturemodel->newsid>0) {?>
        		<span style='color:#79a605'>无</span>
        	<? } else {?>
	        	<input type="text" name="LectureService[publishtime]" id="ptime" value="<?if($lecturemodel->publishtime >0){echo date('Y-m-d H:i',$lecturemodel->publishtime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
			       <script type="text/javascript">
			        Calendar.setup({
			           weekNumbers: true,
			           inputField : "ptime",
			           trigger    : "ptime",
			           dateFormat: "%Y-%m-%d %H:%M",
			           showTime: true,
			           minuteStep: 1,
			           onSelect   : function() {this.hide();}
			       });
			    </script>
        	<? } ?>			
        </td>
    </tr>
   
      <tr>
    	<td>封面样式<span class='need'>*</span></td>
        <td>
            <select name="LectureService[thumbtype]" id="thumbtypeId">
                <option value="1" <?php if($lecturemodel->thumbtype==1){ echo 'selected=selected';};?> >通栏样式</option>
                <option value="2" <?php if($lecturemodel->thumbtype==2){ echo 'selected=selected';};?>>左图样式</option>
                <option value="3" <?php if($lecturemodel->thumbtype==3){ echo 'selected=selected';};?>>三图样式</option>
            </select>
        </td>
    </tr>


      <tr  id="content_type-edit">
        <td>内容类型<span class='need'>*</span></td>
        <td>
            <select name="LectureService[content_type]">
                <option value="1" <?php if($lecturemodel->content_type==1){ echo 'selected=selected';};?> >正常图文</option>
                <option value="2" <?php if($lecturemodel->content_type==2){ echo 'selected=selected';};?>>包含视频</option>
                <option value="3" <?php if($lecturemodel->content_type==3){ echo 'selected=selected';};?>>包含语音</option>
            </select>
        </td>
    </tr>
    <tr>
    	<td>缩略图<span class='need'>*</span></td>
        <td>
        	<input type ="hidden" id="thumb0" name="thumb[]" value="<?= $thumbs[0]['img'] ?>" />
        	<input type ="hidden" id="thumb1" name="thumb[]" value="<?= $thumbs[1]['img'] ?>" />
        	<input type ="hidden" id="thumb2" name="thumb[]" value="<?= $thumbs[2]['img'] ?>" />    
                
        	<a name='athumb' id='athumb0' thumbid='0' href='#'>
                    <img id='img0' src="<? if($thumbs[0]['img']){echo $thumbs[0]['img'];}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
                </a>        	
                <span id="spanid" <?php if($lecturemodel->thumbtype!=3){ echo 'style="display: none"';}?>>
                <a name='athumb' id='athumb1' thumbid='1' href='#'>
                    <img id='img1' src="<? if($thumbs[1]['img']){echo $thumbs[1]['img'];}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;'/>
                </a>
        	<a name='athumb' id='athumb2' thumbid='2' href='#'>
                    <img id='img2' src="<? if($thumbs[2]['img']){echo $thumbs[2]['img'];}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;'/>
                </a>3图时大小230*150 1图宽为690高度可以不限制
                </span>
        </td>
    </tr>
    
    <?php
    if($type==2){
    ?>
     <tr>
   <td>投放区域<span class='need'>*</span></td>
        <td>
        <input class="all_check"  type="checkbox"  >全选
        	<?php
               
                foreach ($province as $key => $value) {
        	?>
	<input <?php 
              if(!empty($lecturemodel->proviceids)){
                $array = explode(',', $lecturemodel->proviceids);
                if($array){
                    foreach($array as $key=>$v){#|| $v==100
                        if($v==$value['provinceid']){
                             echo 'checked="checked"';
                        }
                    }
                }
              }
             ?>
            type="checkbox" name="proviceids[]" value="<?=$value['provinceid']?>" ><?= $value['provincename']?>
	     <?php
        	}
              ?>
        </td>
     </tr>
        <tr>
   <td>针对身份<span class='need'>*</span></td>
        <td>
        <input class="all_check"  type="checkbox"  >全选
       <?php foreach ($profession as $key => $value) { ?>
	<input <?php 
        if(!empty($lecturemodel->professionids)){
            $array = explode(',', $lecturemodel->professionids);
               if($array){
                    foreach($array as $key=>$v){#  || $v==100
                        if($v==$value['professionid']){  echo 'checked="checked"'; }
                    } 
                  }
               }
              ?>
            type="checkbox" name="professionids[]" value="<?=$value['professionid']?>" ><?= $value['professionname']?>
		<?php
        	}
                ?>
        </td>
     </tr>
    <?php
    }
    ?>
     <?php if($type!=2){ ?>
    <tr>
    	<td>是否进入精讲列表</td>
        <td>
            <input type="radio" name="LectureService[is_in_list]" value="1" <?php if($lecturemodel->is_in_list==1 || !isset($lecturemodel->is_in_list)){ echo 'checked=checked';};?> />是
            <input type="radio" name="LectureService[is_in_list]" value="2" <?php if($lecturemodel->is_in_list==2){ echo 'checked=checked';};?> />否
        </td>
    </tr>
    
     <tr>
    	<td>精彩课程推荐</td>
        <td>
                <div>  
                    <span class="normalbtn_l">
                        
                        <div><input type="text" value="<?php  if(!empty($course[0]['title'])) echo $course[0]['title'];?>" id="courseidOne"  /><a onclick="func(1)" href="#">推荐课程一</a>
                            <input type="hidden" value="<?php  if(!empty($course[0]['courseid'])) echo $course[0]['courseid'];?>" name="courseid_one" id="hidden1"  />
                        </div>
                        
                        <div><input type="text" value="<?php  if(!empty($course[1]['title'])) echo $course[1]['title'];?>"  id="courseidTwo" /><a onclick="func(2)" href="#">推荐课程二</a>
                            <input type="hidden" value="<?php if(!empty($course[1]['courseid'])) echo $course[1]['courseid'];?>" name="courseid_two" id="hidden2"  />
                        </div>
                  </span>
             </div>
        </td>
    </tr>
        <tr>
    	<td>清除推荐课</td>
        <td>
                <div>  
                    是<span class="normalbtn_l"><input type="radio" value="1" name="radio" /></span>
                    否<span class="normalbtn_l"><input type="radio" value="2" name="radio"  checked="checked"/></span>
             </div>
        </td>
    </tr>
     <?php } ?>
    <tr <?php if($type==2){ echo 'style="display: none;"';}?>>
    	<td>内容<span class='need'>*</span></td>
        <td>
         <script name='NewsDataService[content]' id="editor" type="text/plain" style="width:770px;height:500px;"></script>
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
    <input type="hidden" value="<?php echo $type?>" name="type" />
    <input type="hidden" value="<?=$lecturemodel->lecture_level1 ?>" name="lecture_level_name" />
    </tbody>
 </table> 
  <?php ActiveForm::end(); ?> 
  </div>
<input  type="hidden" value="<?php echo $type;?>" id="typeid" />

<script type="text/javascript">
  //省份全选按钮
  $(".all_check").change(function(){
    if(this.checked){
      $(this).siblings().prop("checked", true);;
    }else{
      $(this).siblings().removeAttr("checked");
    }
  });

     //选择整课课程
      function func(i){
            var content = '/course/course_list';
            var title = '选择课程';
            content = content + '?id='+i;
            layer.open({
                type: 2,
                title: title,
                maxmin: false,
                shadeClose: false, //点击遮罩关闭层
                area : ['80%' , '90%'],
                content: content
          });
      }
     //点击封面样式选择时 不同类型切换
    $("#thumbtypeId").change(function(){
        var value = $(this).val();       
        if(value==3){
            $("#spanid").show();
            $("#content_type-edit").hide();
        }else{

            $("#spanid").hide();
            $("#content_type-edit").show();
        }
    });
  </script>
  <script>
  		//父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
                var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
  		//显示富文本框内容
  		ue.ready(function() {
  			ue.setContent('<?= $newsdatamodel->content ?>');   
  		});
             
  		//点击缩略图事件
  		$("a[name=athumb]").click(function () {
		    var thumbid = $(this).attr("thumbid");
		    var content = '/lecture/thumbupload';
			var title = '编辑缩略图';
			content = content + '?id=' + thumbid +'&url='+ encodeURI($('#thumb'+thumbid).val());
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
        //保存按钮
        $("#asave").click(function () {
            var typeid = $("#typeid").val();
            
            //检查缩略图
            t0 = $('#thumb0').val();
            t1 = $('#thumb1').val();
            t2 = $('#thumb2').val();
           var thumbtypeId = $("#thumbtypeId").val();
            if(t0 == ''){
            	layer.msg('第一张缩略图必须上传', {icon: 2});
          		return false;
            }
            if(thumbtypeId == 3){ //(t1 =='' && t2 != '') || 
                if(t2 =='' || t1 == ''){
                    layer.msg('三图样式下，必须选择三张图片', {icon: 2});
                    return false;
                } 
            }
            //检查富文本框
            var html = $.trim(ue.getContent());
            if(html =='' && typeid !=2){
            	layer.msg('请输入文章内容', {icon: 2});
          	return false;
            }
            //如果选择通栏和左图，把第二和第三张图片删除掉
            if(thumbtypeId<3){
                $("#thumb1").val('');
                $("#thumb2").val('');
            }
            
            var hidden1 = $("#hidden1").val();//推荐课程一
            var hidden2 = $("#hidden2").val();//推荐课程二
           
           
           var proviceids =$("input[name='proviceids[]']").is(':checked');
           var professionids =$("input[name='professionids[]']").is(':checked');
           //选择专题时必须选择区域和身份
           if(typeid==2){
                if(proviceids==false){
                    layer.msg('投放区域必须选择！', {icon: 2});
                    return false; 
                }  
               if(professionids==false){
                    layer.msg('请你选择身份！', {icon: 2});
                    return false; 
                }  
            }
            if(typeid ==1){
                if(hidden1 !='' || hidden2 !=''){
               if(hidden1==hidden2){
                   layer.msg('所选课程重复,课程不能重复！', {icon: 2});
          	return false;
               }
                if(hidden1==''){
                    layer.msg('推荐课程一不能为空！', {icon: 2});
                 return false;
                }
                  if(hidden2==''){
                    layer.msg('推荐课程二不能为空！', {icon: 2});
                 return false;
                }
              }
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
      //选择主类型
        $("#maintype").change(function () {
            var objmain = $('#maintype')[0];
            var objsub = $('#subtype')[0];
            var index = objmain.selectedIndex; //序号，取当前选中选项的序号
            var val = objmain.options[index].value;
            //先清空agent，然后添加全部选项
            objsub.options.length = 0;
            objsub.add(new Option("选择分类型", "0"));
            //首先清空经纪人选项，并且加全部选项
            if (val != '0') {
                $.ajax({
                    type: "post",//使用get方法访问后台
                    dataType: "json",//返回json格式的数据
                    url: "/lecture/ajaxsubtype",//要访问的后台地址
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
                           layer.msg('访问出错', {icon: 2});
                    }
                });
            }
        });
    </script>