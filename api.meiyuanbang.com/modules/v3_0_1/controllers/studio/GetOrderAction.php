<?php
namespace api\modules\v3_0_1\controllers\studio;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\StudioService;
use common\models\myb\StudioSignuser;

/**
 * 得到班型下面的报名方式
 *
 */
class GetOrderAction extends ApiBaseAction {

    public function run() {
        //班型id
        $enrollid = $this->requestParam('enrollid'); //班型id
        $uid = $this->requestParam('uid');  //用户id
        $discount_price = $this->requestParam('discount_price');  //价格
        if ($uid > 0) {
            if ($discount_price > 0) {
                $ret = StudioService::getOrder($uid, $enrollid);
            } else {
                $ret['orderid'] = StudioSignuser::find()->select('signuserid')->where(['uid'=>$uid])->andWhere(['enrollid' => $enrollid])->count();
            }
            if (empty($ret)) {
                $ret = [
                    'orderid' => 0
                ];
            }
        } else {
            $ret = [];
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
