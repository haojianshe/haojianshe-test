<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上传视频</title>
    <link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
    <script src="/static/js/aliyun/aliyun-sdk.min.js"></script>
    <script src="/static/js/aliyun/vod-sdk-upload.min.js"></script>
</head>
<body>
<div>
<form action="">
    <table  class="normaltable">
        <tbody>
            <tr><td>选择文件</td><td><input type="file" name="file" id="files" /></td></tr>
            <tr><td>上传进度：</td><td><progress id="funding" value="0" max="100"></progress><span class="progressval"></span></td></tr>
            <tr><td>保存</td><td><span class="normalbtn_l"><a  id="file_upload" href="javascript:;">上传保存</a></span></td></tr>
        </tbody>
    </table>
</form>
 </div>
 <!-- 用来获取视频时长 -->
 <video style="display:none;" controls="controls" id="ivideo" oncanplaythrough="getDuration(this)"></video>  

<script>
function getDuration(ele) {  
    //时长
    //console.log(Math.floor(ele.duration));
    parent.$('#video_length').val(Math.floor(ele.duration));  
}  
    var d = new Date();
    var data_floder= d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
    var filename=new Date().getTime()+"_"+Math.round(Math.random()*10000);
    var index = parent.layer.getFrameIndex(window.name);
    var uploader = new VODUpload({
        // 文件上传失败
        'onUploadFailed': function (fileName, code, message) {
            alert("上传文件失败");
            $("#file_upload").attr('disabled',true);
            $("#files").attr('disabled',true);
            console.log("onUploadFailed: " + fileName + code + "," + message);
        },
        // 文件上传完成
        'onUploadSucceed': function (fileName) {
            console.log("onUploadSucceed: " + fileName);
            var newurl = "<?=Yii::$app->params['videosourceurl']?>"+"video/"+data_floder+"/"+filename+".mp4";
            parent.$("#imgthumb").attr("src",newurl+'?d='+ Date.parse(new Date()));
            parent.$('#video').val(newurl);
            parent.$('#filename').val(filename);
            parent.$("form").submit();
            parent.layer.close(index);
        },
        // 文件上传进度
        'onUploadProgress': function (fileName, totalSize, uploadedSize) {
           $("#funding")[0].value=Math.ceil(uploadedSize * 100 / totalSize);
           $(".progressval").html(Math.ceil(uploadedSize * 100 / totalSize)+"%");
            console.log("file:" + fileName + ", " + totalSize, uploadedSize, "percent:", Math.ceil(uploadedSize * 100 / totalSize));
        },
        // token超时
        'onUploadTokenExpired': function (callback) {
            alert("超时");
            $("#file_upload").attr('disabled',true);
            $("#files").attr('disabled',true);
            console.log("onUploadTokenExpired");
        }
    });

    uploader.init("0uMRHb0hY555pkxQ", "oKRwSn6WFVQL8U6pupO2wswCiA38La");
        $("#file_upload").click(function(){
		//获取视频时长
        var url = URL.createObjectURL($("#files")[0].files[0]);  
        document.getElementById("ivideo").src=url;  
		//设置视频大小
         parent.$('#video_size').val($("#files")[0].files[0].size);

        if($("#files")[0].files[0]){
            $(this).attr('disabled',true);
            $("#files").attr('disabled',true);
        }else{
            alert("请选择视频文件");
            return ;
        }
       
        uploader.addFile($("#files")[0].files[0], 'http://oss-cn-beijing.aliyuncs.com', 'myb-media-in',"/video/"+data_floder+"/"+filename+'.mp4' );
        uploader.startUpload();

    });

    /*document.getElementById("files")
            .addEventListener('change', function (event) {
                for(var i=0; i<event.target.files.length; i++) {
                     console.log(event.target);
                    //event.target.files[i]     event.target.files[i].name
                        uploader.addFile(event.target.files[i], 'http://oss-cn-beijing.aliyuncs.com', 'myb-media-in','test1.mp4' );
                }

                uploader.startUpload();
            });*/
</script>
</body>
</html>
