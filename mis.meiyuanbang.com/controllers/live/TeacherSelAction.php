<?php
namespace mis\controllers\live;

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
    public $resource_id = 'operation_course';
    
    public function run()
    {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $sname = $request->get('sname');
        //获取老师列表
        $data =  UserService::getTeacherByPage($sname);
        $data['uid']= $uid;  
        $data['sname']= $sname;  
        return $this->controller->render('teachersel',$data);
    }
}
