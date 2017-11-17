<?php
namespace api\modules\v3_2_1\controllers\home;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\HomePopAdvService;
use api\service\UserDetailService;

/**
 * 获取弹出广告
 */
class PopAdvAction extends ApiBaseAction {

    public function run() {
        //省id
        $provinceid = $this->requestParam('provinceid', true);
        //身份id
        $professionid = $this->requestParam('professionid', true);

        $uid=$this->_uid;
        if($uid>-1){
            //更新用户角色
            UserDetailService::updateProfessionProvince($uid,$professionid,$provinceid);
        }
        $ret=HomePopAdvService::getPopAdv($provinceid,$professionid);
        $data=[];
        if($ret){
        	$data[]=$ret[array_rand($ret,1)];
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
