<?php

namespace api\modules\v3_0_1\controllers\studio;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\StudioService;
use api\service\LiveService;

//use common\models\myb\Studio;

/**
 * 得到菜单对应的内容
 *
 */
class GetStudioLiveAction extends ApiBaseAction {

    public function run() {
        $menutype = $this->requestParam('menutype'); // 菜单状态
        $rn = $this->requestParam('rn') ? $this->requestParam('rn') : 10;
        $lastid = $this->requestParam('last_id') ? $this->requestParam('last_id') : NULL;
        //画室用户
        $uid = $this->requestParam('uid');
        //登录用户
        $userid = $this->requestParam('userid');
        if (!$menutype) {
            $menutype = 1;
        }
        if ($userid > 0) {
            switch ($menutype) {
                #直播课列表
                case 1:
                    $ret = LiveService::getStudioLive($uid, $lastid, $rn,$userid,1);
                    break;
                #课程列表
                case 2:
                    $ret = LiveService::getStudioLive($uid, $lastid, $rn,$userid,2);
                    break;
                default:
                    break;
            }
        } else {
            $ret = [];
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

    //            '1' => "热门班型",
    //            '2' => "画室简介",
    //            '3' => "作品展示",
    //            '4' => "视频课程",
    //            '5' => "师资力量",
    //            '6' => "往期成绩",
    //            '7' => "教学优势",
    //            '8' => "学员风采",
    //            '9' => "学习生活",
    //            '10' => "新闻资讯",
}
