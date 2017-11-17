<?php
namespace api\components\filters;

use Yii;
use yii\base\ActionFilter;
use api\service\UserTokenService;

/**
 * token检查过滤器
 */
class TokenFilter extends ActionFilter
{
    /**
     * 在cation执行前检查token
     * token有效时,只是负责把用户id赋值
     */
    public function beforeAction($action)
    {
    	$request = Yii::$app->request;
    	
    	//判断token参数
    	$token = $action->requestParam('token');
    	if(!$token){
    		//没有token参数的情况
    		return true;
    	}
    	//获取到token对象
    	$model = UserTokenService::getByToken($token);
    	if($model){
    		$action->_uid = $model['uid'];
    	}
    	return true;
    }
}