  <?php
  use yii\helpers\Html;
  use yii\bootstrap\ActiveForm;
  use common\service\DictdataService;
  ?>
   <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
  <script type="text/javascript" src="/static/js/validform_v5.3.2_min.js"></script>
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css">
  <link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css">
  <script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
  <script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>
  
  <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.js?v=201605191725"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/alivideo/alivideo.js"></script>  

<!--添加乐视视频按钮-->
<script type="text/javascript" charset="utf-8" src="/ueditor/dialogs/letv/levedio.js"></script> 


  <div class="normaltable">
   <?php $form = ActiveForm::begin(['id' => 'roleform']); ?>    
      <table style="width: 100%">
    <tbody>
      <?php if(isset($model->liveid)){?>
      <tr>
       <td style="width: 100px;">直播编号</td>
       <td style="width:600px;">
         <input type ="hidden" name='isedit' value='1' />
         <input type ="hidden" name='LiveService[liveid]' value='<?php echo $model->liveid;?>' />
         <span><?= $model->liveid ?></span>
       </td>
     </tr>
     <?php } ?>
      <tr>
       <td style="width: 100px;">标题<span class='need'></span><span class='need'>*</span></td>
       <td style="width:600px;">
           <input class="inputclass1"  name="live_title" id="live_title"   type="text" value="<?= $model->live_title ?>"   style="width: 720px" datatype="*1-100" nullmsg="请输入标题，最多100个字！" sucmsg="&nbsp;"/>
       </td>
     </tr>
     <tr> 
      <td width="80">分类<span class='need'>*</span></td>
      <td>
       <select name="f_catalog_id" id="f_catalog_id">
         <?
         //if(empty($model->f_catalog_id)){ ?>
         <option value="" key="">一级分类</option>
         <?//}?>
         <? foreach ($catalog['imgmgr_level_1'] as $key => $value) {?>            
         <option value="<?=$key?>" key="<?=$key?>" <?if ($key==$model->f_catalog_id) {?>selected<?} ?>>
          <?=$value?>
        </option>
        <?}?>
      </select>
      <select name="s_catalog_id" id="s_catalog_id">
        <?
         $s_catalog_id_name='';
        if($model->f_catalog_id){
            $s_catalog_id_name=DictdataService::getTweetSubTypeById($model->f_catalog_id,$model->s_catalog_id);
        }
        if(empty($s_catalog_id_name)){ ?>
        <option value="" selected="">二级分类</option>
        <?}else{?>
        <option value="<?= $model->s_catalog_id?>" selected><?= $s_catalog_id_name ?></option>
        <?}?>
      </select>
    </td>
  </tr>
      <tr>
         <td width="80">播放类型<span class='need'>*</span> </td>
           <td>
             <select name="playtype" id="playtype">
                 <option  value="1" <?php  if($model['playtype']==1){ echo 'selected=selected';}?> >m3u8</option>
                 <option  value="2" <?php  if($model['playtype']==2){ echo 'selected=selected';}?> >rtmp</option>
             </select>
             </td>
      </tr> 
          <tr>
               <td >直播讲师<span class="need">*</span></td>
               <td>
                 <input type ="hidden" class="inputclass1" id="teacheruid" name="teacheruid" style="width:80px" type="text" value="<?= $model->teacheruid ?>" />
                  <div>  
                      
                      <span class="userinfo" style="margin: 3px;padding: 3px;"><?php echo isset($usersinfo[0]['sname'])?$usersinfo[0]['sname']:"";?></span>
                      <span class="normalbtn_l"><a id="selbtn" href="#">选择</a></span>
                  </div>
               </td>
             </tr> 
          <tr>
          <td style="width: 100px;">讲师介绍<span class='need'>*</span></td>
          <td style="width:400px;">
           <textarea name="teacher_desc" style="margin: 0px -5px 0px 1px; height: 98px; width: 821px;" class="inputclass1" ><?= $model->teacher_desc ?></textarea>
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
       <td style="width: 100px;">广告id</td>
       <td style="width:600px;">
           <input class="inputclass1"  name="advid" id="advid"   type="text" value="<?= $model->advid ?>"    style="width: 120px"/>
       </td>
     </tr>


    <tr>
      <td style="width: 80px">开始时间<span class='need'>*</span></td>
      <td>
        <input type="text" name="start_time" id="start_time" value="<?php 
        if($model['start_time']){
          echo date('Y-m-d H:i:s',$model['start_time']); 
        }
        ?>" class="inputclass1" readonly="readonly" style="width:255px">&nbsp;
        <script type="text/javascript">
          Calendar.setup({
            weekNumbers: true,
            inputField : "start_time",
            trigger    : "start_time",
            dateFormat: "%Y-%m-%d %H:%M:%S",
            showTime: true,
            minuteStep: 1,
            onSelect   : function() {this.hide();}
          });
          </script>         
      </td>
    </tr>
    <tr>
      <td style="width: 80px">预计截止时间<span class='need'>*</span></td>
      <td>
        <input type="text" name="end_time" id="end_time" value="<?php
        if($model['end_time']){
         echo  date('Y-m-d H:i:s',$model['end_time']); 
        }
        ?>" class="inputclass1" readonly="readonly" style="width:255px">&nbsp;
        <script type="text/javascript">
          Calendar.setup({
            weekNumbers: true,
            inputField : "end_time",
            trigger    : "end_time",
            dateFormat: "%Y-%m-%d %H:%M:%S",
            showTime: true,
            minuteStep: 1,
            onSelect   : function() {this.hide();}
          });
          </script>         
      </td>
    </tr>
       <tr>
       <td style="width: 100px;">浏览基数<span class='need'>*</span></td>
       <td style="width:600px;">
           <input class="inputclass1"  name="hits_basic" id="hits_basic"   type="text" value="<?= $model->hits_basic ?>"  datatype="/^[0-9]+([.]{1}[0-9]+){0,1}$/"  errormsg="必须为数字" sucmsg="&nbsp;"   style="width: 120px"/>
       </td>
     </tr>
    
