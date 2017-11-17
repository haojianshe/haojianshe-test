<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>美院帮</title>
<?php
use yii\widgets\ActiveForm;
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>
<link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
<!-- 图片浏览 引入开始-->
<script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
<!--鼠标控制滚动-->
<script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- 图片浏览 引入结束-->
<script type="text/javascript" src="/static/js/layer/layer.js"></script>  
<script type="text/javascript" src="/static/js/layer/extend/layer.ext.js"></script>  
<style type="text/css">
  *{
    padding:0px;
    margin:0px;
  }   
  body{       
    background-color: #ECECEC;
   /*  font-size:30px; */
    text-align:center;
  }
  .container{
    width:100%;
  }
  input{
   /*  font-size:30px; */
   /*  line-height:30px; */
  }
  .container{
    text-align:center;
  }
  .form-group{
    text-align:center;
  }
  #uploadform-file{
   /*  padding-left:300px; */
  }
  #w0{
    background-color:#D8D8D8;
    border-radius:4px;
    border:solid 1px #CCCBCB;
   /*  padding-top:10px; */
    /* padding-bottom:20px; */
  }
  .impot_btn{
    /* font-size:25px; */
  }
  .finished{
    color:red;
  }
  /**-----------弹层样式调整**/
  .layui-layer-prompt .layui-layer-input{
    width:100%;
  }
  .layui-layer-btn a{
    font-size:16px;
    line-height:22px;
    height:22px;
  }
  .layui-layer-btn .layui-layer-btn0{
    background-color:#454B50;
    border-color:#909294;
  }
  /**-----------弹层样式调整**/
</style>
<?= $form->field($model, 'file')->fileInput()->label('上传Excel数据前,要将帖子图片上传到阿里云服务器') ?>
    <button class="btn impot_btn">导入Excel 数据</button>
<?php ActiveForm::end(); ?>
<table class="table table-condensed tweetlist">
   <caption><span class="table_title">帖子列表 共<?= count($data) ?>条 数据</span>
    <button class="btn btn-primary btn-sm btn_upload">开始处理</button>
    <button class="btn btn-primary btn-sm btn_hidecomment">收起评论</button>
    <button style="display: none;" class="btn btn-primary btn-sm btn_showcomment" >显示评论</button>
    </caption>
   <thead>
      <tr>
         <th>内容：</th>
         <th>图片地址</th>
         <th>一级分类</th>
         <th>二级分类</th>
         <th>标签</th>    
         <th>处理状态</th>      
      </tr>
   </thead>
   <tbody>
     
<?php    
    $count = count($data);
    for($i=2;$i<=$count;$i++)
    {   $keys=array_keys($data[$i]); 
        ?>
        <tr class="tweet_info">
         <td class="tweet_content"><?=$data[$i][$keys[0]]?></td>
         <td class="tweet_img">
         <?php $img_arr=explode(",",$data[$i][$keys[1]]);
              foreach ($img_arr as $key => $value) {
          ?>
            <a id="example1" title="<?=$data[$i][$keys[0]]?>" rel="group" href="<?=$folder_name?><?=$value?>">
                <img style="height:50px;width:50px; margin-left:3px;margin-top:3px;" name="prew_resource" src="<?=$folder_name?><?=$value?>@200h_2o" />
            </a>
          <?php
              }
          ?>
            
         </td>
         <td hidden class="tweet_imgurl">
            <?php
            $img_arr_html='';
            $img_arr_html_arr=explode(",", $data[$i][$keys[1]]);
            foreach ($img_arr_html_arr as $key => $value) {
                  $img_arr_html.=$folder_name.$value.',';
            }
            $img_arr_html=substr($img_arr_html, 0,strlen($img_arr_html)-1);
            ?>
              <?=$img_arr_html?>
            <?php
            ?>
         </td>
         <td class="tweet_fcatalog"><?=$data[$i][$keys[2]]?></td>
         <td class="tweet_scatalog"><?=$data[$i][$keys[3]]?></td>
         <td class="tweet_tags"><?=$data[$i][$keys[4]]?></td>
         <td class="finished">未处理</td>
         <td hidden class="tweet_comment"><?php
         $comment_num=0;
        for($j=5;$j<count($keys);$j++){          
             if(!empty($data[$i][$keys[$j]])){
                $comment_num=$comment_num+1;
               ?><?=$data[$i][$keys[$j]].'||||'?>
               <?php 
                }
               } ?></td>
          <td hidden class="comment_num"><?=$comment_num?></td>
      </tr>
      <tr> 
      <td class="comment" colspan="6">
      <?php
        for($j=5;$j<count($keys);$j++){
             if(!empty($data[$i][$keys[$j]])){
               ?>
                <?=$data[$i][$keys[$j]].'</br>'?>
                <?php
             }
        }
      ?>
      </td>
      </tr>
<?php   }   ?>
   </tbody>
