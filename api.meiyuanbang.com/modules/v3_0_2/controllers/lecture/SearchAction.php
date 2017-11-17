<?php
namespace api\modules\v3_0_2\controllers\lecture;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;

use api\service\LectureService;
/**
 * 精讲文章 及专题搜索
 * @author ihziluoh
 *
 */
class SearchAction extends ApiBaseAction{

   public  function run(){
        $keyword=$this->requestParam('keyword',true);
        $uid=$this->_uid;
        //搜索文章 专题
        $newsids=LectureService::getSearchLectureList($keyword);
        $data['list']=LectureService::getLectureListInfo($newsids,$uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}