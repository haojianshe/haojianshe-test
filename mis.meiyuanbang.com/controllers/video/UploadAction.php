<?php

namespace mis\controllers\video;

use Yii;
use yii\base\Action;
use mis\service\TweetService;
use mis\service\ResourceService;
use mis\components\MBaseAction;
use common\service\CommonFuncService;
use common\service\DictdataService;

/**
 * 视频上传
 * 
 */
class UploadAction extends MBaseAction {
	public $resource_id = 'operation_video';


    public function run() {
        
        return $this->controller->render('upload');
    }

}
