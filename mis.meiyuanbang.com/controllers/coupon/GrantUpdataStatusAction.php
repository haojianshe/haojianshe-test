<?php
namespace mis\controllers\coupon;

use Yii;
use mis\components\MBaseAction;
use mis\service\CouponService;
use mis\service\CouponGrantService;
use mis\service\UserCouponService;
/**
 * 删除
 */
class GrantUpdataStatusAction extends MBaseAction
{	
	public $resource_id = 'operation_coupon';
	
    /**
     * 只支持post删除
     */
    public function run()
    {
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
        $mis_userid = Yii::$app->user->getIdentity()->mis_userid;

    	//检查参数是否非法
    	$coupongrantid = $request->post('coupongrantid');
        $status = $request->post('status');
        if(!($status==3 || $status==2)){
            die('参数不正确');
        }
    	if(!$coupongrantid || !is_numeric($coupongrantid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = CouponGrantService::findOne(['coupongrantid' => $coupongrantid]);
    	if($model){
            if($status==2){
                $model->mis_userid_audit =$mis_userid;
            }
    		$model->status =$status;
    		$ret = $model->save();
            if($status==2 && $model->granttype==0){
            	//实时发放在审核通过后立刻发放，预发放在后台任务延时处理
            	$uid_arr=explode(",", $model->uids);
                foreach ($uid_arr as $key1 => $value1) {
                   //增加发送多张课程卷的功能
                   for($i=0;$i<$model->num;$i++){
	                   	$umodel=new UserCouponService();
	                   	$umodel->uid=$value1;
	                   	$umodel->couponid=$model->couponid;
	                   	$umodel->coupongrantid=$model->coupongrantid;
	                   	$umodel->save();
                   }
                }
                $couponinfo=CouponService::find()->where(['couponid'=> $model->couponid])->asArray()->one();
                UserCouponService::couponPushMsg($uid_arr, $model->couponid,$couponinfo['coupon_name']);
            }
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'更改状态失败']);
    }
}
