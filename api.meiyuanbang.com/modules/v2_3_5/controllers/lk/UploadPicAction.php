<?php
namespace api\modules\v2_3_5\controllers\lk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\AliOssService;
use common\service\CommonFuncService;

/**
 * 上传图片
 */
class UploadPicAction extends ApiBaseAction
{
	private $ossobject = 'activity/lk';
    public function run()
    {  
        $img_type = $this->requestParam('img_type'); 
        $request = Yii::$app->request;
        if(!$request->isPost){
            die('非法请求!');
        }
       
        //处理上传的图片
        if (!isset($_FILES['img_file'])) {
            die('未选择图片!');
        }       
        $file = $_FILES['img_file'];
        //检查图片大小和类型
        if($file['size']>10485760){
            die('图片太大');
        }

        $fileext = AliOssService::getFileExt($file['name']) ;
        if(!in_array( $fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp"]))
        {
            die('图片格式错误');
        }
        //开始处理图片
        $filename = AliOssService::getFileName($fileext);
        $ret = AliOssService::picUpload($this->ossobject, $filename, $file);
        if ($ret == false) {
            return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        }
        $img_infohw=AliOssService::getFileHW(Yii::$app->params['ossurl'] . $ret);
        //判断宽高
        if(!$img_infohw['height'] || !$img_infohw['width']){
        	return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        }
        $img_info['n']['h']=$img_infohw['height'];
        $img_info['n']['w']=$img_infohw['width'];
        $img_info['n']['url']=Yii::$app->params['ossurl'] . $ret;
        //上传成功,返回
        $data['img_type']=$img_type;
        $data['img']=json_encode($img_info);
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
