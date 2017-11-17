<?php
namespace api\modules\v1_3\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserDetailService;
use api\service\UserRelationService;

/**
 * 找名师
 * @author Administrator
 *
 */
class FamousSearchAction extends ApiBaseAction
{
	public function run()
    {
    	//每页返回记录个数
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}
    	//分页
    	$lastuid = $this->requestParam('lastuid');
    	if(!$lastuid){
    		$lastuid = 0;
    	}
    	//用于搜索的用户名
    	$sname = $this->requestParam('sname',true);    
    	$ret=[];
    	//(1)获取id列表
    	$ids = UserDetailService::getIdsList($sname, $lastuid, $rn);
    	//(2)获取用户信息
    	foreach ($ids as $k=>$v){
    		//获取基本信息
    		$model = UserDetailService::getByUid($v['uid']);
    		//关注关系
    		if($this->_uid==-1){
    			$model['follow_type'] = 0;
    		}
    		else{
    			$model['follow_type'] = 0;
    			$model['follow_type'] = UserRelationService::getBy2Uid($this->_uid, $v['uid']);
    		}
    		$ret[] = $model;
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
