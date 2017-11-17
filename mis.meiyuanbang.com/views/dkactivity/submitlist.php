  <?php
  use common\widgets\MyLinkPager;
  ?>
  <link rel="stylesheet" type="text/css" href='/static/css/pager.css'>

  <!-- 图片浏览 引入开始-->
  <script type="text/javascript" src="/static/js/fancyBox/source/jquery.fancybox.js"></script>
  <link rel="stylesheet" type="text/css" href="/static/js/fancyBox/source/jquery.fancybox.css" media="screen" />
  <!--鼠标控制滚动-->
  <script type="text/javascript" src="/static/js/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
  <!-- 图片浏览 引入结束-->
  
  <table cellspacing="0" cellpadding="0" class="content_list">
      <!--标题  -->
      <thead>
        <tr class="operate">
          <th colspan="6" >
            共有<?= $pages->totalCount ?>条记录
          </th>
         <th colspan="2" style="text-align: right;">
            <a class="button " onclick="updateZanAll()">更改赞数</a>
          </th>
        </tr>

        <tr class="tb_header">
        <th >排行</th>
          <th >编号</th>
          <th >用户</th>
          <th >分类</th>
          <th >内容</th>
          <th >图片</th>
          <th >赞数</th>
          <th >创建时间</th>
          <th >操作</th>
        </tr>
      </thead>
      <!-- 列表 -->
      <? foreach ($models as $key=>$model) { ?>
        <tr class="tb_list">
          <td><input class="ids" type="checkbox" data-id="<?= $model['dkcorrectid'] ?>" name="ids">&nbsp;&nbsp;<script type="text/javascript">
            function getQueryString(name)
            {  
                 var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
                  var r = window.location.search.substr(1).match(reg);
                  if(r!=null)return  unescape(r[2]); return null;
            }
            /**/
            document.write((getQueryString('page')-1)*getQueryString('per-page')+<?=$key?>+1);
          </script></td>
          <td><?= $model['dkcorrectid'] ?></td>
            
          <td>
          <?= "<img width='40px;' src='".json_decode($model['avatar'])->img->n->url ."' >  ". $model['sname'] ?>
          </td>

          <td><?= $model['f_catalog'] ?><?= $model['s_catalog'] ?></td>
          <td><?= $model['content'] ?></td>
        <td>
            <a id="example1" title="<?= $model['content'] ?>" rel="group" href="<?= json_decode($model['img'])->n->url  ?>">
                <img style="height:50px;width:50px; margin-left:3px;margin-top:3px;" name="prew_resource" src="<?= json_decode($model['img'])->n->url  ?>" />
              </a>
          </td>
          <td><?= $model['zan_num'] ?></td>
          <td><?= date('Y-m-d H:i',$model['ctime'])  ?></td>
           <td><a onclick="edit(<?=$model['dkcorrectid']?>)">更改赞数</a>&nbsp;
           <a name='adel' onclick='del(<?=$model['dkcorrectid']?>)' href='#'>删除</a></td>
           
         
        </tr>
      <?}?>

     <!-- 分页 -->
     <tr class="operate">
	      <td colspan="8">
  			<div class="cuspages right">
  			<?= MyLinkPager::widget(['pagination' => $pages]); ?>
  			</div>      
	      </td>
      </tr>
  </table>



<!-- 页面操作逻辑  开始-->
<script type="text/javascript">


//删除
        function del(dkcorrectid){
            layer.confirm('是否删除？', {
              btn: ['删除','否'] //按钮
            }, function(){
              $.ajax({
                type: "post",
                dataType: "json",
                url: "/dkactivity/delcorrect",
                    data: "dkcorrectid=" + dkcorrectid,//要发送的数据                    
                    success: function (data) {
                      if (data.errno == 0) {
                        window.location.reload();
                      }
                      else {
                        layer.msg(data.msg,{icon: 2});
                      }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                      layer.msg("访问出错",{icon: 2});
                    }
                  });     
            }, function(){
             
            });
        }
function updateZanAll(){
    var ids_arr=[];
    for (var i = 0; i < $(".ids:checked").length; i++) {
      ids_arr[i]=$($(".ids:checked")[i]).data("id");
    }
    var ids=ids_arr.join(',')
    if(ids){
         edit(ids);
    }else{
      layer.msg("请选择改赞的记录！！！",{icon: 2});
    }
}
//更改赞数
  function edit(dkcorrectid){
    var content = '/dkactivity/editzan';
    var title = '更改赞数';
    if(dkcorrectid){
      content = content + '?dkcorrectid=' + dkcorrectid; 
      title = '编辑--编号:'+ dkcorrectid;
    }
    top.parent.layer.open({
          type: 2,
          title: title,
          maxmin: false,
          shadeClose: false, //点击遮罩关闭层
          area : ['50%' , '500px'],
          content: content
      });
  }


</script>
<!-- 页面操作逻辑 结束-->



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

