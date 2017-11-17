<?php
namespace mis\controllers\dkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserCorrectService;
use mis\service\UserService;
use common\models\myb\UserCorrect;

/**
 * 选择认证老师
 */
class TeacherSelAction extends MBaseAction
{
    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_teacher';
    
    public function run()
    {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        //获取批改老师
        $data =  UserService::getFamousTeacherByPage();
        $data['uid']= $uid;  
        return $this->controller->render('teachersel',$data);
    }
}
