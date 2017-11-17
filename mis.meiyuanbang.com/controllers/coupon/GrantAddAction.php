<?php

namespace mis\controllers\coupon;

use Yii;
use mis\components\MBaseAction;
use mis\service\CouponGrantService;
use mis\service\UserService;
use mis\service\UserCouponService;
use common\service\CommonFuncService;

/**
 * 编辑
 */
class GrantAddAction extends MBaseAction {
    public $resource_id = 'operation_coupon';

    public function run() {
        $mis_userid = Yii::$app->user->getIdentity()->mis_userid;
        $request = Yii::$app->request;
        $isclose = false;
        $msg = '';
       
        if (!$request->isPost) {
            //get访问，判断是grantadd还是add,返回不同界面
            $couponid = $request->get('couponid');
           
            $model = new CouponGrantService();
            $model->couponid=$couponid;
            //默认发一张
            $model->num = 1;
            return $this->controller->render('grantadd', ['model' => $model, 'msg' => $msg]);
        } else {
             //插入
            $model = new CouponGrantService();
            $model->load($request->post());
            $ids = $this->requestParam('ids');
            //过滤不同平台的回车换行
            $ids = str_replace(array("/r", "/n", "/r/n"), "", $ids);
            $model->granttype = $this->requestParam('granttype');
            if($model->granttype==0){
            	//立刻发放课程卷，根据用户id发放
            	$model->uids = $ids;
            	$uid_arr=explode(",", $ids);
            	foreach ($uid_arr as $key => $value) {
            		$search=UserService::findOne(['uid'=>$value]);
            		if(!$search){
            			$msg='找不到用户id为 ：'.$value.'的用户！';
            			return $this->controller->render('grantadd', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
            		}
            	}
            } 
            else{
            	//预发放，根据手机号发放,此时不用判断手机号已注册,只需判断手机号是否正常
            	$model->mobiles = $ids;
            	$model->waiting_grant_mobiles = $ids;
            	$mobile_arr=explode(",", $ids);
            	foreach ($mobile_arr as $key => $value) {
            		if(!CommonFuncService::check_mobile($value)){
            			$msg=$value.'不是正确的手机号！';
            			return $this->controller->render('grantadd', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
            		}
            	}
            }            
            //$model->status = 3;
            //添加创建时间
            $model->ctime = time();
            $model->mis_userid_grant=$mis_userid;
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
               $msg=json_encode($model->getErrors());
                //$msg = '保存失败';
            }
            return $this->controller->render('grantadd', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
        }
    }
}
