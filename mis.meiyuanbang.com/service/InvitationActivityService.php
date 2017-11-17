<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\InvitationActivity;
use common\redis\Cache;

/**
 * 邀请活动列表
 * @author Administrator
 *
 */
class InvitationActivityService extends InvitationActivity {
    /**
     * 分页获取所有邀请活动列表
     */
    public static function getByPage() {
        $query = parent::find()->where(['status' => 1]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['*'])
                ->from(parent::tableName())
//    	->innerJoin('myb_news as b','a.newsid=b.newsid')
//    	->innerJoin('myb_news_data as c','a.newsid=c.newsid')
                ->where(['status' => 1])   //已审核
//    	->orWhere(['a.status' => 2]) //待审核
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('invitation_id DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 用户提交领奖信息时候，判断活动是否刚好过期
     * @param int $invitation_id 活动id
     */
    static public function getActivityTime($invitation_id) {
        return InvitationActivityService::find()->where(['invitation_id' => $invitation_id])->andWhere(['<=', 'btime', time()])->andWhere(['>=', 'award_time', time()])->count(); //->createCommand()->getRawSql();;
    }

    /**
     * 用户提交领奖信息时候，判断活动是否刚好过期
     * @param int $invitation_id 活动id
     */
    static public function getInvitationActivity() {
        $rediskey = "invitation_list";
        $redis = Yii::$app->cache;
        $mlist = $redis->get($rediskey);
        if (empty($mlist)) {
            //数据库获取
            $data = InvitationActivityService::find()->where(['<=', 'btime', time()])->andWhere(['>=', 'award_time', time()])->asArray()->one();
            $mlist = json_encode($data);
            $redis->set($rediskey, $mlist);
            $redis->expire($rediskey, 3600 * 24 * 3);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }

}
