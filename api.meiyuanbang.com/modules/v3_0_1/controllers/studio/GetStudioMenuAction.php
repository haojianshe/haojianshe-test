<?php

namespace api\modules\v3_0_1\controllers\studio;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\StudioService;
use common\service\dict\StudioDictDataService;

/**
 * 得到画室菜单
 *
 */
class GetStudioMenuAction extends ApiBaseAction {

    public function run() {
        $uid = $this->requestParam('uid', true); #画室id
        $array = StudioService::getStudioMenu($uid);
        if (!empty($array)) {
            foreach (StudioDictDataService::getBookMainType() as $key => $val) {
                foreach ($array as $k => $v) {
                    if ($key == $v['menuid']) {
                        $ret[] = [
                            'key' => $key,
                            'menuVal' => $val,
                            'uid' => $v['uid'],
                        ];
                    }
                }
            }
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
