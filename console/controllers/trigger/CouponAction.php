<?php
namespace console\controllers\trigger;

use Yii;
use yii\base\Action;
use console\service\CouponGrantService;
use console\service\CouponService;
use console\service\UserCouponService;
use console\service\UserService;

/**
 * 检查预发放的课程卷的定时任务
 */
class CouponAction extends Action
{
    public function run()
    {
    	//(1)获取所有未发放完的预发放课程卷
    	$grantModels = CouponGrantService::getPreGrant();
    	//(2)按名单发放课程卷
    	foreach ($grantModels as $k=>$grantModel){
    		$noregmobiles='';
    		$mobile_arr=explode(",", $grantModel->waiting_grant_mobiles);
    		$couponinfo=CouponService::find()->where(['couponid'=> $grantModel->couponid])->asArray()->one();
    		foreach ($mobile_arr as $k1 => $mobile) {
    			//根据手机号获取用户id
    			$mobile_model=UserService::findOne(['umobile'=>$mobile,"register_status"=>0]);
    			if(empty($mobile_model)){
    				//用户未注册
    				$noregmobiles .= $mobile;
    			}
    			else{
	    			//给用户发送一到多张课程卷
	    			for($i=0;$i<$grantModel->num;$i++){
	    				$umodel=new UserCouponService();
	    				$umodel->uid=$mobile_model->id;
	    				$umodel->couponid=$grantModel->couponid;
	    				$umodel->coupongrantid=$grantModel->coupongrantid;
	    				$umodel->status=1;
	    				$umodel->save();
	    			}	    			
	    			//消息推送	    			
	    			UserCouponService::couponPushMsg($mobile_model->id, $grantModel->couponid,$couponinfo['coupon_name']);
    			}    			
    		}
    		//(3)更新未发放名单
    		$grantModel->waiting_grant_mobiles = $noregmobiles;
    		$grantModel->save();
    	}
    }    
}