</table>


<script type="text/javascript">


$(document).ready(function() { 
      /*----------------输入图片地址弹层*/
      if(!GetQueryString("folder_name")){
            layer.prompt({
                title: '输入上传服务器图片存储地址:',
                closeBtn: 0,
                area: '500px',
                formType: 0 //prompt风格，支持0-2
            }, function(pass){
                window.location.href="/tweet/import_excel?folder_name="+pass;
            });
      }
      $(".layui-layer-input").val("http://img.meiyuanbang.com/tweet/");
      $(".layui-layer-btn1").hide();
      /*----------------输入图片地址弹层*/
}); 

/*获取url参数方法*/
function GetQueryString(name) {
   var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)","i");
   var r = window.location.search.substr(1).match(reg);
   if (r!=null) return (r[2]); return null;
}

/*--------全部评论显示隐藏*/
$(".btn_hidecomment").click(function(){
    $(".comment").hide();
    $(".btn_showcomment").show();
    $(".btn_hidecomment").hide();
});

$(".btn_showcomment").click(function(){
    $(".comment").show();
    $(".btn_showcomment").hide();
    $(".btn_hidecomment").show();
});
/*--------全部评论显示隐藏*/

/*每条评论点击隐藏*/
$(".tweet_info").click(function(){
    if($(this).next("tr").find('td.comment').is(":hidden")){
        $(this).next("tr").find('td.comment').show();
    }else{
        $(this).next("tr").find('td.comment').hide();
    }  
});
/*处理数据*/
 $(".btn_upload").click(function(){
    $(".tweetlist tbody .tweet_info").each(function(trindex,tritem){//遍历每一行
        var stringurl="<?=Yii::$app->params['apisiteurl']?>";
         $.ajax({
             type: "post",
             url: stringurl+"/v1_2/thread/temptweetupload",
             async:false,
             data: {
                content:$(tritem).find(".tweet_content").html(), 
                comment:$(tritem).find(".tweet_comment").html(),
                fcatalog:$(tritem).find(".tweet_fcatalog").html(), 
                scatalog:$(tritem).find(".tweet_scatalog").html(), 
                imgurl:$(tritem).find(".tweet_imgurl").html(), 
                tags:$(tritem).find(".tweet_tags").html(),
                comment_num:$(tritem).find(".comment_num").html(),                
             },
             dataType: "jsonp",
             success: function(data){
                //更改处理状态
                if(data.errno==0 && data.data.msg==''){
                   $(tritem).find(".finished").html("已处理");
                    $(tritem).find(".finished").css("color","green");
                }else{
                   $(tritem).find(".finished").html(data.data.msg);
                   $(tritem).find(".finished").css("color","red");
               }
             }
          });
    });
  });  
</script>

<!-- 图片浏览 开始-->
<script language="javascript" type="text/javascript"> 
  $(document).ready(function() {
     $("a#example1").fancybox({
      type:'image',
      afterLoad : function() {
          this.title = '图片数：' + (this.index + 1) + '/' + this.group.length + (this.title ? ' - ' + this.title : '');
        },
        loop:false,
      padding: 2,
      helpers : {
          title : {
            type : 'inside'
          }
      }
     });
  });
</script>
<!-- 图片浏览 结束 -->
