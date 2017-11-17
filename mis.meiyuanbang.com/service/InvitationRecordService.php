<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\InvitationRecord;
use common\models\myb\InvitationActivity;
use common\models\myb\InvitationPrizes;
use common\models\myb\UserToken;
use common\models\myb\User;
use common\redis\Cache;

/**
 * 邀请活动列表
 * @author Administrator
 *
 */
class InvitationRecordService extends InvitationRecord {
    /**
     * 分页获取所有邀请活动列表
     */
    public static function getByPage($invitation_id) {
        $query = parent::find()->where(['status' => 1, 'invitation_id' => $invitation_id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.*', 'b.umobile'])
                ->from(parent::tableName() . ' a')
                ->innerJoin('ci_user b', 'a.invitation_uid=b.id')
                ->where(['a.status' => 1, 'a.invitation_id' => $invitation_id])   //正常
                ->andWhere(['b.register_status' => 0])  //正常用户
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('record_id DESC')
                ->all();

        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 获取生效时间
     * @param int $invitationid  邀请活动id
     * @param int $uid  邀请人uid
     */
    static public function getTakeTime($invitationid, $uid) {
        $invitation = InvitationActivity::findOne(['invitation_id' => $invitationid]);
        $userTakeTime = UserToken::find()->where(['uid' => $uid])//->andWhere(['>=', 'create_time', $invitation->btime])->andWhere(['<=', 'create_time', $invitation->etime])
                ->andWhere(['is_valid' => 1])
                        ->orderBy('create_time')->limit(1)->asArray()->one();
        //->createCommand()->getRawSql();//
        if ($userTakeTime['create_time']) {
            return $userTakeTime['create_time'];
        } else {
            return false;
        }
    }

    /**
     * 获取邀请被邀请人的奖品
     * @param int $invited_id 被邀请人奖品
     * @return array          邀请活动下面被邀请人的奖品
     */
    static public function getInviterRecord($invited_id, $uid) {
        return InvitationPrizes::find()->where((['prizes_id' => $invited_id]))->andWhere(['status' => 1])->asArray()->one();
    }

    /**
     * 
     * @param int $uid 根据被邀请人电话来获取邀请人的手机号
     */
    static public function getUserPhone($uid, $invitation_id) {
        $phone = User::find()->select('umobile')->where(['id' => $uid])->andWhere(['register_status' => 0])->one();
        return (new \yii\db\Query())
                        ->select(['b.umobile'])
                        ->from(parent::tableName() . ' a')
                        ->innerJoin('ci_user b', 'a.invitation_uid=b.id')
                        ->where(['a.status' => 1, 'a.invitation_id' => $invitation_id])   //正常
                        ->andWhere(['a.invitee_phone' => $phone['umobile']])  //正常用户
                        ->andWhere(['b.register_status' => 0])  //正常用户
                        ->limit(1)
                        ->orderBy('record_id DESC')
                        ->one();
    }

    /**
     * 判断用户是否已经注册或被邀请过
     * @param INT $invitation_id 活动id
     * @param INT $phone 被邀请人电话
     * @param INT $uid   邀请人id
     */
    static public function getUserPhoneRegistered($invitation_id, $phone, $uid) {
        $userData = User::find()
                        ->where(['>', 'id', 0])
                        ->andWhere(['umobile' => $phone])
                        ->andWhere(['register_status' => 0])->count();
        //用户已经注册过
        if ($userData) {
            return 2;
        }
        
        
        # ->createCommand()->getRawSql();//
        $activityNormal = self::find()
                        ->where(['invitation_id' => $invitation_id])
                        ->andWhere(['invitee_phone' => $phone])
                        ->andWhere(['status' => 1])->count();
        //已经被邀请过
        if ($activityNormal) {
            return 1;
            //没有被邀请，判断是否注册过
        } else {
            $model = new InvitationRecordService();
            $model->invitation_id = $invitation_id;
            $model->invitee_phone = $phone;
            $model->invitation_uid = $uid;
            $model->ctime = time();
            $model->status = 1;
            $model->invitee_uid = 0;
            if ($model->save()) {
                return 3;
            }
        }
        
    }
    
    /**
     *  活动是否有效
     * @param type $invitation_id
     */
    static public function getActiveEffective($invitation_id){
        return InvitationActivityService::find()->where(['<=', 'btime', time()])->andWhere(['>=', 'award_time', time()])->andWhere(['invitation_id'=>$invitation_id])->asArray()->one();
    }

    //      
}
