<?php
namespace api\modules\v3_1_1\controllers\token;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserTokenService;

/**
 * 网页端Token有效检查防止多设备登录
 */
class MCheckAction extends ApiBaseAction {

    public function run() {
       	$request = Yii::$app->request;
    	$token = $this->requestParam('token',true);
    	$devicetype = $this->requestParam('devicetype');
    	//判断token参数
    	if(!$token){
    		//没有token参数的情况
    		$this->controller->renderJson(ReturnCodeEnum::ERR_TOKEN);
    	}
    	//获取到token对象
    	$model = UserTokenService::getByToken($token);
    	if(!$model){
    		//根据token没有获取到实体
    		$this->controller->renderJson(ReturnCodeEnum::ERR_TOKEN);
    	}

    	//判断token是否超时
    	$current_time = time();
    	$invalid_time = $model['invalid_time'];
    	if (intval($invalid_time) <= $current_time) {    		
    		//删掉数据库记录
    		$model = UserTokenService::findOne($token);
    		$model->delete();
    		//返回错误
    		$this->controller->renderJson(ReturnCodeEnum::ERR_TOKEN);
    	}
    	//更改缓存设备token
    	$this->UpdataTokenRides($model['uid'],$token);
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    }

    /*
    	验证token是否失效
     */
    private function UpdataTokenRides($uid,$token){
	    $rediskey="mobile_user_token_".$uid;
	    $redis = Yii::$app->cache;
	    // $redis->delete($rediskey);
	    $old_token=$redis->get($rediskey);
	    if(empty($old_token)){

	    	$redis->set($rediskey,$token);
	        $redis->expire($rediskey,3600*24*365*5);
	    }else{
	    	if($old_token!=$token){
	    		//设置老token 为过期
	    		$model = UserTokenService::findOne($old_token);
	    		$model->invalid_time=time();
    			$model->save();
    			//保存新的token到缓存
	    		$redis->set($rediskey,$token);
	    	    $redis->expire($rediskey,3600*24*365*5);
	    	}
	    }
    }

    
}
