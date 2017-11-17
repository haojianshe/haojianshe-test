<?php
namespace api\components\filters;

use Yii;
use yii\base\ActionFilter;
use api\service\BlackListService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 黑名单检查
 * 黑名单需要用户登录后才能检查，所以应该放在login或者tokenfilter之后
 */
class BlackFilter extends ActionFilter
{
	/**
	 * 暂时未加缓存
	 * (non-PHPdoc)
	 * @see \yii\base\ActionFilter::beforeAction()
	 */
    public function beforeAction($action)
    {
    	//黑名单需要用户先登录状态
    	if($action->_uid <=0){
    		$action->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL);
    	}
    	//查看用户是否在黑名单中
    	$model = BlackListService::findOne(['uid' => $action->_uid]);
    	if($model){
    		//在黑名单内,返回身份错误
    		$action->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL);
    	}
    	return true;
    }
}