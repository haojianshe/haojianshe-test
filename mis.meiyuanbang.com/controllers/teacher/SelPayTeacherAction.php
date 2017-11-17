<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserCorrectService;

/**
 * 选择付费老师
 */
class SelPayTeacherAction extends MBaseAction
{
    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_teacher';
    
    public function run()
    {
        $request = Yii::$app->request;
        $teacheruids = $request->get('teacheruids');
        $data['teacheruids']=explode(",", $teacheruids);
        $data['userlist']=UserCorrectService::getPayTeachers();
        return $this->controller->render('selpayteacher',$data);
    }
}
