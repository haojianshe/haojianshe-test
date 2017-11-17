<?php

namespace api\modules\v3_0_4\controllers\live;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;

/**
 * 直播列表
 *
 */
class ListAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        #最后获取id
        $lastid = $this->requestParam('lastid');
        #主分类
        $f_catalog_id = $this->requestParam('f_catalog_id') ? $this->requestParam('f_catalog_id') : 0;
        $uid = $this->_uid;
        //缓存获取直播id列表
        $liveids = LiveService::getLiveList($lastid, $rn, $f_catalog_id);
        //获取直播列表详情
        $ret = LiveService::getListDetail($liveids, $uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
