<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioService;
use mis\service\StudioMenuService;

/**
 * 画室导航排序
 */
class ListorderAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $menuid = $request->post('menuid');
        $value = $request->post('value');
        $uid = $request->post('uid');
        if (!$menuid || !is_numeric($menuid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = StudioMenuService::findOne(['menuid' => $menuid, 'uid' => $uid]);
        if ($model) {
            if ($value == 1.1) {
               StudioMenuService::deleteAll(['menuid' => $menuid, 'uid' => $uid]);
            } else {
                $model->listorder = $value;
            }

            //审核判断章节视频是否为空
            //获取章节
            $ret = $model->save();
            
            $redis = Yii::$app->cache;
            $redis->delete('studio_menu_list_' . $uid);
            $redis->delete('studio_teacher_list_' . $uid . '_menutype_' . $menuid);
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
            return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
        }
    }

}
