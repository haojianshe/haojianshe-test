<?php

namespace console\controllers\trigger;

use Yii;
use yii\base\Action;
use common\models\myb\InvitationActivity;
#use common\models\myb\InvitationAwardRecord;
#use common\models\myb\InvitationPrizes;
use common\models\myb\InvitationRecord;
use common\models\myb\User;

/**
 * 
 */
class UserDetectionAction extends Action {

    public function run() {
        $activityData = InvitationActivity::find()->select('invitation_id')->where(['<=', 'btime', time()])->andWhere(['>=', 'award_time', time()])->asArray()->one();
        if ($activityData['invitation_id']) {
            $InvitationActivity = InvitationRecord::find()->select('invitee_phone')->where(['>', 'record_id', 0])->andWhere(["invitee_uid" => 0])->andWhere(["invitation_id" => $activityData['invitation_id']])->asArray()->all(); //->createCommand()->getRawSql();
            if (!empty($InvitationActivity)) {
                foreach ($InvitationActivity as $key => $val) {
                    $userPhone = User::find()->select('id')->where(['>', 'id', 0])->andWhere(["umobile" => $val['invitee_phone']])->andWhere(["register_status" => 0])->asArray()->one();
                    echo InvitationRecord::updateAll(['invitee_uid' => $userPhone['id']], ["invitee_phone" => $val['invitee_phone']]); //->createCommand()->getRawSql();
                }
            }
        }
    }

}
