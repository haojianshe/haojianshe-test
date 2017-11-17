<?php

namespace mis\controllers\tool;

use Yii;
use mis\components\MBaseAction;
use common\service\AliCdnService;

/**
 * 上传文件后清空CDN
 */
class RemovecacheAction extends MBaseAction {

    public $resource_id = 'operation_package';

    /**
     * 清空CDN
     */
    public function run() {
        //$request = Yii::$app->request;
        $result = AliCdnService::refresh('Directory', 'img.meiyuanbang.com/download/');
        
        if ($result) {
            return $this->controller->outputMessage(['errno' => 0, 'msg' => '清空CDN成功']);
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '清空失败,请联系管理员']);
    }

}
 