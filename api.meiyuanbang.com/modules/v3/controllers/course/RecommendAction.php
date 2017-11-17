<?php
namespace api\modules\v3\controllers\course;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CourseService;
use api\service\CourseRecommendService;
use api\service\CourseRecommendCatalogService;
use api\service\LiveRecommendService;
use api\service\LiveService;
use api\service\PosidHomeService;

/**
 * 课程首页推荐
 * @author ihziluoh
 *
 */
class RecommendAction extends ApiBaseAction{
   public  function run(){
        $ret=[];
        //顶部广告
        $ret['top_adv']=PosidHomeService::getPosidHomeList(5);
        //后台推荐直播
        $recliveids=LiveRecommendService::getLiveRecommendIds();
        $ret['live_recommend']=LiveService::getListDetail($recliveids);
        //正在进行的直播
        $onlineliveids=LiveService::getOnlineLiveList();
        $ret['live_online']=LiveService::getListDetail($onlineliveids);
        //推荐课程
        $uid=$this->_uid;
        $ret['course_recommend']=CourseRecommendCatalogService::getCatalogInfo($uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}