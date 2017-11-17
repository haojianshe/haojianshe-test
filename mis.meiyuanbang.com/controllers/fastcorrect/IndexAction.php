<?php
namespace mis\controllers\fastcorrect;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\FastCorrectService;
/**
 * 后台快速批改活动列表页
 * 
 */
class IndexAction extends MBaseAction
{
   public $resource_id = 'operation_activity';

	public function run()
    {

        //分页获取帖子列表
        $data = FastCorrectService::getFastCorrectByPage();
        return $this->controller->render('index', $data);
    }
}
