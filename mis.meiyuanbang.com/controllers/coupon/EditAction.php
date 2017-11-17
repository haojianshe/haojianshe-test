<?php

namespace mis\controllers\coupon;

use Yii;
use mis\components\MBaseAction;
use mis\service\CouponService;

/**
 * 编辑
 */
class EditAction extends MBaseAction {
    public $resource_id = 'operation_coupon';
    public function run() {
        $request = Yii::$app->request;
        $isclose = false;
        $msg = '';
       
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $couponid = $request->get('couponid');
            if ($couponid) {
                if (!is_numeric($couponid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = CouponService::findOne(['couponid' => $couponid]);
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg,  ]);
            } else {
                $model = new CouponService();
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //编辑
                $model = CouponService::findOne(['couponid' => $request->post('CouponService')['couponid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                //插入
                $model = new CouponService();
                $model->load($request->post());
                //$model->status = 3;
                //添加创建时间
                $model->ctime = time();
            }
            $model->btime = strtotime($model->btime);
            $model->etime = strtotime($model->etime);
            //操作员
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
               $msg=json_encode($model->getErrors());
                //$msg = '保存失败';
            }
            return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
        }
    }

}
