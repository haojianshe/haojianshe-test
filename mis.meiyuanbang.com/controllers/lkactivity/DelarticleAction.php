<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\NewsService;
use common\service\DictdataService;
use common\models\myb\LkMaterialRelation;
/**
 * 删除活动方法
 */
class DelarticleAction extends MBaseAction {

    public $resource_id = 'operation_activity';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $newsid = $request->post('newsid');
        # 2/3 状元分享会/名师大讲堂/联考攻略',
        $result = LkMaterialRelation::find()->select('lkid,zp_type')->where(['newsid' => $newsid, 'status' => 1])->Asarray()->all();
        $html = "文章已添加到以下位置，请取消关联后再删除文章：<br/>";
        if (!empty($result)) {
            foreach ($result as $key => $val) {
                if ($val['zp_type'] == 2) {
                    $title = '名师大讲堂';
                } elseif ($val['zp_type'] == 3) {
                    $title = '联考攻略';
                }
                foreach (DictdataService::getProvince() as $k => $v) {
                    if ($v['provinceid'] == $val['lkid']) {
                        $html .=$v['provincename'] . "联考活动-$title<br/>";
                    }
                }
            }
            return $this->controller->outputMessage([ 'msg' => $html, 'errno' => 2,]);
        }

        $status = $request->post('status');
        if (!$newsid || !is_numeric($newsid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = NewsService::findOne(['newsid' => $newsid]);
        if ($model) {
            $model->status = $status;
            $ret = $model->save();
            if ($ret) {
                $redis = Yii::$app->cache;
                $rediskey = "activity_article_" . $newsid;
                $redis->delete($rediskey);
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
