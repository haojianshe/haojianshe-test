<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioService;
use mis\service\UserService;
use mis\service\StudioMenuService;
use common\service\dict\StudioDictDataService;

/**
 * 添加页面
 */
class AddpageAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {

        $request = Yii::$app->request;
        if (!$request->isGet) {
            die('非法请求!');
        }

        //检查参数是否非法
        $uid = $request->get('uid');
        if (!$uid || !is_numeric($uid)) {
            die('参数不正确');
        }

        $model = StudioMenuService::find()->select('studiomenuid,menuid')->where(['uid' => $uid])->asArray()->all();
        $studioMenu = StudioDictDataService::getBookMainType();
        StudioMenuService::delCache('',$uid);
        if (empty($model)) {
            return $this->controller->render('addpage', ['model' => $studioMenu, 'uid' => $uid]);
        } else {
            $array = [];
            foreach ($model as $key => $val) {
                $array[$val['menuid']] = $val['menuid'];
            }
            foreach ($array as $val) {
                foreach ($studioMenu as $k => $v) {
                    unset($studioMenu[$val]);
                }
            }
            return $this->controller->render('addpage', ['model' => $studioMenu, 'uid' => $uid]);
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '添加失败']);
    }

}
