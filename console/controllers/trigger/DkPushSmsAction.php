<?php
namespace console\controllers\trigger;

use Yii;
use yii\base\Action;
use common\models\myb\DkActivity;
use mis\service\DkCorrectService;
use mis\service\UserService;

use mis\service\DkPushSmsService;
use common\service\SmsService;

/**
 * 大咖改画群发任务
 */
class DkPushSmsAction extends Action
{
    public function run()
    {
        $query=new \yii\db\Query();
        $activity=$query->select("dk_push_sms.*,dk_activity.*")->from("dk_activity")->leftJoin("dk_push_sms","sid=activityid")->where(['dk_activity.status'=>1])->andWhere(['dk_push_sms.status'=>2])->andWhere(["<","dk_push_sms.ptime",time()])->all();
        //$activity=DkActivity::find()->where(["<","activity_etime",time()])->andWhere(['status'=>1])->all();
        if($activity){
            foreach ($activity as $key => $value) {
                    $sms=DkPushSmsService::find()->where(["sid"=>$value['activityid']])->one();
                    //获取用户发送短信息
                    $uids=DkCorrectService::getSubmitUids($value['activityid']);

                    $mobiles_arr=UserService::getMobileByUids($uids);
                    $modules_str=implode(",",$mobiles_arr);
                    $return_obj=SmsService::SendMobileSms($modules_str,$sms->content,2);
                    //保存返回数据
                    if($return_obj->taskID){
                        $sms->taskid=json_encode($return_obj->taskID);
                    }
                    $sms->status=3;
                    $sms->message=json_encode($return_obj->message);
                    $sms->successcounts=$return_obj->successCounts;
                    $sms->returnstatus=json_encode($return_obj->returnstatus);
                    $sms->totalcounts=count($mobiles_arr);
                    $sms->mobiles=implode(",",$mobiles_arr);
                    $sms->save();
            }
        }
    }
}