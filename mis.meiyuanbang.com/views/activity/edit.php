<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mis\lib\enumcommon\ActivityClickTypeEnum;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js?v=20170526"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.js?v=20170526"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/alivideo/alivideo.js"></script>  
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
          <?php if(isset($activitymodel->newsid)){?>
          <input type ="hidden" name='isedit' value='1' />
          <input type ="hidden" name="ActivityService[newsid]" value="<?= $activitymodel->newsid ?>" />
          <?php } ?>
          <tr>
           <td style="width: 80px">活动标题<span class='need'>*</span></td>
           <td>
               <input class="inputclass1" name="NewsService[title]" style="width:70%" type="text" value="<?= $newsmodel->title ?>" datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
           </td>
       </tr>
       <tr>
          <td style="width: 80px">跳转类型</td>
          <td>
              <select name="ActivityService[click_type]">
                  <option  value="<?=ActivityClickTypeEnum::NONE?>">请选择</option>
                  <option  <? if($activitymodel->click_type==ActivityClickTypeEnum::WAP){?>selected<?}?> value='<?=ActivityClickTypeEnum::WAP?>'>网页 </option>
                  <option  <? if($activitymodel->click_type==ActivityClickTypeEnum::HOME){?>selected<?}?> value='<?=ActivityClickTypeEnum::HOME?>'>个人主页 </option>
                  <option  <? if($activitymodel->click_type==ActivityClickTypeEnum::LECTURE){?>selected<?}?> value='<?=ActivityClickTypeEnum::LECTURE?>'>文章 </option>
                  <option  <? if($activitymodel->click_type==ActivityClickTypeEnum::TWEET){?>selected<?}?> value='<?=ActivityClickTypeEnum::TWEET?>'>帖子 </option>
                  <option  <? if($activitymodel->click_type==ActivityClickTypeEnum::LESSON){?>selected<?}?> value='<?=ActivityClickTypeEnum::LESSON?>'>考点 </option>
                  <option  <? if($activitymodel->click_type==ActivityClickTypeEnum::SEARCH){?>selected<?}?> value='<?=ActivityClickTypeEnum::SEARCH?>'>搜索</option>
              </select>
          </td>
      </tr>


       <tr>
          <td style="width: 80px">活动类型</td>
          <td>
              <select name="ActivityService[activity_type]">
                  <option  <?if($activitymodel->activity_type==1){?>selected<?}?> value='1'>运营活动 </option>
                  <option  <?if($activitymodel->activity_type==2){?>selected<?}?> value='2'>直播活动 </option>
              </select>
          </td>
      </tr>


      <script type="text/javascript">
        $("[name='ActivityService[click_type]']").click(function(){       
        //0 无跳转 1 wap网页 2 home个人主页 3 lecture文章 4 tweet帖子 5 lesson考点 6 search搜索
        switch(this.value){
            case '<?=ActivityClickTypeEnum::NONE?>':     
                $(".click_tr").hide(); 
                $(".button_text").hide();                                
                break;
            case '<?=ActivityClickTypeEnum::WAP?>':     
                $(".click_tr").hide(); 
                $(".signup_url").show(); 
                $(".button_text").show();                                
                break;
            case '<?=ActivityClickTypeEnum::HOME?>':     
                $(".click_tr").hide();  
                $(".button_text").show(); 
                $("#click_tr_param1").show();
                $(".click_title_param1").html('个人编号');
                $("[name='ActivityService[param1]']").val('');
                break;
            case '<?=ActivityClickTypeEnum::LECTURE?>':
                $(".click_tr").hide();
                $("#click_tr_param1").show();
                $(".button_text").show(); 
                $(".click_title_param1").html('文章链接');
                $("[name='ActivityService[param1]']").val('');
                break;
            case '<?=ActivityClickTypeEnum::TWEET?>':
                $(".click_tr").hide();
                $(".button_text").show(); 
                $("#click_tr_param1").show();
                $(".click_title_param1").html('帖子编号');
                $("[name='ActivityService[param1]']").val('');
                break;
            case '<?=ActivityClickTypeEnum::LESSON?>':
                $(".click_tr").hide();
                $(".button_text").show(); 
                $("#click_tr_param1").show();
                $(".click_title_param1").html('考点编号');
                $("[name='ActivityService[param1]']").val('');
                break;
            case '<?=ActivityClickTypeEnum::SEARCH?>':
                $(".click_tr").hide();
                $(".button_text").show(); 
                $("#click_tr_param1").show();
                $("#click_tr_param2").show();
                $(".click_title_param1").html('搜索分类（空格隔开）');
                $(".click_title_param2").html('搜索标签（逗号隔开）');
                $("[name='ActivityService[param1]']").val('');
                $("[name='ActivityService[param2]']").val('');
                break;
            default:               
                break;             
        }
    });
