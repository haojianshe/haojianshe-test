<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioClasstypeService;

/**
 * 报名详情列表页
 */
class SignDetailAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
   public $resource_id = 'operation_studio';
    public function run() {
        $request = Yii::$app->request;
        //检查参数是否非法
        $uid = $request->get('uid');
        $classtypeid = $request->get('classtypeid');
        //分页列表
        $data = StudioClasstypeService::getPayListUser($uid, $classtypeid);
        return $this->controller->render('sign_detail', $data);
    }

}
