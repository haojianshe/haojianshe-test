<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioMenuService;

/**
 * 添加页面 
 */
class PageinsertAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $uid = $request->post('uid');
        $status = $request->post('status');
        $id = $request->post('id');

        if (!$uid || !is_numeric($uid)) {
            die('参数不正确');
        }
        //添加
        if ($status == 1) {
            $model = new StudioMenuService();
            $model->uid = $uid;
            $model->menuid = $id;
            $model->ctime = time();
            $model->save();
            //删除
        } else {
            StudioMenuService::deleteAll(['uid' => $uid, 'menuid' => $id]);
        }
    }

}
