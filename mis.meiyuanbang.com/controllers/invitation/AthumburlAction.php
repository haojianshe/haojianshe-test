<?php
namespace mis\controllers\activity;

use Yii;
use mis\components\MBaseAction;
use common\service\AliOssService;

/**
 * 精讲添加和修改页面
 */
class AthumburlAction extends MBaseAction
{
	//public $resource_id = 'operation_activity';
	private $ossobject = 'cms/activity';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	$thumb['valclass']  = $request->get('valclass');
        $thumb['imgclass']  = $request->get('imgclass');
    	if(!$request->isPost){   
    		//get访问
    		$url = $request->get('url');
    		if($url!=''){
    			$url = urldecode($url);
    		}
    		$model['url'] = $url;
    		return $this->controller->render('cthumbupload', ['model' => $model,'thumb'=>$thumb]);
    	}
    	else{
    		$url = $request->post('url');
    		$model['url'] = $url;
    		//获取上传的图片
    		if (!isset($_FILES['file_thumb'])) {
    			die('未选择图片!');
    		}    		
    		$file = $_FILES['file_thumb'];
    		//检查图片大小和类型
    		if($file['size']>512000){
    			return $this->controller->render('cthumbupload', ['model' => $model,'msg'=>'缩略图不能大于500k','thumb'=>$thumb]);
    		}
    		$fileext = AliOssService::getFileExt($file['name']) ; 
    		if(!in_array( $fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp"]))
    		{
    			return $this->controller->render('cthumbupload', ['model' => $model,'msg'=>'图片格式错误','thumb'=>$thumb]);
    		}
    		//上传图片
    		$filename = AliOssService::getFileName($fileext);
    		$ret = AliOssService::picUpload($this->ossobject, $filename, $file);
    		if ($ret == false) {
   				return $this->controller->render('cthumbupload', ['model' => $model,'msg'=>'上传图片失败','thumb'=>$thumb]);
    		}
    		//上传成功
    		$model['url'] = Yii::$app->params['ossurl'] . $ret;
    		return $this->controller->render('cthumbupload', ['model' => $model,'msg'=>'上传成功','isclose'=>true,'thumb'=>$thumb]);
    	}
    }
}
