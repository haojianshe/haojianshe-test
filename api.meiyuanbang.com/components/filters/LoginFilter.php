<?php
namespace api\components\filters;

use Yii;
use yii\base\ActionFilter;
use api\service\UserTokenService;
use api\service\UserRepeatloginService;
use api\lib\enumcommon\ReturnCodeEnum;
use yii\base\Model;

/**
 * 登录检查过滤器
 */
class LoginFilter extends ActionFilter
{
    /**
     * 在cation执行前检查用户是否登录
     */
    public function beforeAction($action)
    {
    	$request = Yii::$app->request;
    	$token = $action->requestParam('token');
    	$devicetype = $action->requestParam('devicetype');
    	
    	//判断token参数
    	if(!$token){
    		//没有token参数的情况
    		$action->controller->renderJson(ReturnCodeEnum::ERR_TOKEN);
    	}
    	//获取到token对象
    	$model = UserTokenService::getByToken($token);
    	if(!$model){
    		//根据token没有获取到实体
    		$action->controller->renderJson(ReturnCodeEnum::ERR_TOKEN);
    	}
    	//判断token是否超时
    	$current_time = time();
    	$invalid_time = $model['invalid_time'];
    	if (intval($invalid_time) <= $current_time) {    		
    		//删掉数据库记录
    		$model = UserTokenService::findOne($token);
    		$model->delete();
    		if($this->checkRepeatLoginPrompt($token)){
    			//3.1.1添加禁止多台设备使用统一账号功能后，返回10006错误标识
    			$action->controller->renderJson(ReturnCodeEnum::ERR_TOKEN_REPEAT);
    		}
    		else{
    			$action->controller->renderJson(ReturnCodeEnum::ERR_TOKEN);
    		}    		
    	}
    	//通过验证以后给action的uid属性赋值
    	if($model){
    		$action->_uid = $model['uid'];
    	}
    	//add by liq,在andriod和ios设备上检查用户是否多台设备重复登录    	
    	if($devicetype=='ios' || $devicetype=='android'){
    		$this->checkRepeatLogin($action->_uid, $devicetype, $token);
    	}
    	return true;
    }
    
    /**
     * 检查是否需要提示用户重复登录，需要返回true，否则返回false
     * @param unknown $token
     */
    private function checkRepeatLoginPrompt($token){
    	$repeatModel = UserRepeatloginService::getNeedPromptToken($token);
    	if($repeatModel){
    		$repeatModel->isprompt=1;
    		$repeatModel->save();
    		return true;
    	}
    	return false;    	
    }
    
    /**
     * 检查账号是否在多台设备上使用
     * 只对andriod和ios进行检查
     */
    private function checkRepeatLogin($uid,$devicetype,$currentToken){
    	//获取用户上一次登录时的token
    	$tokenModel = UserRepeatloginService::getUserLastToken($uid);
    	//(1)第一次访问未记录过token
    	if($tokenModel == null){
    		//token第一次出现, 写数据库
    		UserRepeatloginService::insertBySql($uid, $currentToken, $devicetype);
    		//写缓存
    		UserRepeatloginService::saveCurTokenToCache($uid, $currentToken, time());
    		return;
    	}
    	//(2)与上一次token一致
    	$lastToken =$tokenModel['token'];
    	$curtime = time();
    	if($lastToken==$currentToken){
    		//与上次是统一token，证明用户没有换设备，每3分钟更新用户最后访问时间
    		if($curtime-$tokenModel['logintime']>3*60){
    			//在数据库和缓存中更新用户最后登录时间
    			UserRepeatloginService::updateLastLoginTime($uid, $currentToken);
    			UserRepeatloginService::saveCurTokenToCache($uid, $currentToken, $curtime);
    		}
    		return;
    	}
    	//(3)与上次token不一致，用户在另一台机器登录
    	//把前一次登录的token设置为失效，invalid_time设置为当前时间即可
    	$tokenmodel = UserTokenService::findOne($lastToken);
    	if($tokenmodel){
    		//解决有时token删除后没有修改重复登录数据的问题
    		$tokenmodel->invalid_time = $curtime;
    		$tokenmodel->save();
    	}    	
    	//重复登录表中记录重复时间
    	UserRepeatloginService::updateRepeatTime($uid, $lastToken);
    	//把最新的token写入数据库和缓存
    	UserRepeatloginService::insertBySql($uid, $currentToken, $devicetype);
    	UserRepeatloginService::saveCurTokenToCache($uid, $currentToken, $curtime);
    }
}