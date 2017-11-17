<?php

namespace api\modules\v2_3_3\controllers\dk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\DkService;

/**
 * @Description
 * 当用户提交了
 * @param $phone,$token,$address,$name
 * @
 */
class SetuserprizeAction extends ApiBaseAction {

    public function run() {
        //活动id
        if ($this->_uid < 0) {
            $data['message'] = '过期或者无效的token,请你从新登陆！';
            $this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL, $data);
        }
        //活动id
        $activeid = $this->requestParam('activeid', true);
        //奖品id
        $prizesid = $this->requestParam('prizesid', true);
        //电话
        $mobile = $this->requestParam('mobile', true);
        //地址
        $address = $this->requestParam('address');
        //数据写入表主键 抽奖活动对应奖品表
        $gameprizesid = $this->requestParam('gameprizesid');
        //姓名
        $name = $this->requestParam('name');
        //token验证成功后，首先去活动表中获取是否存在或有效验证通过后写入中奖用户表dk_prize_user
        $activeCount = DkService::getPrizeGame($activeid, 2);

        if ($activeCount['count'] == 1) {
            //根据活动id来判断是否分享记录中有符合条件的记录
            $prizeRes = DkService::getPrizeShareRecord($activeid, $this->_uid);
            if ($prizeRes['count'] >= 1) {
                $array = [
                    'activityid' => $activeid,
                    'prizesid' => $prizesid,
                    'gameprizesid' => $gameprizesid,
                    'uid' => $this->_uid,
                    'mobile' => $mobile,
                    'address' => $address,
                    'name' => $name
                ];
                //写入用户中奖用户表
                try {
                    //开启实物
                    #$innerTransaction = Yii::$app->db->beginTransaction();
                    //写入到中奖用户表
                    $writeUserData = DkService::setUserPrize($array);
                    //修改奖品数量并且改变分享记录status状态
                    $setPrizeNum = DkService::setPrizeNum($array['gameprizesid'], $activeid, $this->_uid);
                    if ($writeUserData == true && $setPrizeNum == true) {
                        # $innerTransaction->commit();
                        $data = [];
                        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                    } else {
                        $data['message'] = '奖品没有库存了';
                        $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE, $data);
                    }
                } catch (Exception $ex) {
                    #$innerTransaction->rollBack();
                    $data['message'] = '信息录入失败';
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE, $data);
                }
            } else {
                $data['message'] = '没有抽奖机会';
                $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE, $data);
            }
        } else {
            $data['message'] = '活动不存在,请你核实后再次提交';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST, $data);
        }
    }

}
