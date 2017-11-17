<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\service\dict\CourseDictDataService;

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
            <?php if(isset($model->courseid)){?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="CourseService[courseid]" value="<?= $model->courseid ?>" />
            <?php } ?>
           
            <tr>
              <td style="width: 80px">标题<span class='need'>*</span></td>
                <td>
                  <input class="inputclass1" name="CourseService[title]" style="width:98%" type="text" value="<?= $model->title ?>" datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>
             <tr>
               <td >老师<span class="need">*</span></td>
               <td>
                 <input type ="hidden" class="inputclass1" id="teacheruid" name="CourseService[teacheruid]" style="width:80px" type="text" value="<?= $model->teacheruid ?>" />
                  <div>  
                      <span class="userinfo"><?if($usersinfo){echo $usersinfo[0]['sname'];}?></span>
                      <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
                  </div>
               </td>
             </tr> 
            <tr>
                <td width="80">分类<span class="need">*</span></td>
                <td>
                <select name="CourseService[f_catalog_id]" id="f_catalog_id">
                    <?if(empty($model->f_catalog_id)){ ?>
                        <option value="" selected="">一级分类</option>
                    <?}?>

                    <? foreach ($catalog['imgmgr_level_1'] as $key => $value) {?>            
                        <option value="<?=$key?>" key="<?=$key?>" <?if ($key==$model->f_catalog_id) {?>selected<?} ?>>
                            <?=$value?>
                        </option>
                    <?}?>
                </select>
                <select name="CourseService[s_catalog_id]" id="s_catalog_id">
                    <?if(empty($model->s_catalog_id)){ ?>
                        <option value="" selected="">二级分类</option>
                    <?}else{?>
                        <option value="<?=$model->s_catalog_id?>" selected=""><?= CourseDictDataService::getCourseSubTypeById($model->f_catalog_id,$model->s_catalog_id); ?>
                        </option>
                    <?}?>
                </select>
                </td>
            </tr>
            <tr>
                   <td style="width: 80px;">课程图<span class='need'>* </span>250*140px</td>
                   <td>
                     <input type ="hidden" id="thumb_thumb_url" name="CourseService[thumb_url]" value="<?= $model->thumb_url ?>" />     
                    <a name='athumb' id='athumb_thumb_url' data-name="thumb_url" thumbid='0' href='#'><img id='imgthumb_thumb_url' src="<? if($model->thumb_url){echo $model->thumb_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;' /></a>
                   </td>
                 </tr>
            <tr>

            <tr>
                <td style="width: 80px">老师描述<span class='need'>*</span></td>
                <td>
                    <textarea name="CourseService[teacher_desc]" style="width:98%;height:100px;" datatype="*0-500" errormsg="摘要最多500个字符！" sucmsg="&nbsp;" ><?= $model->teacher_desc ?></textarea>
                    
                </td>
            </tr>
            <tr>
              <td>浏览数</td>
                <td>
              <input class="inputclass1" name="CourseService[hits_basic]" style="width:100px" type="text" value="<?= $model->hits_basic ?>"  />
                </td>
            </tr>

            <tr>
              <td>苹果内购佣金价格</td>
                <td>
              <input class="inputclass1" id='course_bounty_fee_ios' name="CourseService[course_bounty_fee_ios]" style="width:100px" type="text" value="<?= $model->course_bounty_fee_ios ?>"  />
                </td>
            </tr>     



            <tr>
              <td>普通渠道佣金价格</td>
                <td>
              <input class="inputclass1" id="course_bounty_fee" name="CourseService[course_bounty_fee]" style="width:100px" type="text" value="<?= $model->course_bounty_fee ?>"  />
                </td>
            </tr>   
            <tr>
              <td>课程付费类型<span class='need'>*</span></td>
              <td>
                <select name="CourseService[is_free]"  id="is_free">
                        <option value="0" <? if($model->is_free==0){echo "selected";}?> selected="">免费</option>
                        <option value="1"<? if($model->is_free==1){echo "selected";}?> >付费</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>课程价格</td>
                <td>
                  <select name="CourseService[buy_type]"  <? if($model->buy_type && $model->status==2){echo "disabled";}?>  id="buy_type">
                          <option value="1" <? if($model->buy_type==1){echo "selected";}?> selected="">分课时购买</option>
                          <option value="2"<? if($model->buy_type==2){echo "selected";}?> >整课购买</option>
                  </select>

              <span id='course-price'  <?php if($model->buy_type!=2){echo "hidden";}?>>
                课程原价格：<input <?php if($model->is_free==0 || empty($model->is_free) ){echo "disabled value='0'";}?>  class="inputclass1" name="CourseService[course_price]" style="width:100px" type="text" value="<?= $model->course_price ?>"  />
                课程折扣价格：<input class="inputclass1" id="course_sale_price"  <?php if($model->is_free==0 || empty($model->is_free) ){echo "disabled value='0'";}?>  name="CourseService[course_sale_price]" style="width:100px" type="text" value="<?= $model->course_sale_price ?>"  />
                苹果内购课程价格：
                  <input id="ios_price" hidden class="inputclass1" name="CourseService[course_price_ios]" style="width:100px" type="text" value="<?= $model->course_price_ios?$model->course_price_ios:0 ?>"   />
                  <a  <?php if($model->is_free==0 || empty($model->is_free) ){echo "hidden";}?>  class="normalbtn_l" id="iospricesel" href='javascript:;'>选择</a>
                  <span id="ios_price_info"><?= $model->course_price_ios?$model->course_price_ios:0 ?></span><span>元</span>
                  &nbsp;&nbsp;试学课id <input type="text" name="CourseService[learn_videoid]" id="learn_videoid" value="<?php echo $model->learn_videoid?>" style="width:80px" />
                </td>
            </tr>  
            
      <tr>
       <td style="width: 100px;">客服信息</td>
       <td>
           <?php
           if(!empty($model->customer_service)){
               $customer_service = json_decode($model->customer_service,1);
           }
           ?>
        QQ号:&nbsp;<input class="inputclass1"  name="qq" id="qq"   type="text" value="<?php if(isset($customer_service['qq'])){ echo $customer_service['qq'];} ?>"    style="width: 100px"/>&nbsp;&nbsp;
        QQ号名称:&nbsp;<input class="inputclass1"  name="qq_name" id="qq_name"   type="text" value="<?php if(isset($customer_service['qq_name'])){ echo $customer_service['qq_name'];} ?>"    style="width: 150px"/>&nbsp;&nbsp;
        QQ群号:&nbsp;<input class="inputclass1"  name="qq_qun" id="qq_qun"   type="text" value="<?php if(isset($customer_service['qq_qun'])){ echo $customer_service['qq_qun'];} ?>"    style="width: 100px"/>&nbsp;&nbsp;
        QQ群名称:&nbsp;<input class="inputclass1"  name="qq_qun_name" id="qq_qun_name"   type="text" value="<?php if(isset($customer_service['qq_qun_name'])){ echo $customer_service['qq_qun_name'];} ?>"    style="width: 150px"/>&nbsp;&nbsp;
       </td>
      </tr>
        <tr>
                <td style="width: 80px">抽奖活动id22</td>
                <td>
                    <input class="inputclass1" name="CourseService[gameid]" style="width:100px" type="text" value="<?= $model->gameid ?>"  nullmsg="" sucmsg="&nbsp;"/>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: red">没有抽奖活动不用添加抽奖id</span>
                </td>
            </tr>
          <tr>
      <td style="width: 80px">抽奖开始时间</td>
      <td>
        <input type="text" name="CourseService[game_start_time]" id="game_start_time" value="<?php 
        if($model->game_start_time){
          echo date('Y-m-d H:i',$model->game_start_time); 
        }
        ?>" class="inputclass1" readonly="readonly" style="width:255px">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: red">抽奖id添加后必须选择开始时间</span>
        <script type="text/javascript">
          Calendar.setup({
            weekNumbers: true,
            inputField : "game_start_time",
            trigger    : "game_start_time",
            dateFormat: "%Y-%m-%d %H:%M",
            showTime: true,
            minuteStep: 1,
            onSelect   : function() {this.hide();}
          });
          </script>         
      </td>
    </tr>
    <tr>
      <td style="width: 80px">抽奖截止时间</td>
      <td>
        <input type="text" name="CourseService[game_end_time]" id="game_end_time" value="<?php
        if($model->game_end_time){
         echo  date('Y-m-d H:i',$model->game_end_time); 
        }
        ?>" class="inputclass1" readonly="readonly" style="width:255px">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: red">抽奖id添加后必须选择结束时间</span>
        <script type="text/javascript">
          Calendar.setup({
            weekNumbers: true,
            inputField : "game_end_time",
            trigger    : "game_end_time",
            dateFormat: "%Y-%m-%d %H:%M",
            showTime: true,
            minuteStep: 1,
            onSelect   : function() {this.hide();}
          });
          </script>         
      </td>
    </tr>
    
          
            <tr>
                <td style="width: 80px">分享标题<span class='need'>*</span></td>
                <td>
                    <input class="inputclass1" name="CourseService[share_title]" style="width:30%" type="text" value="<?= $model->share_title ?>" datatype="*1-30" nullmsg="请输入标题，最多30个字！" sucmsg="&nbsp;"/>
                </td>
            </tr>
           
            <tr>
                <td style="width: 80px">分享描述<span class='need'>*</span></td>
                <td>
                  <textarea name="CourseService[share_desc]" style="width:98%;height:100px;" datatype="*0-500" errormsg="摘要最多500个字符！" sucmsg="&nbsp;" ><?= $model->share_desc ?></textarea>
                </td>
            </tr>

            <tr>
                   <td style="width: 80px;">分享图片<span class='need'>*</span></br>100*100px</td>
                   <td >
                     <input type ="hidden" id="thumb_share_img" name="CourseService[share_img]" value="<?= $model->share_img ?>" />     
                    <a name='athumb' id='athumb_share_img' data-name="share_img" thumbid='0' href='#'><img id='imgthumb_share_img' src="<? if($model->share_img){echo $model->share_img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='height:50px;' /></a>
                   </td>
                 </tr>
             <tr>
  
            <tr>
              <td>内容介绍<span class='need'>*</span></td>
                <td>
                  <script name='CourseService[content]' id="editor" type="text/plain" style="width:770px;height:500px;"></script>
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
  $("#buy_type").change(function(){
      if($(this).val()==1){
        $("#course-price").hide();
      }else{
        $("#course-price").show();
      }
  });

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
  //父窗口句柄
  var index = parent.layer.getFrameIndex(window.name);
  //显示富文本框内容
    var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
  ue.ready(function() {
    ue.setContent('<?= $model->content ?>');   
  });
    //选择批改老师
    $("#selbtn").click(function () {
            var content = '/course/teachersel';
            var title = '选择老师';
            content = content + '?uid='+ encodeURI($("#teacheruid").val());
            var search =layer.open({
                type: 2,
                title: title,
                maxmin: true,
                area : ['700px' , '600px'],
                content: content
              });
            layer.full(search);
      });
      
        $("#f_catalog_id").change(function () {
             var f_catalog_id = $(this).val();
             var url = '/course/select_menu';
             var data = {
                 f_catalog_id : f_catalog_id
             }
          $.post(url,data,function(m){
               $("#s_catalog_id option").remove();
                if(m==null){
                    m='<option value=0>请选择</option>';
                }
               $("#s_catalog_id").append(m);
          },'json');
         });
      
