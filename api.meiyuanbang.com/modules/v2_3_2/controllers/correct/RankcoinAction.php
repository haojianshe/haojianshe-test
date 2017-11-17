<?php
namespace api\modules\v2_3_2\controllers\correct;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectService;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\CointaskService;
use api\service\UserCoinService;

/**
 * 定时任务，给用户加金币
 * @author Administrator
 *
 */
class RankcoinAction extends ApiBaseAction{
    public  function run(){
    	
    	$data=CorrectService::getCorrectScoreRankRedis();
        $rankData = json_decode($data);
        
        foreach ($rankData->f1 as $key => $value) {
        	$model = CorrectService::getFullCorrectInfo($value->correctid,$this->_uid);
        	$this->checkCoin($model['submituid'], $model['correctid'],$model['tid']);
        }
        foreach ($rankData->f4 as $key => $value) {
        	$model = CorrectService::getFullCorrectInfo($value->correctid,$this->_uid);
        	$this->checkCoin($model['submituid'], $model['correctid'],$model['tid']);
        }
        foreach ($rankData->f5 as $key => $value) {
        	$model = CorrectService::getFullCorrectInfo($value->correctid,$this->_uid);
        	$this->checkCoin($model['submituid'], $model['correctid'],$model['tid']);
        }
        //返回数据
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    }
    
    private function checkCoin($uid,$correctId,$tid){
    	if(CointaskService::IsAddRank($uid, $correctId)){		
    		$tasktype = CointaskTypeEnum::RANK_LIST;
    		$coinCount = CointaskDictService::getCoinCount($tasktype);
    		//加金币
    		UserCoinService::addCoinNew($uid, $coinCount);
    		//发通知给用户
    		CorrectService::rankPushMsg(1, $uid, $tid);
    	}
    }
}