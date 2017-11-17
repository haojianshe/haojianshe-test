<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use common\models\myb\StudioTeacher;

/**
 * 添加页面 
 */
class TeacherInsertAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        if (!$request->isGet) {
            die('非法请求!');
        }
        //检查参数是否非法
        $uid = $request->get('uid');
        $status = $request->get('status');
        $uuid = $request->get('uuid');
        $redis = Yii::$app->cache;
        $redis_key = 'studio_live_list'; //缓存key
        $redis->delete($redis_key);
        //$uids = StudioTeacher::find()->select(['uuid'])->where(['uid' => $uid])->asArray()->one();
        $redis->delete('studio_studio_course' .$uuid);
        if (!$uid || !is_numeric($uid)) {
            die('参数不正确');
        }
        //添加
        if ($status == 1) {
            $model = new StudioTeacher();
            $model->uid = $uid;
            $model->uuid = $uuid;
            $model->ctime = time();
            $model->save();
            echo 1;
            exit;
            //删除
        } else {
            StudioTeacher::deleteAll(['uid' => $uid, 'uuid' => $uuid]);
            echo 0;
            exit;
        }
    }

}