<tr>
   <td>预告封面<span class='need'>*</span></td>
   <td>
       <input type ="hidden" id="thumb" name="live_thumb_url" value="<?= $model->live_thumb_url ?>" /> 
       <input type ="hidden" id="filename" value="" />     
       <a name='athumb' id='athumb' thumbid='0' href='#'>
           <img id='imgthumb' src="<? if($model->live_thumb_url){echo $model->live_thumb_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
       </a>
   </td>
</tr>
<tr>
   <td>回放封面<span class='need'>*</span></td>
   <td>
       <input type ="hidden" id="thumb11" name="recording_thumb_url" value="<?= $model->recording_thumb_url ?>" /> 
        <input type ="hidden" id="filename" value="" />     
       <a name='athumb11' id='athumb11' thumbid='0' href='#'>
       <img id='imgthumb11' src="<? if($model->recording_thumb_url){echo $model->recording_thumb_url;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
       </a>
   </td>
</tr>
    <tr>
       <td style="width: 100px;">直播价格<span class='need'>*</span></td>
       <td style="width:600px;">
 <input class="inputclass1" name="live_price"  type="text" value="<?= $model->live_price ?>"   datatype="/^[0-9]+([.]{1}[0-9]+){0,1}$/"  errormsg="必须为数字" sucmsg="&nbsp;"   style="width: 120px"/>
       </td>
     </tr> 
      <tr>
            <td>直播IOS售价</td>
            <td>
                <input id="live_ios_price" hidden class="inputclass1" name="live_ios_price" style="width:100px" type="text" value="<?= $model->live_ios_price?$model->live_ios_price:0 ?>"   />
                <span class="normalbtn_l"><a id="iospricesel" href='javascript:;'>选择</a></span>
                <span id="ios_price_info"><?= $model->live_ios_price?$model->live_ios_price:0 ?></span>元
            </td>
       </tr>
     
     <tr>
       <td style="width: 100px;">回放价格<span class='need'>*</span></td>
       <td style="width:600px;">
