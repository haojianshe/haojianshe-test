<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use common\service\AliOssService;
use mis\service\LessonPicService;
use mis\service\LessonService;
use mis\service\LessonSectionService;

/**
 * 上传图片
 */
class PicUploadAction extends MBaseAction
{
	public $resource_id = 'operation_lesson';
	private $ossobject = 'cms/lesson';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	
    	//只能post访问
    	if(!$request->isPost){
    		die('访问错误');
    	}
    	//检查参数
    	$sectionid = $request->get('sectionid');
    	if(($sectionid && !is_numeric($sectionid)) || !$sectionid) {
    		die('参数错误!');
    	}
    	//处理上传的图片
    	if (!isset($_FILES['uploadify'])) {
    		die('未选择图片!');
    	}    	
    	$file = $_FILES['uploadify'];
    	//检查图片大小和类型
    	if($file['size']>2048000){
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
    	//上传成功,写库
    	$model = new LessonPicService();
    	$model->sectionid = $sectionid;
    	$model->picurl = Yii::$app->params['ossurl'] . $ret;
    	//两张图之间留10个空位，图片传错或者漏传的时候可以插入图片和调整位置
    	$listorder = LessonPicService::getMaxListorder($sectionid);
    	$model->listorder = $listorder+10;
    	//图片宽高
    	$ret =  AliOssService::getFileHW($model->picurl);
        //判断是否取到宽高
        if(!$ret['height'] || !$ret['width']){
            return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
        }
    	if($ret!=false){
    		$model->picw = $ret['width'];
    		$model->pich = $ret['height'];
    	}
    	else{
    		$model->picw = 1000;
    		$model->pich = 1000;
    	}
    	if($model->save()){
    		//更新section表的图片数量,采用每次在pic表计算的方式，不使用pic数量+1，避免计算失误没有弥补机会
    		$piccount = LessonPicService::getPicCount($sectionid);
    		$sectionmodel = LessonSectionService::findOne(['sectionid'=>$sectionid]);
    		$sectionmodel->piccount = $piccount;
    		$sectionmodel->save();    		
    		return $this->controller->outputMessage(['errno'=>0]);
    	}
    	else{
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'保存数据库失败']);
    	}    	
    }
}
