<?php
namespace api\modules\v1_3\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserDetailService;
use api\service\UserRelationService;
use common\service\DictdataService;

/**
 * 找画友列表
 * @author Administrator
 *
 */
class FriendListAction extends ApiBaseAction
{
	public function run()
    {
    	//每页返回记录个数
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}
    	$lastuid = $this->requestParam('lastuid');
    	if(!$lastuid){
    		$lastuid = 0;
    	}
    	//用户性别
    	$gender = $this->requestParam('gender');
    	if($gender!=='0' && $gender!=1){
    		$gender = -1;
    	}
    	//用户地区
    	$provinceid = 0;
    	//判断最后一个用户
    	if($this->_uid != -1){
    		$usermodel = UserDetailService::getByUid($this->_uid);
    		if($usermodel && $usermodel['provinceid']){
    			$provinceid = $usermodel['provinceid'];
    		}
    	} 
    	$ret=[];
    	//(1)获取推荐的人名单
    	$ids = UserDetailService::getFriendIdsList($this->_uid, $provinceid,$gender,$lastuid, $rn);
    	//(2)如果取到的人比rn少，如果之前根据省市获取的，则放弃这批用户重新获取
    	if(count($ids)<$rn && $provinceid!=0){
    		//不够一页的情况
    		if(count($ids)>0){ 
    			$lastuid = 0;
    		}
    		$ids = UserDetailService::getFriendIdsList($this->_uid, $provinceid,$gender, $lastuid, $rn,true);
    	}
    	//(3)获取用户信息
    	foreach ($ids as $k=>$v){
    		//获取基本信息
    		$model = UserDetailService::getByUid($v['uid']);
    		//互动条数
    		$model['pmsgnum'] = 0;
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
