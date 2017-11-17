<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use common\service\AliOssService;

/**
 * 上传节日图标图片
 */
class ThumbUploadAction extends MBaseAction
{
	public $resource_id = 'operation_course';
	private $ossobject = 'cms/icons';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	
    	if(!$request->isPost){   
    		//get访问
    		$url = $request->get('url');
    		if($url!=''){
    			$url = urldecode($url);
    		}
    		$model['url'] = $url;
            $model['name'] = $request->get('name');
    		return $this->controller->render('thumbupload', ['model' => $model]);
    	}
    	else{
    		$url = $request->post('url');
            $model['name'] = $request->post('name');
    		$model['url'] = $url;
    		//获取上传的图片
    		if (!isset($_FILES['file_thumb'])) {
    			die('未选择图片!');
    		}    		
    		$file = $_FILES['file_thumb'];
    		//检查图片大小和类型
    		if($file['size']>512000){
    			return $this->controller->render('thumbupload', ['model' => $model,'msg'=>'缩略图不能大于500k']);
    		}
    		$fileext = AliOssService::getFileExt($file['name']) ; 
    		if(!in_array( $fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp"]))
    		{
    			return $this->controller->render('thumbupload', ['model' => $model,'msg'=>'图片格式错误']);
    		}
    		//上传图片
    		$filename = AliOssService::getFileName($fileext);
    		$ret = AliOssService::picUpload($this->ossobject, $filename, $file);
    		if ($ret == false) {
   				return $this->controller->render('thumbupload', ['model' => $model,'msg'=>'上传图片失败']);
    		}
    		//上传成功
    		$model['url'] = Yii::$app->params['ossurl'] . $ret;
    		return $this->controller->render('thumbupload', ['model' => $model,'msg'=>'上传成功','isclose'=>true]);
    	}
    }
}
