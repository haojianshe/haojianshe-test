<?php

namespace api\modules\v3_0_4\controllers\lesson;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LessonService;

/**
 * 跟着画列表
 *
 */
class ListAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
       
        //主分类
        $maintypeid = $this->requestParam('maintypeid') ? $this->requestParam('maintypeid') : 0;
        //二级分类
        $subtypeid = $this->requestParam('subtypeid') ? $this->requestParam('subtypeid') : 0;
        //最后获取id
        $lastid = $this->requestParam('lastid');
        $uid = $this->_uid;
        //缓存获取跟着画
        $lessonids = LessonService::getLessonList($maintypeid,$subtypeid,$lastid,$rn);
        //获取跟着画列表信息
        $ret = LessonService::getListDetail($lessonids, $uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
