<?php

namespace api\modules\v3_0_4\controllers\lesson;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LessonService;
use common\service\DictdataService;

/**
 * 获取分类推荐内容
 *
 */
class CatalogListRecommendAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
      
        //最后获取id
        $lastid = $this->requestParam('lastid');
        //分页获取推荐跟着画id
        $lessonids=LessonService::getLessonIdsByLevelFromCache($rn,$lastid);
        //获取跟着画列表信息
        $ret = LessonService::getListDetail($lessonids, $uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
