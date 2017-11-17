<?php
namespace  mis\controllers\material;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\MatreialSubjectService;
/**
* 
*/
class IndexAction extends MBaseAction
{
    public $resource_id = 'operation_material';
    public function run()
    { 
        //分页获取专题列表
        $data = MatreialSubjectService::getSubjectByPage();
        return $this->controller->render('index', $data);
    }
}