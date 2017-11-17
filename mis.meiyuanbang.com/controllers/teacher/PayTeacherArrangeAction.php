<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\CorrectPayteacherArrangeService;
use mis\service\UserService;
/**
 * 付费老师排班
 */
class PayTeacherArrangeAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_teacher';
	
	public function run()
    {
    	//获取排班列表
    	$request = Yii::$app->request;
    	$data =  CorrectPayteacherArrangeService::getByPage();
        
        foreach ($data['models'] as $key => $value) {
            $data['models'][$key]['teacherlist']=UserService::getInfoByUids($value['teacheruids']);
        }
    	return $this->controller->render('payteacherarrange',$data);
    }
}