</script>


<tr <? if($activitymodel->click_type=='<?=ActivityClickTypeEnum::NONE?>' || empty($activitymodel->param1)){?>style="display:none;"<?}?> class="click_tr" id='click_tr_param1'>
    <td class="click_title_param1" style="width: 80px" >
        <? switch ($activitymodel->click_type) {
            case ActivityClickTypeEnum::HOME:
                echo '个人编号';
                break;
            case ActivityClickTypeEnum::LECTURE:
                echo '文章编号';
                break;
            case ActivityClickTypeEnum::TWEET:
                echo '帖子编号';
                break;
            case ActivityClickTypeEnum::LESSON:
                echo '考点编号';
                break;
            case ActivityClickTypeEnum::SEARCH:
                echo '搜索分类（空格隔开）';
                break;
            default:
                break;
        }?>
    </td>
    <td class="click_content_param1">      
        <input class="inputclass1" name="ActivityService[param1]" style="width:30%" type="text" value="<?= $activitymodel->param1 ?>"  />      
    </td>
</tr>

<tr <? if($activitymodel->click_type=='<?=ActivityClickTypeEnum::NONE?>' || empty($activitymodel->param2)){?>style="display:none;"<?}?> class="click_tr" id='click_tr_param2'>
    <td class="click_title_param2" style="width: 80px" >
       <? switch ($activitymodel->click_type) {                
        case ActivityClickTypeEnum::SEARCH:
            echo '搜索标签（逗号隔开）';
            break;
        default:
            break;
    }
    ?>
</td>
<td class="click_content_param2">      
    <input class="inputclass1" name="ActivityService[param2]" style="width:30%" type="text" value="<?= $activitymodel->param2 ?>"  />      
</td>
</tr>



<tr class="button_text" <? if($activitymodel->click_type=='<?=ActivityClickTypeEnum::NONE?>'){?>style="display:none;"<?}?> >
    <td style="width: 80px">点击按钮文本</td>
    <td>
        <input class="inputclass1" name="ActivityService[click_button_text]" style="width:30%" type="text" value="<?= $activitymodel->click_button_text ?>"   errormsg="按钮文本" sucmsg="&nbsp;"/>
    </td>
</tr>





<tr class="signup_url click_tr" <? if($activitymodel->click_type!='<?=ActivityClickTypeEnum::WAP?>'){?>style="display:none;"<?}?>>
   <td style="width: 80px">报名网址</td>
   <td>
       <input class="inputclass1" name="ActivityService[signup_url]" style="width:50%" type="text" value="<?= $activitymodel->signup_url ?>" ignore="ignore" datatype="url" errormsg="请检查报名地址是否为url地址" sucmsg="&nbsp;"/>
   </td>
</tr>
<tr>
   <td style="width: 80px">展示网址</td>
   <td>
       <input class="inputclass1" id='activity_url' name="ActivityService[activity_url]" style="width:50%" type="text" value="<?= $activitymodel->activity_url ?>"  ignore="ignore" datatype="url" errormsg="请检查展示地址是否为url地址" sucmsg="&nbsp;" />
   </td>