<input class="inputclass1" name="recording_price"  type="text" value="<?= $model->recording_price ?>" datatype="/^[0-9]+([.]{1}[0-9]+){0,1}$/"  errormsg="必须为数字" sucmsg="&nbsp;"     style="width: 120px"/>
       </td>
     </tr>
       <tr>
            <td>录播IOS售价</td>
            <td>
                <input id="recording_ios_price" hidden class="inputclass1" name="recording_ios_price" style="width:100px" type="text" value="<?= $model->recording_ios_price?$model->recording_ios_price:0 ?>"   />
                <span class="normalbtn_l"><a id="recordingiosprice" href='javascript:;'>选择</a></span>
                <span id="recording_price_info"><?= $model->recording_ios_price?$model->recording_ios_price:0 ?></span>元
            </td>
       </tr>
      
       <tr>
         <td width="80">管理员<span class='need'>*</span></td>
           <td>
               <input class="inputclass1" name="adminuid"  type="text" value="<?= $model->adminuid ?>"  style="width: 520px" datatype="/^[0-9]+([.]{1}[0-9]+){0,1}$/"  errormsg="必须为数字" sucmsg="&nbsp;"     style="width: 120px"/>
 
             </td>
      </tr> 
      <tr>
        <td>分享主图<span class='need'>*</span></td>
        <td>
            <input type ="hidden" id="thumb1" name="share_img" value="<?= $model->share_img ?>" />   
             <input type ="hidden" id="filename" value="" />     
            <a name='athumb1' id='athumb1' thumbid='0' href='#'>
                <img id='imgthumb1' src="<? if($model->share_img){echo $model->share_img;}else echo '/ueditor/dialogs/image/images/image.png'; ?>" style='padding-left:15px;height:100px;' />
            </a>
        </td>
     </tr>
     <tr>
       <td style="width: 100px;">分享标题<span class='need'>*</span></td>
       <td style="width:600px;">
           <input class="inputclass1" name="share_title"  type="text" value="<?= $model->share_title ?>"     style="width:520px"/>
       </td>
     </tr> 
      <tr>
          <td style="width: 100px;">分享描述<span class='need'>*</span></td>
          <td style="width:400px;">
           <textarea name="share_desc" style="margin: 0px -5px 0px 1px; height: 98px; width: 821px;" class="inputclass1" ><?= $model->share_desc ?></textarea>
          </td>
      </tr>
         <tr>
            <td>内容</td>
            <td>
                <script name='live_content' id="editor" type="text/plain" style="width:770px;height:500px;"></script>
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
<input type="hidden" name="teacherid" value="<?=$model['teacheruid']?>" />
</table> 
<?php ActiveForm::end(); ?> 
</div>
<script type="text/javascript">
     //父窗口句柄
  		var index = parent.layer.getFrameIndex(window.name);
  		//显示富文本框内容
  		  var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
  		ue.ready(function() {
               ue.setContent('<?= $model['live_content']?>');
               });
               
       //选择直播价格
    $("#iospricesel").click(function () {
            var content = '/live/ios_price_sel';
            var title = '选择ios价格';
            content = content + '?price='+ encodeURI($("#live_ios_price").val());
            var search =layer.open({
                type: 2,
                title: title,
                maxmin: true,
                area : ['700px' , '600px'],
                content: content
              });
            layer.full(search);
      });
      
          //选择录播价格
    $("#recordingiosprice").click(function () {
            var content = '/live/recording_ios_price_sel';
            var title = '选择ios价格';
            content = content + '?price='+ encodeURI($("#recording_ios_price").val());
            var search =layer.open({
                type: 2,
                title: title,
                maxmin: true,
                area : ['700px' , '600px'],
                content: content
              });
            layer.full(search);
      });
      
//预告封面
      $("a[name=athumb").click(function () {
              var content = '/video/thumbupload';
              var title = '编辑缩略图';
              content = content + '?url='+ encodeURI($('#thumb').val());
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['400px' , '300px'],
            content: content
              });
              return false;
          });
//回放封面
      $("a[name=athumb11").click(function () {
              var content = '/live/ccthumbupload';
              var title = '编辑缩略图111';
              content = content + '?url='+ encodeURI($('#thumb11').val());
              layer.open({
                  type: 2,
                  title: title,
                  maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area : ['400px' , '300px'],
            content: content
              });
              return false;
          });
      //分享主图
      $("a[name=athumb1").click(function () {
              var content = '/live/cthumbupload';
              var title = '编辑缩略图11';
              content = content + '?url='+ encodeURI($('#thumb1').val())+"&imgclass=imgthumb1&valclass=thumb1";
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


        $("#f_catalog_id").change(function () {
             var f_catalog_id = $(this).val();
             var url = '/live/select_menu';
             var data = {
                 f_catalog_id : f_catalog_id
             }
          $.post(url,data,function(m){
               $("#s_catalog_id option").remove();
              $("#s_catalog_id").append(m);
          },'json');
         });
 
    $(function () {
      //保存按钮
      $("#asave").click(function () {
       var live_title = $("#live_title").val();
       var f_catalog_id = $("#f_catalog_id").val();
       var s_catalog_id = $("#s_catalog_id").val();
       var publish_time = $("#publish_time").val();
       var start_time = $("#start_time").val();
       var end_time = $("#end_time").val();
//       alert(f_catalog_id);
//       return;
       //  || f_catalog_id=='' || s_catalog_id=='' || publish_time==''
       if(live_title==''){
                layer.msg('直播标题不能为空！', {icon: 2});
                return false;
        }
         if(f_catalog_id==''){
                layer.msg('一级分类不能为空！', {icon: 2});
                return false;
        }
        
         if(s_catalog_id==''){
                layer.msg('二级分类不能为空！', {icon: 2});
                return false;
        }
       
         if(publish_time==''){
                layer.msg('发布时间不能为空！', {icon: 2});
                return false;
        }
     
         if(start_time==''){
                layer.msg('开始时间不能为空！', {icon: 2});
                return false;
        }
         if(end_time==''){
                layer.msg('结束时间不能为空！', {icon: 2});
                return false;
        }
        
       if(start_time>=end_time){
            layer.msg('开始时间不能大于结束时间！', {icon: 2});
            return false;
        }
        
        
        if(!$("#thumb").val()){
          alert("请上传封面图");
          return ;
        }
        
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
    
    
    //选择批改老师
    $("#selbtn").click(function () {
            var content = '/live/teachersel';
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

  </script>
  