<?php
namespace api\modules\v1_3\controllers\lesson;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LessonService;
use api\service\LessonSectionService;
use api\service\LessonPicService;
use common\service\DictdataService;
use common\service\CommonFuncService;

/**
 * 考点主类型信息
 */
class MainTypeListAction extends ApiBaseAction
{
	public function run()
    {
    	//获取主类型
    	$ret = [];
    	$mainlist = DictdataService::getLessonMainType();
    	foreach ($mainlist as $k=>$v){
    		//添加标题
    		$v['title'] = '跟我画' . $v['maintypename'];
    		//类型对应的考点总数
    		$v['totalnum'] = 0;
    		//第一个分类型id
    		$v['firstsubtypeid'] = DictdataService::getLessonSubType($v['maintypeid'])[0]['subtypeid'];
    		//获取最新的6个考点
    		$lessonids = LessonService::getIdsByMainType($v['maintypeid'], 6);
            if($lessonids){
                //为每一个lesson添加第一张图片
                foreach ($lessonids as $k1=> $v1){
                    //获取考点信息和第一张展示图
                    $arrlesson = LessonService::getLessonWithFirstPic($v1);
                    if($arrlesson){
                        $v['lessonlist'][]=$arrlesson;
                    }               
                }
            }else{
                $v['lessonlist']=[];
            }
    		$ret['typelist'][] = $v;    		
    	}
    	//所有考点数
    	$ret['totalnum'] =  LessonService::getLessonNum();
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);    	
    }
}