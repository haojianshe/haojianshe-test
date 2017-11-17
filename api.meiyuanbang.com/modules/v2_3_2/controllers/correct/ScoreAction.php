<?php
namespace api\modules\v2_3_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectService;
use api\service\UserCorrectService;
use api\service\TweetService;
use api\service\CapacityModelService;
use common\service\dict\CapacityModelDictDataService;

/**
 *  
 * 打分
 */
class ScoreAction extends ApiBaseAction
{
	public function run()
    {
        $correctid=$this->requestParam('correctid',true);
        $score=$this->requestParam('score');
        //获取批改实体和帖子实体
        $model =  CorrectService::findOne(['correctid' => $correctid]);
        $tweetModel= TweetService::findOne(['correctid'=>$correctid]);
        if($this->_uid!=$model->teacheruid){
            die('用户无权限');
        }        
        $data=[];
        //处理打分
        if($score && $tweetModel->f_catalog_id){
        	//获取平均分
        	$teacherscore = json_decode($score,true);
        	if($teacherscore && count($teacherscore)>0){
	        	$itemlist = CapacityModelDictDataService::getCorrectScoreItemByMainId($tweetModel->f_catalog_id);
	        	$correctscore = CapacityModelService::calScore($teacherscore, $itemlist);
	        	$data['score'] = $correctscore;
        	}
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }  
}