//    $("#f_catalog_id").click(function() {
//           var key=$("#f_catalog_id  option:selected").attr("key");
//           var catalog_json=<?//= json_encode($catalog)?>;
//           var s_catalog_id="<?//= $model->s_catalog_id?>";
//           var content='';
//           var s_catalogs=catalog_json.imgmgr_level_2[key];
//           for(var item in s_catalogs) {
//                  if(s_catalog_id==item){
//                    content+="<option selected value="+item+">"+s_catalogs[item]+"</option>";
//                  }else{
//                    content+="<option value="+item+">"+s_catalogs[item]+"</option>";
//                  }
//              $("#s_catalog_id").html(content);
//          }    
//      });    

    //上传图片
    $("a[name=athumb]").click(function () {
                var content = '/course/thumbupload';
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
    $("#is_free").change(function(){
        if($(this).val()==0){
          $("#iospricesel").hide();
          $("[name='CourseService[course_sale_price]']").val(0);
          $("[name='CourseService[course_price]']").val(0);
          $("[name='CourseService[course_price_ios]']").val(0);
          $("#ios_price_info").html(0);
          $("[name='CourseService[course_sale_price]']").attr("disabled",true);
          $("[name='CourseService[course_price]']").attr("disabled",true);
        }else{
          $("#iospricesel").show();
          $("[name='CourseService[course_sale_price]']").attr("disabled",false);
          $("[name='CourseService[course_price]']").attr("disabled",false);
        }
    })
    //保存按钮
    $("#asave").click(function () {
        //检查富文本框
        var html = $.trim(ue.getContent());
        
        var teacheruid=$("#teacheruid").val();
        var f_catalog_id=$("#f_catalog_id").val();
        var s_catalog_id=$("#s_catalog_id").val();
        var thumb_share_img=$("#thumb_share_img").val();
        var thumb_thumb_url=$("#thumb_thumb_url").val();
        var course_bounty_fee_ios=parseFloat($("#course_bounty_fee_ios").val());
        var course_sale_price=parseFloat($("#course_sale_price").val());
        var course_bounty_fee=parseFloat($("#course_bounty_fee").val());
        var ios_price=parseFloat($("#ios_price").val());
        var learn_videoid = $("#learn_videoid").val();
        if(course_bounty_fee_ios>course_sale_price || course_bounty_fee>course_sale_price ||course_bounty_fee_ios>ios_price || course_bounty_fee>ios_price){
           layer.msg('佣金价格不能大于课程价格', {icon: 2});
            return false;
        }
        if(teacheruid == null || teacheruid == undefined || teacheruid == ''){
          layer.msg('请选择老师', {icon: 2});
          return false;
        }
        if(f_catalog_id == null || f_catalog_id == undefined || f_catalog_id == ''){
          layer.msg('请选择一级分类', {icon: 2});
          return false;
        }
        if(s_catalog_id == null || s_catalog_id == undefined || s_catalog_id == ''){
          layer.msg('请选择二级分类', {icon: 2});
          return false;
        }
        if(thumb_thumb_url == null || thumb_thumb_url == undefined || thumb_thumb_url == ''){
          layer.msg('请上传课程图片', {icon: 2});
          return false;
        }

        if(html ==''){
          layer.msg('请输入文章内容', {icon: 2});
          return false;
        }
        if(thumb_share_img == null || thumb_share_img == undefined || thumb_share_img == ''){
          layer.msg('请上传分享图片', {icon: 2});
          return false;
        }
        //如果填写试学课id  课程付费类型必须为付费 、课程价格必须为整课购买
        if(learn_videoid>0){
            if($("#is_free").val() !=1){
                 layer.msg('课程付费类型必须为付费!', {icon: 2});
                 return false;
            }
            if($("#buy_type").val() !=2){
                 layer.msg('课程价格必须为整课购买!', {icon: 2});
                 return false;
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
</script>