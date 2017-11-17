<?php
namespace api\modules\v1_3\controllers\lesson;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LessonService;
use common\service\DictdataService;

/**
 * 考点搜索
 */
class SearchAction extends ApiBaseAction
{
	public function run()
    {
    	//每页返回记录个数
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}
    	//主类型，必选
    	$keyword = $this->requestParam('kw',true);
    	//最后一个lessonid
    	$lastid = $this->requestParam('lastid');
    	if(!$lastid){
    		$lastid=0;
    	}
    	$ret['lessonlist'] = [];
    	//(1)获取id列表
    	$ids = LessonService::getIdsByTitle($keyword, $lastid, $rn);
    	//(2)添加第一张图片
    	foreach ($ids as $k1=> $v1){
    		//获取考点信息和第一张展示图
    		$arrlesson = LessonService::getLessonWithFirstPic($v1['lessonid']);
    		if($arrlesson){
    			$ret['lessonlist'][]=$arrlesson;
    		}
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
