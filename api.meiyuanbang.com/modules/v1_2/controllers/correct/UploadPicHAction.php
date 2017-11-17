<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use common\service\AliOssService;
use common\models\myb\Resource;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\CommonFuncService;

/**
 * 上传图片
 */
class UploadPicHAction extends ApiBaseAction
{    
    private $ossobject = 'correct/img';
    public function run()
    {  
        $request = Yii::$app->request;
        if(!$request->isPost){
            die('非法请求!');
        }
        $model=new Resource();
        $model->description=$request->post('description');
        $model->resource_type=0;

        //处理上传的图片
        if (!isset($_FILES['img_file'])) {
            die('未选择图片!');
        }       
        $file = $_FILES['img_file'];
        //检查图片大小和类型
        if($file['size']>10485760){
            die('图片太大');
        }
       /* //大于1M图片压缩
        if($file['size']>1024*1024){
            $fp = fopen($_FILES['img_file']['tmp_name'], "r");
            $imgstring=stream_get_contents($fp);
            fclose($fp);
            $resizeimage = CommonFuncService::reSizeImg($imgstring, getimagesize($_FILES['img_file']['tmp_name'])[1], getimagesize($_FILES['img_file']['tmp_name'])[0], $_FILES['img_file']['tmp_name'],70);

        }
       */
        $fileext = AliOssService::getFileExt($file['name']) ;
        if(!in_array( $fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp"]))
        {
            die('图片格式错误');
        }
        //开始处理图片
        $filename = AliOssService::getFileName($fileext);
        $ret = AliOssService::picUpload($this->ossobject, $filename, $file);
        if ($ret == false) {
        	$this->errorInfo('上传失败');
        	return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        }
        $img_infohw=AliOssService::getFileHW(Yii::$app->params['ossurl'] . $ret);
        //判断宽高
        if(!$img_infohw['height'] || !$img_infohw['width']){
        	$this->errorInfo('图片上传失败');
        	return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        }
        $img_info['n']['h']=$img_infohw['height'];
        $img_info['n']['w']=$img_infohw['width'];
        $img_info['n']['url']=Yii::$app->params['ossurl'] . $ret;
        //上传成功,写库
        $model->img=json_encode($img_info);
        $model->save();
        $data['img']=json_decode($model->img);
        $data['description']=$model->description;
        $data['rid']= $model->attributes['rid'];
        $url=Yii::$app->params['sharehost'];
        die("<script>    
            document.domain = '".substr($url,strpos($url,".")+1,strlen($url))."';
           top.uploadr('". $data['rid']."');
           top.layerClose();
           </script>");
     //   $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
    
    /**
     * 上传图片错误时，客户端提示并且关闭层
     * @param unknown $msg
     */
    private function errorInfo($msg){
    	$url=Yii::$app->params['sharehost'];
    	die("<script>
            document.domain = '".substr($url,strpos($url,".")+1,strlen($url))."';
           top.layerClose();
    	   top.layer.open({
			          content: '".$msg."',
			          style: 'background-color:#fff; color:#000; border:none;padding:0.2rem; font-size: 0.473333rem; border-radius:0.15rem; line-height:0.8rem; width:4.5rem;',
			          time: 2
			        });	
           </script>");    	
    }
}
