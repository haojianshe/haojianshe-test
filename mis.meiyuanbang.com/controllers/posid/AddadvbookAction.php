<?php

namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use common\models\myb\RecommendBook;
use mis\service\PublishingBookService;

/**
 * 修改添加推荐
 */
class AddadvbookAction extends MBaseAction {

    public $resource_id = 'operation_posid';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $typeid = $request->post('typeid');
        $value = $request->post('value');
        $status = $request->post('status');
        if (!$typeid || !is_numeric($typeid)) {
            die('参数不正确');
        }
        //添加
        if ($status == 1) {
            $model = new RecommendBook();
        } else {
            //取消
            $model = RecommendBook::findOne(['f_catalog_id' => $typeid, 'bookid' => $value]);
        }
        if ($model) {
            if ($status == 2) {
                $model->status = 2;
                $model = RecommendBook::deleteAll(['f_catalog_id' => $typeid, 'bookid' => $value]);
            } else {
                $model->status = 1;
                $model->f_catalog_id = $typeid;
                $model->bookid = $value;
                $model->ctime = time();
                $ret = $model->save();
            }
             #去掉图书列表和单个图书编辑的缓存
            PublishingBookService::setCaheBook('', $typeid, 5);
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
