<table class="content_list">
  <thead>
    <tr >
      <td colspan="2" class="tbTitle">清除缓存</td>
    </tr>
  </thead>
  <tr>
    <td width="100" ></td>
    <td >
    	<input type="button" id="btnall" value="重置全部缓存" class="button"/>
    </td>
  </tr>
</table>
<script>
//全部重置按钮绑定事件
$("#btnall").click(function () {
    layer.confirm('确定重置所有用缓存吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        //确定进行删除
        $.ajax({
            type: "post",
            dataType: "json",
            url: "cache/all",                             
            success: function (data) {
                if (data.errno == 0) {
                	layer.msg('操作成功！',{icon: 1});
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
        //取消
    });
    return false;
});
</script>