<?php

namespace api\modules\v3\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\OrderinfoService;
use api\service\LiveService;
use api\service\CourseSectionVideoService;
use api\service\CourseService;
use api\service\StudioService;
use api\service\GroupBuyService;
use api\service\CorrectService;
use api\service\CorrectRewardService;

/**
 * 最近购买列表
 * @author ihziluoh
 *
 */
class BuyVideoListAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        $uid = $this->_uid;
        $lastid = $this->requestParam('lastid');
        $goods = OrderinfoService::getBuyGoodsDb($uid, $lastid, $rn);
        foreach ($goods as $key => $value) {
            $goods[$key]['course_info'] = (object) null;
            $goods[$key]['live_info'] = (object) null;
            $goods[$key]['studio_info'] = (object) null;
            $goods[$key]['correct_info'] = (object) null;
            $goods[$key]['correct_reward_info'] = (object) null;
            switch ($value['subjecttype']) {
                case 1:
                    $goods[$key]['live_info'] = LiveService::getDetail($value['mark'], $uid);
                    break;
                case 2:
                    $goods[$key]['course_info'] = CourseService::getDetail($value['mark'], $uid);
                    break;
                case 3:
                    $goods[$key]['studio_info'] = (Object) StudioService::getDetail($value['mark']);
                    break;
                case 4://礼物
                    $goods[$key]['correct_reward_info'] = (Object) CorrectRewardService::getUserRewardOrderList($value['mark']);
                    $goods[$key]['ordertitle'] = str_replace('（礼物）','',$value['ordertitle']);
                    break;
                case 5:
                    $goods[$key]['correct_info'] = (Object) CorrectService::getFullCorrectInfo($value['mark'], $uid);
                     break;
                default:
                    break;
            }
        }
        GroupBuyService::removeRed($uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $goods);
    }

}
