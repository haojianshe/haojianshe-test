<?php

namespace api\modules\v3_0_1\controllers\studio;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\StudioService;

//use common\models\myb\Studio;

/**
 * 得到菜单对应的内容
 *
 */
class GetStudioDataAction extends ApiBaseAction {

    public function run() {
        $menutype = $this->requestParam('menutype'); // 菜单状态
        $rn = $this->requestParam('rn') ? $this->requestParam('rn') : 10;
        $lastid = $this->requestParam('last_id') ? $this->requestParam('last_id') : NULL;
        $uid = $this->requestParam('uid');
        if (!$menutype) {
            $menutype = 1;
        }
        if ($uid > 0) {
            switch ($menutype) {
                //热门班型
                case 1:
                    $ret = StudioService::getStudio($uid);
                    break;
                #画室简介
                case 2:
                    $ret = StudioService::getStudioSynopsis($uid);
                    break;
                #作品展示
                case 3:
                    $array = StudioService::getStudioOpus($uid, $lastid, $rn);
                    $ret = StudioService::getStudioOpsusInfo($array);
                    break;
                #视频课程
                case 4:
                    $ret = StudioService::getStudioCourseInfo($uid);
                    break;
                #师资力量
                case 5:
                #往期成绩
                case 6:
                #教学优势
                case 7:
                #学员风采
                case 8:
                #学习生活
                case 9:
                #新闻资讯
                case 10:
                    $ret = StudioService::getStudioTeacherInfo($uid, $menutype);
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
