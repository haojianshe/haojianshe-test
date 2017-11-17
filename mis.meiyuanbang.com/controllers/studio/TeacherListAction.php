<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
#use mis\service\UserCorrectService;
use mis\service\UserService;
#use common\models\myb\UserCorrect;

/**
 * 老师管理选择老师
 */
class TeacherListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $sname = trim($request->get('sname'));
        $select = $request->get('select');
        $type = '';
        if($request->get('over')=='已选中列表'){
            $type=$uid;
        }
        //获取老师列表
        $data = UserService::getTeacherByPageList($sname, $select,$type);
        $userData = UserService::getUserCheckboxList($uid);
        if (!empty($data)) {
            foreach ($data['models'] as $key => $val) {
                foreach ($userData as $k => $v) {
                    if ($v['uid'] == $val['uid']) {
                        $data['models'][$key]['type_status'] = 1;
                    }
                }
            }
        }
        $data['uid'] = $uid;
        $data['sname'] = $sname;
        $data['select'] = $select;
        return $this->controller->render('teacher_list', ['models' => $data, 'userData' => $userData]);
    }

}
