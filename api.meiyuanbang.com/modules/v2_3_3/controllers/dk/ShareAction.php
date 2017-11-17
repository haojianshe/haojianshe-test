<?php
namespace api\modules\v2_3_3\controllers\dk;
use Yii;
use api\components\ApiBaseAction;
use api\service\DkPrizeShareService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 大咖活动分享抽奖接口
 */
class ShareAction extends ApiBaseAction {
    public function run() {
        //活动id
        $activityid = $this->requestParam('activityid', true);
        //用户id
        $uid=$this->_uid;

        $type = $this->requestParam('oauth_type', true);

        $rec=DkPrizeShareService::find()->where(['activityid'=>$activityid])->andWhere(["uid"=>$uid])->andWhere(['type'=>$type])->andWhere(['>','ctime',strtotime(date("Y-m-d 0:0:0",time()))])->andWhere(['<','ctime',strtotime(date("Y-m-d 24:0:0",time()))])->one();

        if($rec){
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        }else{
            $model=new DkPrizeShareService();
            $model->activityid=$activityid;
            $model->uid=$uid;
            $model->type=$type;
            $model->ctime=time();
            $model->status=1;
            $res=$model->save();
            if($res){
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
            }else{
                $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
            }
        }

    }

}
