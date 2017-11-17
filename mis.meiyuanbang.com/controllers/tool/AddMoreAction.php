<?php

namespace mis\controllers\tool;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;

/**
 * 批量增加能力素材页面
 */
class AddMoreAction extends MBaseAction {

      public $resource_id = 'operation_package';

    /**
     * 批量增加能力素材
     */
    public function run() {
        return $this->controller->render('addmore');
    }

}
