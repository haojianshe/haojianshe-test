<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\InvitationRecord;
use common\models\myb\InvitationAwardRecord;
use common\models\myb\InvitationActivity;
use common\models\myb\UserToken;
use common\models\myb\User;
use common\redis\Cache;

/**
 * 邀请活动列表
 * @author Administrator
 *
 */
class InvitationAwardRecordService extends InvitationAwardRecord {
    /**
     * 分页获取所有邀请活动列表
     */
    public static function getByPage($invitation_id) {
        $query = parent::find()->where(['invitation_id' => $invitation_id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.*', 'b.umobile','c.title as prizes_title'])
                ->from(parent::tableName() . ' a')
                ->innerJoin('ci_user b', 'a.award_uid=b.id')
                ->innerJoin('myb_invitation_prizes c', 'c.prizes_id=a.prizes_id')
                ->where(['invitation_id' => $invitation_id])   //正常
                ->andWhere(['b.register_status' => 0])  //正常用户
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('award_id DESC')
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
        $userTakeTime = UserToken::find()->where(['uid' => $uid])->andWhere(['>=', 'create_time', $invitation->btime])->andWhere(['<=', 'create_time', $invitation->award_time])->andWhere(['is_valid' => 1])
                        ->orderBy('create_time')->limit(1)->asArray()->one();
        //->createCommand()->getRawSql();//
        if ($userTakeTime['create_time']) {
            return $userTakeTime['create_time'];
        } else {
            return false;
        }
    }

    /**
     * 用户邀请记录表中奖品的个数之和
     */
    static public function getUserAward($uid) {
        $prize_ids = self::find()->select('prizes_id')->where(['award_uid' => $uid])->asArray()->all();
        $array = [];
        if ($prize_ids) {
            foreach ($prize_ids as $key => $val) {
                $array[$key] = $val['prizes_id'];
            }
        } else {
            return 0;
        }
        #print_R($array);
        $prizesNumber = InvitationPrizesService::find()->select('number')->where(['prizes_type' => 2])->andWhere(['in', 'prizes_id', $array])->andWhere(['status' => 1])->asArray()->all();
        #print_R($prizesNumber);
        #exit;
        $prizeCount = 0;
        if ($prizesNumber) {
            foreach ($prizesNumber as $k => $v) {
                $prizeCount+=$v['number'];
            }
        }
        return $prizeCount;
    }

    /**
     * 判断该活动下面的次奖品是否已经领取过
     * @param int  $uid           用户id
     * @param int  $prize_id      奖品id
     * @param int  $prizes_type   奖品属性 1：邀请 2：被邀请
     * @return int bloor
     */
    static public function getReceiveAward($uid, $prize_id, $prizes_type) {
        return InvitationAwardRecordService::find()->select('*')
                        ->where(['award_uid' => $uid])
                        ->andWhere(['award_type' => $prizes_type])
                        ->andWhere(['status' => 2])
                        ->andWhere(['prizes_id' => $prize_id])
                        ->count();
    }

    /**
     * 判断该活动下面的次奖品是否已经领取过
     * @param int  $uid           用户id
     * @param int  $prize_id      奖品id
     * @param int  $prizes_type   奖品属性 1：邀请 2：被邀请
     * @return int bloor
     */
    static public function getReceiveAwardStatus($uid, $prize_id, $prizes_type) {
        return InvitationAwardRecordService::find()->select('*')
                        ->where(['award_uid' => $uid])
                        ->andWhere(['award_type' => $prizes_type])
                        ->andWhere(['status' => 1])
                        ->andWhere(['prizes_id' => $prize_id])
                        ->count();
    }

    /**
     * 判断被邀请人的被邀请奖品是否被申请、领取 过
     * @param int $uid 用户id
     * @param int $invitation_id 活动id
     * @param int $pirze_id  奖品id
     */
    static public function getInviteUserStatus($uid, $invitation_id) {

        $result = InvitationRecord::find()->select('invitation_id')
                        ->where(['invitee_uid' => $uid])
                        ->andWhere(['status' => 1])
                        ->andWhere(['invitation_id' => $invitation_id])->asArray()->one();

        if ($result['invitation_id']) {
            $inviterPrizeList = InvitationActivity::find()->select(['invited_id'])->where(['in', 'invitation_id', $result['invitation_id']])->andWhere(['status' => 1])->asArray()->one();
            return InvitationPrizesService::find()->select(['*'])->where(['in', 'prizes_id', $inviterPrizeList['invited_id']])->andWhere(['status' => 1])->andWhere(['prizes_type' => 1])->asArray()->one();
        } else {
            $phone = User::find()->select('umobile')->where(['id' => $uid])->andWhere(['register_status' => 0])->one();
            if ($phone->umobile) {
                $inviterPrize = InvitationRecordService::find()->select(['invitation_id'])->where(['in', 'invitee_phone', $phone['umobile']])->andWhere(['status' => 1])->asArray()->one();
                if ($inviterPrize->invitation_id) {
                    $inviterPrizes = InvitationActivityService::find()->select(['invited_id'])->where(['in', 'invitation_id', $inviterPrize['invitation_id']])->andWhere(['status' => 1])->asArray()->one();
                    return InvitationPrizesService::find()->select(['*'])->where(['in', 'prizes_id', $inviterPrizes['invited_id']])->andWhere(['status' => 1])->andWhere(['prizes_type' => 1])->asArray()->one();
                }
            }
        }
    }

    /**
     * 获取奖品状态
     */
    static public function getPrizeSataus($pirze_id, $invitation_id, $uid) {
        return InvitationAwardRecordService::find()->select('status')->where(['prizes_id' => $pirze_id])->andWhere(['award_uid' => $uid])->andWhere(['invitation_id' => $invitation_id])->andWhere(['award_type' => 1])->asArray()->one();
    }

}
