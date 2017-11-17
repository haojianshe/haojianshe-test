<?php

namespace api\modules\v3_0_4\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectService;

/**
 * 获取分页排行榜数据
 * @author Administrator
 *
 */
class RankpageAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        //获取排行版类型 1日榜 2周榜
        $rankType = $this->requestParam('ranktype', true);
        //批改类型 1:色彩   4:素描    5:速写
        $correctType = $this->requestParam('correcttype', true);
        //分页
        $lastid = $this->requestParam('lastid');
        $self = false;
        if (!$lastid) {
            $lastid = 0;
            if ($this->_uid > 0) {
                //第一页数据，并且登录状态才取自己的排名
                $self = true;
            }
        }
        //获取数据
        $ret['content'] = [];
        $data = CorrectService::getRank($correctType, $rankType, $rn, $lastid, $this->_uid, $self);
        $ids = $data['data'];
        foreach ($ids as $key => $value) {
            $ret['content'][] = CorrectService::getFullCorrectInfo($value, $this->_uid);
        }
        //是否要带自身排名
        if (isset($data['self']) && isset($data['self'])) {
            $data['self']['info'] = CorrectService::getFullCorrectInfo($data['self']['correctid'], $this->_uid);
            $ret['selfrank'] = $data['self'];
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
