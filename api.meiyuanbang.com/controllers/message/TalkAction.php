<?php
namespace api\controllers\message;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\MessageService;
use api\service\UserDetailService;
use common\models\myb\Message;
use common\service\CommonFuncService;
/**
 * 两人私信对话列表
 */
class TalkAction extends ApiBaseAction
{   
    public function run()
    {
    	//页数
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		$rn = 10;
    	}
    	//私信对话人
    	$otheruid = $this->requestParam('msg_uid',true);
    	$gettype = $this->requestParam('type');
		if($gettype && $gettype == 'next'){
			//分页获取
			$lastid = $this->requestParam('last_mid',true);
		}
		else{
			//获取第一页
			$lastid = 0;
		}
		$ret['content']=[];
		$ret['last_read_mid'] = 1; //客户端未使用
    	//得到对话列表
    	$talklist = MessageService::getTalkList($this->_uid, $otheruid, $lastid, $rn);
    	//将数据翻转成时间正序
    	$talklist = array_reverse($talklist);
    	//数组中添加用户信息
    	if($talklist){
    		$usermodel = UserDetailService::getByUid($this->_uid);
    		$otherusermodel = UserDetailService::getByUid($otheruid);	
    		foreach ($talklist as $k=>$v){
    			//添加用户信息
    			if($usermodel['uid']==$v['from_uid']){
    				$curuser = $usermodel;
    			}
    			else{
    				$curuser = $otherusermodel;
    			}
    			$v['sname'] = $curuser['sname'];
    			$v['avatar'] = $curuser['avatar'];
    			$v['ukind'] = $curuser['ukind'];
    			$v['ukind_verify'] = $curuser['ukind_verify'];
    			//处理图片
    			if($v['mtype']==1){
    				$resource = json_decode($v['content'],true);
    				$resource['t'] = CommonFuncService::getPicByType($resource['n'], 't');
    				//description和rid只为兼容老版本程序
    				$resource['description'] = '';
    				$resource['rid'] = 1;
    				$v['resource'] = $resource;
    			}
    			else if($v['mtype']==2){
    				//处理声音按文字处理,老版本不会处理语音私信
    				$v['mtype'] = 0;
    				$v['content'] = '语音私信,请升级app后收听';
    			}
    			
    			$ret['content'][] = $v;
    		}
    	}
    	//第一次进入私信对话页面时清除小红点
    	if($lastid==0){
    		MessageService::removeRed($this->_uid, $otheruid);
    	}
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
