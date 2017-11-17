<?php

namespace api\modules\v3_0_2\controllers\lecture;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LectureService;
use api\service\UserDetailService;

/**
 * 获取精讲列表
 * @author ihziluoh
 *
 */
class ListAction extends ApiBaseAction {

    public function run() {
        $lastid = $this->requestParam('lastid');
        $rn = $this->requestParam('rn') ? $this->requestParam('rn') : 10;
        $lecture_level1 = $this->requestParam('lecture_level1', true);
        //身份参数 新加
        $professionid = $this->requestParam('professionid');
        //省份参数 新加
        $provinceid = $this->requestParam('provinceid');
        $uid = $this->_uid;

        //由于老版本没有传递 身份和省份参数，所以只判断参数是否存在就看断定是否是老接口或者新接口
        if (isset($_REQUEST['professionid']) || isset($_REQUEST['provinceid'])) {
            //新接口
            if ($uid == -1 && intval($provinceid) > 0 && intval($provinceid) < 35) {
                //游客模式 如果能正确获取到位置 游客模式传递的身份为0,高中
                $array = [
                    'provinceid' => $provinceid,
                    'professionid' => $professionid
                ];
                $data = LectureService::getNewData(LectureService::getLectureData($lecture_level1, $lastid, $rn), $array);
            } elseif ($uid > 0) {
                //去表中获取身份和省份 如果不存在就按照老数据返回
                $userData = UserDetailService::getByUid($uid);
                if (isset($userData['provinceid']) && isset($userData['professionid'])) {
                    $array = [
                        'provinceid' => $userData['provinceid'],
                        'professionid' => $userData['professionid']
                    ];
                    $data = LectureService::getNewData(LectureService::getLectureData($lecture_level1, $lastid, $rn), $array);
                }
            }
        } else {//老接口
            $data = LectureService::getLectureData($lecture_level1, $lastid, $rn);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
