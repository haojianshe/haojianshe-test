<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use common\models\myb\RecommendBookAdv;
use mis\service\PublishingBookService;

/**
 * 修改添加推荐
 */
class AddadvbookAction extends MBaseAction {

    public $resource_id = 'operation_publish';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $uid = $request->post('uid');
        $typeid = $request->post('typeid');
        $status = $request->post('status');
        $value = $request->post('value');
        if (!$uid || !is_numeric($uid)) {
            die('参数不正确');
        }
        //添加
        if ($status == 1) {
            $model = new RecommendBookAdv();
        } else {
            //取消
            $model = RecommendBookAdv::findOne(['uid' => $uid, 'adv_type' => $typeid, 'bookid' => $value]);
            $advid = $model->advid;
        }
        if ($model) {
            if ($status == 2) {
                $model->status = 2;
                $model = RecommendBookAdv::deleteAll(['uid' => $uid, 'adv_type' => $typeid, 'bookid' => $value]);
            } else {
                $model->status = 1;
                $model->adv_type = $typeid;
                $model->uid = $uid;
                $model->bookid = $value;
                $model->ctime = time();
                $model->listorder = 0;
                $ret = $model->save();
                $advid = $model->attributes['advid'];
            }
            #去掉图书列表和单个图书编辑的缓存
            PublishingBookService::setCaheBook('', $advid, 2);
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
