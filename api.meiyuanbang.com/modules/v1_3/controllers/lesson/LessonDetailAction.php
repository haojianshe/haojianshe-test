<?php
namespace api\modules\v1_3\controllers\lesson;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LessonService;
use api\service\LessonSectionService;
use api\service\LessonPicService;
use api\service\LessonDescService;
use common\service\DictdataService;

/**
 * 获取考点完整信息
 * @author Administrator
 *
 */
class LessonDetailAction extends ApiBaseAction
{
	public function run()
    {
    	//考点id
    	$lessonid = $this->requestParam('lessonid',true);    	
    	//(1)考点详情
    	$ret = LessonService::getById($lessonid,$this->_uid);
    	//(2)填充考点的节点和图片信息
    	$sectionids = explode(',', $ret['sectionids']);
    	$imgcount = 0;
    	foreach ($sectionids as $k=>$v){
    		//取考点信息
    		$section = LessonSectionService::getById($v);
    		$imgs = LessonPicService::getBySectionId($v);
    		$section['img'] = $imgs;
    		$imgcount += count($imgs);
    		$ret['section'][] = $section;

    	}
    	$ret['imgcount'] = $imgcount;
        //增加批改分享url ji图片
        $ret['shareurl'] = Yii::$app->params['sharehost'].'/lesson?lessonid='.$lessonid;
        $ret['shareimg'] = LessonService::getLessonWithFirstPic($lessonid)['imgs']['l']['url'];
        $ret['lessondesc']=LessonDescService::getLessonDescByLessonid($lessonid);

    	//(3)增加浏览量
    	LessonService::addHits($lessonid);
     

        $recommendlessonids = LessonService::getLessonRecommendRedis($lessonid,$ret['maintype'],$ret['subtype']);
        //获取跟着画列表信息
        $ret['recommend']=LessonService::getListDetail($recommendlessonids, $this->_uid);

    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
