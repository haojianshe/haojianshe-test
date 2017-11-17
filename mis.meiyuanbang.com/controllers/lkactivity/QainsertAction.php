<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\LkActivityService;
use mis\service\MybLkMaterialRelationService;

/**
 * 写入状元分享会数据
 */
class QainsertAction extends MBaseAction {

    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        //先获取model
        $status = $request->post('status');

        $zp_type = $request->post('zp_type');
        $newsid = $request->post('newsid');
        $lkid = $request->post('lkid');
        if (($zp_type == 2 || $zp_type == 3) && $status == 1) {
            $status = MybLkMaterialRelationService::getZtypeStatus($lkid, $zp_type, $newsid);
            if ($status['reid']) {
                echo 2;
                exit;
            }
        }
        $model = MybLkMaterialRelationService::findOne(['lkid' => $lkid, 'newsid' => $newsid, 'zp_type' => $zp_type]);
        if ($zp_type === 1) {
            $redistype = 'qa';
        } else {
            $redistype = 'article';
        }
        if (!empty($model)) {
            $model->status = $request->post('status');
            $model->ctime = time();
            if ($request->post('status') == 0) {
                $model->ctime = 0;
                $model->status = 0;
                $model->zdtime = 0;
            } else {
                $model->ctime = time();
                $model->status = 1;
            }
            if ($model->save()) {
                $redis = Yii::$app->cache;
//                $rediskey = "article_list_" . $lkid . '_' . $zp_type;
                $rediskey = "article_list_" . $lkid . '_' . 'all';
                $rediskey1 = "activity_" . $redistype . "_" . $newsid;
                $redis->delete($rediskey);
                $redis->delete($rediskey1);
                echo 1;
                exit;
            } else {
                echo 0;
                exit;
            }
        } else {
            $model = new MybLkMaterialRelationService();
            $model->lkid = $lkid;
            $model->newsid = $newsid;
            $model->zp_type = $zp_type;
            $model->ctime = time();
            $model->zdtime = 0;
            $model->status = 1;
            if ($model->save()) {
                echo 1;
                exit;
            } else {
                echo 0;
                exit;
            }
        }
//        $delResult = MybLkMaterialRelationService::deleteAll('lkid=:lkid and zp_type=:zp_type', [':lkid' => $lkid, ':zp_type' => $zp_type]);
//        foreach ($strid as $key => $val) {
//            $PrizesModel = new MybLkMaterialRelationService();
//            $PrizesModel->lkid = $lkid;
//            $PrizesModel->zp_type = $zp_type;
//            $PrizesModel->newsid = $val;
//            $PrizesModel->ctime = time();
//            $PrizesModel->status = 1;
//            //$PrizesModel->zdtime = 0;
//            $PrizesModel->save();
//        }
//        //修改完毕情况缓存
//        $redis = Yii::$app->cache;
//        $rediskey = "article_list_" . $lkid . '_' . $zp_type;
//        $redis->delete($rediskey);
//        echo 1;
//        exit;
    }

}
