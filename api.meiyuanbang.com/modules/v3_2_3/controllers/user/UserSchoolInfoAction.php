<?php

namespace api\modules\v3_2_3\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\MiddleSchoolService;
use api\service\UniversityService;

/**
 * 获取用户学校接口
 */
class UserSchoolInfoAction extends ApiBaseAction {

    public function run() {
        $request = Yii::$app->request;
        //省id
        $provinceid = $this->requestParam('provinceid', true);
        //市 区
        $city_id = $this->requestParam('city_id', true);
        //县
        $area_id = $this->requestParam('area_id');
        //所属学龄阶段 
        $professionid = $this->requestParam('professionid',true);
        switch ($professionid) {
            //高三 高二 高一 初中
            case 0:
            case 1:
            case 2:
            case 3:
                //获取用户的中学、高中
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, MiddleSchoolService::getUserMiddleSchool($provinceid, $city_id, $area_id));
                break;
            //大学
            case 4:
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, UniversityService::getUserUniversity($provinceid, $city_id));
                break;
            //其他 老师 小学
            case 6:
            case 5:
            case 7:
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
                break;
        }
    }

}
