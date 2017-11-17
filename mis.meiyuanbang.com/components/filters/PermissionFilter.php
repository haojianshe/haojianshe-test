<?php
namespace mis\components\filters;

use Yii;
use yii\base\ActionFilter;
use mis\service\MisRoleResourceService;

/**
 * 权限检查过滤器
 * 验证用户是否对要进行的操作有权限
 */
class PermissionFilter extends ActionFilter
{
    /**
     * 在cation执行前检查用户权限
     * 定义了resource_id属性的action需要进行资源权限检查
     */
    public function beforeAction($action)
    {
    	$tplVar = ['code'=>'访问受限','message'=>'当前用户无权限进行此操作，请联系管理员授权'];
    	
    	if(isset($action->resource_id)){
    		//从配置文件读出资源id
    		$resource = Yii::$app->params['misresource'][$action->resource_id];
    		//获取用户角色
    		$model = Yii::$app->user->getIdentity();
    		//检查角色是否对资源有权限
    		if(!$model->roleids){
    			//用户未设置过角色，没有任何权限
    			Yii::$app->end($action->controller->render('/_common/_error', $tplVar));
    		}
    		//判断用户如果属于管理员则对任何功能都有权限,管理员角色默认为1
    		$roleids = explode(',', $model->roleids);
    		if (in_array(1, $roleids)){
    			return true;
    		}
    		//判断用户是否对资源有权限
    		$roleresource = MisRoleResourceService::getAllByRoleids($roleids);
    		foreach ($roleresource as $k=>$v){
    			if($v['resourceid']==$action->resource_id){
    				return true;
    			}
    		}
    		Yii::$app->end($action->controller->render('/_common/_error', $tplVar));
    	}
    	return true;
    }
}