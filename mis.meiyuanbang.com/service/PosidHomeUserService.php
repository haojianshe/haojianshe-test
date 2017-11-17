<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\PosidHomeUser;

/**
 * 考点相关逻辑
 */
class PosidHomeUserService extends PosidHomeUser {

    /**
     * 获取出版社广告
     * @param type $uid
     */
    static function getUserAdvert($uid) {
        $query = parent::find()->where([parent::tableName() . '.status' => 1])
                ->innerJoin('ci_user_detail as a', 'a.uid=' . parent::tableName() . '.uid')
                ->where(['a.role_type' => 2])
                ->andWhere(['a.uid' => $uid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据
        $models = (new \yii\db\Query())
                ->select(['b.*'])
                ->from('ci_user_detail as a')
                ->innerJoin('myb_posid_home_user as b', 'a.uid=b.uid')
                ->where(['a.role_type' => 2])
                ->andWhere(['a.uid' => $uid])
                ->andWhere(['b.status' => 1])
                ->andWhere(['b.advert_type' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('listorder DESC')
                ->all();
        return ['models' => $models, 'uid' => $uid, 'pages' => $pages];
    }

    /**
     * 画室广告位
     * @param type $uid
     * @return type
     */
    static function getByPage($uid) {
        $query = parent::find()->where([parent::tableName() . '.status' => 1])
                ->innerJoin('ci_user_detail as a', 'a.uid=' . parent::tableName() . '.uid')
                ->where(['<>', 'a.studio_type', 2])
                ->andWhere(['a.uid' => $uid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据
        $models = (new \yii\db\Query())
                ->select(['b.*'])
                ->from('ci_user_detail as a')
                ->innerJoin('myb_posid_home_user as b', 'a.uid=b.uid')
                ->where(['<>', 'a.studio_type', 2])
                ->andWhere(['a.uid' => $uid])
                ->andWhere(['b.status' => 1])
                ->andWhere(['b.advert_type' => 2])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('listorder DESC')
                ->all();
        return ['models' => $models, 'uid' => $uid, 'pages' => $pages];
    }

    /**
     * 删除缓存
     * @param type $uid
     */
    public static function delCache($uid) {
        $redis = \Yii::$app->cache;
        $redis_key = "studio_posid_list_" . $uid; //缓存key
        $redis->delete($redis_key);
    }

}
