<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioEnrollService;

/**
 * 不同画室不同班型列表下的不同报名方式
 */
class SignlistAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_studio';

    public function run() {
        //分页列表
        $request = Yii::$app->request;
        $classtypeid = $request->get('classtypeid');
        $uid = $request->get('uid');
        $data = StudioEnrollService::getByPage($classtypeid, $uid);
     
        return $this->controller->render('sign_list', ['models' => $data]);
    }

}