</tr>
<script>
   /* $("[name='ActivityService[activity_url]']").blur(function(){
        if(this.value==''){
            $(".activity_content").show();
               
        }else{
            $(".activity_content").hide();

        }
    });*/
</script>
<tr>
   <td style="width: 80px">起止日期</td>
   <td>
       <input type="text" name="ActivityService[btime]" id="btime" value="<?if($activitymodel->etime){echo date('Y-m-d H:i',$activitymodel->btime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
       <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "btime",
           trigger    : "btime",
           dateFormat: "%Y-%m-%d %H:%M",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
    </script>
    至
    <input type="text" name="ActivityService[etime]" id="etime" value="<? if($activitymodel->etime){echo date('Y-m-d H:i',$activitymodel->etime);} ?>" class="inputclass1" readonly="readonly" style="width:140px" />&nbsp;
    <script type="text/javascript">
        Calendar.setup({
           weekNumbers: true,
           inputField : "etime",
           trigger    : "etime",
           dateFormat: "%Y-%m-%d %H:%M",
           showTime: true,
           minuteStep: 1,
           onSelect   : function() {this.hide();}
       });
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
<tr>
   <td>来源</td>
   <td>
       <input class="inputclass1" name="NewsDataService[copyfrom]" style="width:300px" type="text" value="<?= $newsdatamodel->copyfrom ?>" />
   </td>
</tr>
<tr>
   <td>摘要</td>
   <td>
       <textarea name="NewsService[desc]" style="width:70%;height:30px;" datatype="*0-100" errormsg="摘要最多100个字符！" sucmsg="&nbsp;" ><?= $newsmodel->desc ?></textarea>
   </td>
</tr>
<tr>
   <td>缩略图<span class='need'>*</span></td>
   <td>
       <input type ="hidden" id="thumb" name="thumb" value="<?= $thumb_url ?>" />      	
       <a name='athumb' id='athumb' thumbid='0' href='#'><img id='imgthumb' src="<? if($thumb_url){echo $thumb_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' /></a>
   </td>
</tr>

<tr>
   <td>消耗积分数<span class='need'>*</span></td>
   <td>
       <input class="inputclass1" name="ActivityService[costcoin]" style="width:300px" type="text" value="<?= $activitymodel->costcoin ?>" datatype="n" nullmsg="请输入消耗积分数！" sucmsg="&nbsp;" />
   </td>
</tr>
<tr class="activity_content">
   <td>活动详情</td>
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
</tbody>
</table> 
<?php ActiveForm::end(); ?> 
</div>
<script>
  		//父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
  		//显示富文本框内容
  		  var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
  		ue.ready(function() {
            ue.setContent('<?= $newsdatamodel->content ?>');
        });
  		//点击缩略图事件
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
        //保存按钮
        $("#asave").click(function () {
            //活动网址或者活动详情必须输入一项
            var html = $.trim(ue.getContent());
            var acturl = $.trim($('#activity_url').val());
            if(html =='' && acturl==''){
            	layer.msg('活动详情或者展示网址至少输入一项', {icon: 2});
                return false;
            }
            //检查起止时间,规则为只要输入了截止时间，则截止时间必须大于开始时间
            btime = $('#btime').val();
            etime = $('#etime').val();
            if(etime!='' && etime<btime){
            	layer.msg('活动截止时间应大于起始时间', {icon: 2});
                return false;
            }
            //检查缩略图
            t = $('#thumb').val();
            if(t == ''){
            	layer.msg('缩略图必须上传', {icon: 2});
                return false;
            }
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
               layer.msg('<?= $msg ?>', {icon: 2});
               <? } ?>
               <? } ?>
		//表单验证
        $("#cmsform").Validform({
          tiptype:3,
      });	      
    </script>