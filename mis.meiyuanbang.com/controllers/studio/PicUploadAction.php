<?php
namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use common\service\AliOssService;

use common\models\myb\Resource;

/**
 * 上传图片
 */
class PicUploadAction extends MBaseAction
{
	public $resource_id = 'operation_studio';
	private $ossobject = 'studio';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	
    	//只能post访问
    	if(!$request->isPost){
    		die('访问错误');
    	}
    	
    	//处理上传的图片
    	if (!isset($_FILES['uploadify'])) {
    		die('未选择图片!');
    	}    	
    	$file = $_FILES['uploadify'];
    	//检查图片大小和类型
    	if($file['size']>5120000){
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
    	$data['url'] = Yii::$app->params['ossurl'] . $ret;
        //图片宽高
        $ret =  AliOssService::getFileHW($data['url']);
        //判断是否取到宽高
        if(!$ret['height'] || !$ret['width']){
            return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        }
        if($ret!=false){
            $data['w'] = $ret['width'];
            $data['h'] = $ret['height'];
        }else{
            $data['w'] = 1000;
            $data['h'] = 1000;
        }
        $img['n']=$data;
        $model=new Resource();
        $model->img=json_encode($img);
        $model->save();    	
    	if($ret){    				
    		return $this->controller->outputMessage(['errno'=>0,"data"=>$model->attributes]);
    	}
    	else{
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'保存数据库失败']);
    	}    	
    }
}
