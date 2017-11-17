<?php

namespace api\modules\v2_3_3\controllers\dk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\DkService;

/**
 * @Description 
 * 抽奖模块,用户点击抽奖后,根据rand发射一个常值然后去活动对应奖品表中去对比概率区间,获取对应的奖品
 * 根据token来获取用户的uid,来判断用户信息
 */
class GetprizeuserAction extends ApiBaseAction {

    public function run() {
        //活动id
        if ($this->_uid < 0) {
            $data['message'] = '过期或者无效的token,请你从新登陆！';
            $this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL, $data);
        }
        $activeid = $this->requestParam('activeid', true);

        //根据活动id来判断是否分享记录中有符合条件的记录
        $prizeRes = DkService::getPrizeShareRecord($activeid, $this->_uid);

        //存在分享记录然后获取活动中对应的抽奖活动
        if ($prizeRes['count'] > 0 && $prizeRes['count'] < 5) {
            $prizeId = DkService::getPrizeGame($activeid, 1);
            if (!empty($prizeId['gameid'])) {
                //产生对应的奖品
                $data = DkService::getPrizesList($activeid, $prizeId['gameid'], $this->_uid);
                if (!empty($data)) {
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                } else {
                    $data['message'] = '奖品已经抽空';
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST, $data);
                }
            } else {
                $data['message'] = '对应活动不存在';
                $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE, $data);
            }
        } else {
            $data['message'] = '没有分享记录或存在恶意攻击';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE, $data);
        }
        $data['message'] = '请求错误';
        $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST, $data);
    }

}
