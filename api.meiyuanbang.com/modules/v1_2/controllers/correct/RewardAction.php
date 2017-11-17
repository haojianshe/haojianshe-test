<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\CorrectService;
use api\service\UserCorrectService;
use api\service\UserCoinService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 批改打赏
 */
class RewardAction extends ApiBaseAction
{   
    public function run()
    {
        $correctid=$this->requestParam('correctid',true);
        $rewardnum=$this->requestParam('rewardnum',true);
        $model =  CorrectService::findOne(['correctid' => $correctid]);
        if($model->rewardnum !=0){
            $data['message']='已经打赏';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
            //die('已经打赏');
        }
        if($model->status!=1){
            die('批改未完成');
        }
        if($this->_uid != $model->submituid){
            die('用户无权限');
        }
        //减积分
        $usercoin=UserCoinService::getByUid($this->_uid);
        $coin=$usercoin['remain_coin']-$rewardnum;
        //判断积分是否足够
        if($coin<0){
            $data['message']='金币不足';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        $usercoinmodel=UserCoinService::findOne(['uid'=>$this->_uid]);
        $usercoinmodel->remain_coin=$coin;
        $usercoinmodel->save();

        $model->rewardnum=$rewardnum;
        $model->save();
        //更改批改被奖赏的金币数
        $user_correct=UserCorrectService::findOne(['uid'=>$model->teacheruid]);
        $user_correct->gaincoin=$user_correct->gaincoin+$rewardnum;
        $user_correct->save();

        $data['correctid']= $model->attributes['correctid'];
        $data['message']='';
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
