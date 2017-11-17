<?php

namespace api\modules\v3_1_1\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\OrderinfoService;
use api\service\CorrectService;

/**
 * 获取用户消费数量
 * @author Administrator
 *
 */
class GetCorrectSpendAction extends ApiBaseAction {

    public function run() {
        $uid = $this->requestParam('uid', true); //学生uid
        $ret['content'] = '';
        if ($uid) {
            //查找指定老师给用户的批改次数
            $correct_number = CorrectService::getTeacherSetUserCorrect($uid, $this->_uid);
            //查找我所有的批改次数
            $all_correct_num = CorrectService::getAllUserCorrect($uid);
            //增加消费记录课程总价格，改画次数
            $paySuccess = OrderinfoService::getUserOrderSuccess($uid);
            if ($paySuccess) {
                $ret['content'] = [
                    'spend_num' => (int) $paySuccess['info'], #消费的次数
                    'spend_money' => (float) $paySuccess['money'], #消费的钱
                    'correct_num' => (int) $correct_number, #批改次数
                    'all_correct_num' => (int) $all_correct_num #全部批改数
                ];
            } else {
                $ret['content'] = [
                    'spend_num' => 0,
                    'spend_money' => 0,
                    'correct_num' => (int) $correct_number,
                    'all_correct_num' => (int) $all_correct_num,
                ];
            }
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
