<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use common\service\DictdataService;
use mis\service\LessonSectionService;
use mis\service\LessonPicService;
/**
 * 节点图片编辑页面
 */
class SectionImgAction extends MBaseAction
{
	public $resource_id = 'operation_lesson';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	 
    	//获取sectionid
    	$sectionid =  $request->get('sectionid');
    	if(($sectionid && !is_numeric($sectionid)) || !$sectionid) {
    		die('参数错误!');
    	}
    	//获取节点信息
    	$sectionmodel = LessonSectionService::findOne(['sectionid'=>$sectionid]);
    	$ret['sectionmodel'] = $sectionmodel;
    	//获取图片信息
    	$imgmodels = LessonPicService::getBySectionid($sectionid);
    	$ret['imgmodels'] = $imgmodels;
    	return $this->controller->render('sectionimg', $ret);
    }
}
