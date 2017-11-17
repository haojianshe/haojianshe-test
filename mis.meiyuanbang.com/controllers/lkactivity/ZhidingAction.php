<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\LkActivityService;
use mis\service\MybLkMaterialRelationService;

/**
 * 状元分享会问答置顶
 */
class ZhidingAction extends MBaseAction {

    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        $msg = '';
        $usermodel = \Yii::$app->user->getIdentity();
        //先获取model
        $lkid = $request->post('lkid');
        $status = $request->post('status');
        $zp_type = $request->post('zp_type');
        $newsid = $request->post('newsid');
        $model = new MybLkMaterialRelationService();
        $model = MybLkMaterialRelationService::findOne(['lkid' => $lkid, 'newsid' => $newsid, 'zp_type' => $zp_type]);
        $model->lkid = $lkid;
        $model->zp_type = $zp_type;
        $model->newsid = $newsid;
        $model->status = 1;
        if ($status) {
            $model->zdtime = time();
        } else {
            $model->zdtime = 0;
        }
        if ($model->save()) {
            $redis = Yii::$app->cache;
            #去掉对应的缓存
            $rediskey = "article_list_" . $lkid . '_' . $zp_type;
            $redis->delete($rediskey);

            #去掉所有的缓存
            $rediskeyAll = "article_list_" . $lkid . '_all';
            $redis->delete($rediskeyAll);

            #去掉文章/问答缓存
            if ($zp_type == 1) {
                $rediskey2 = "activity_qa_$newsid";
            } else {
                $rediskey2 = "activity_article_$newsid";
            }
            $redis->delete($rediskey2);

            $redKey = "lk_news_" . $lkid . "_" . $newsid;
            $redis->delete($redKey);
            
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }
    }

}
