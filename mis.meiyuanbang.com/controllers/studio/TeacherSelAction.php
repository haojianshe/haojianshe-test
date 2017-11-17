<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserCorrectService;
use mis\service\UserService;
use common\models\myb\UserCorrect;

/**
 * 选择认证老师
 */
class TeacherSelAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $sname = $request->get('sname');
        $select = $request->get('select');
        //获取老师列表
        $data = UserService::getTeacherByPage($sname,$select);
        $data['uid'] = $uid;
        $data['sname'] = $sname;
        $data['select'] = $select;
        return $this->controller->render('teachersel', ['models'=>$data]);
    }

}
