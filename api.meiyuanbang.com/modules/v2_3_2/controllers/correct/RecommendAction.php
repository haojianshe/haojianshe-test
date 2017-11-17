<?php
namespace api\modules\v2_3_2\controllers\correct;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectService;

class RecommendAction extends ApiBaseAction{
    public  function run(){
    	$ret = [];
    	
        //获取排行榜批改id
        $data=CorrectService::getCorrectScoreRankRedis();
        /*$ret = ['1' => "色彩", '4' => "素描",'5' => "速写"];*/
        $tmp = json_decode($data);
        if($tmp && $tmp->f4 && count($tmp->f4)>0){
        	$tmp1 =  CorrectService::getFullCorrectInfo($tmp->f4[0]->correctid,$this->_uid);
        	$tmp1['ranktype'] = '素描';
        	$ret[] = $tmp1;
        }
        if($tmp && $tmp->f1 && count($tmp->f1)>0){
        	$tmp1 =  CorrectService::getFullCorrectInfo($tmp->f1[0]->correctid,$this->_uid);
        	$tmp1['ranktype'] = '色彩';
        	$ret[] = $tmp1; 
        }        
        if($tmp && $tmp->f5 && count($tmp->f5)>0){
        	$tmp1 =  CorrectService::getFullCorrectInfo($tmp->f5[0]->correctid,$this->_uid);
        	$tmp1['ranktype'] = '速写';
        	$ret[] = $tmp1;
        }
        //返回数据
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}