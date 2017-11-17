<?php
namespace api\modules\v2_3_5\controllers\lk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LkService;
use api\service\LkPaperService;
/**
 * 联考模拟考信息
 */
class GetInfoAction extends ApiBaseAction
{
    public function run()
    {   
        $lkid = $this->requestParam('lkid',true); 
        $data=LkService::getLkDetail($lkid);
        $uid=$this->_uid;
        //分享状态  2/1 已参加/未参加
        $data['is_submit']=1;
        //判断
        if($uid>0){
            $paper=LkPaperService::find()->where(['lkid'=>$lkid])->andWhere(["uid"=>$uid])->andWhere(['status'=>1])->asArray()->one();
            if($paper){
                $data['is_submit']=2;
            }
        }
        $data['lk_share_url']=Yii::$app->params['sharehost']."/mactivity/lk/index?lkid=".$lkid;
        $data['paper_share_url']=Yii::$app->params['sharehost']."/mactivity/lk/submit_paper?lkid=".$lkid;
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
