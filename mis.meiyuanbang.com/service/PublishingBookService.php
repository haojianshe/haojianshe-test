<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\PublishingBook;
use common\models\myb\RecommendBookAdv;
use common\models\myb\Resource;

/**
 * 考点相关逻辑
 */
class PublishingBookService extends PublishingBook {

    /**
     * 获取图书推荐管理列表
     * @param type $uid
     */
    static function getBookList($uid, $type) {
        $query = (new \yii\db\Query())
                ->select(['a.uid', 'c.bookid', 'd.thumb', 'd.title', 'c.publishing_name', 'c.price', 'b.ctime', 'b.listorder', 'b.advid'])
                ->from('ci_user_detail as a')
                ->innerJoin('myb_recommend_book_adv as b', 'a.uid=b.uid')
                ->innerJoin('myb_publishing_book as c', 'b.bookid=c.bookid')
                ->innerJoin('myb_news as d', 'c.newsid=d.newsid')
                ->where(['a.role_type' => 2])
                ->andWhere(['a.uid' => $uid])
                ->andWhere(['b.adv_type' => $type])
                ->andWhere(['b.status' => 1])
                ->andWhere(['c.status' => 1]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        $models = (new \yii\db\Query())
                ->select(['a.uid', 'c.bookid', 'd.thumb', 'd.title', 'c.publishing_name', 'c.price', 'b.ctime', 'b.listorder', 'b.advid'])
                ->from('ci_user_detail as a')
                ->innerJoin('myb_recommend_book_adv as b', 'a.uid=b.uid')
                ->innerJoin('myb_publishing_book as c', 'b.bookid=c.bookid')
                ->innerJoin('myb_news as d', 'c.newsid=d.newsid')
                ->where(['a.role_type' => 2])
                ->andWhere(['a.uid' => $uid])
                ->andWhere(['b.adv_type' => $type])
                ->andWhere(['b.status' => 1])
                ->andWhere(['c.status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('b.ctime asc')
                ->all();
        foreach ($models as $key => $val) {
            $models[$key]['thumb'] = static::getPic($val['thumb']);
        }
        return ['models' => $models, 'uid' => $uid, 'type' => $type, 'pages' => $pages];
    }

    /**
     * 获取图书推荐管理列表
     * @param type $uid
     */
    static function getUserListBook($uid) {
        $query =  (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName() . ' as c')
                ->innerJoin('ci_user_detail as a', 'a.uid=c.uid')
                ->innerJoin('myb_news as d', 'c.newsid=d.newsid')
                ->innerJoin('myb_news_data as e', 'd.newsid=e.newsid')
                ->where(['a.role_type' => 2])
                ->andWhere(['a.uid' => $uid])
                ->andWhere(['c.status' => 1])
                ->andWhere(['d.status' => 0]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $models = (new \yii\db\Query())
                ->select(['a.uid', 'c.bookid', 'd.thumb', 'd.title', 'c.publishing_name', 'c.price', 'e.copyfrom', 'd.username', 'd.ctime'])
                ->from(parent::tableName() . ' as c')
                ->innerJoin('ci_user_detail as a', 'a.uid=c.uid')
                ->innerJoin('myb_news as d', 'c.newsid=d.newsid')
                ->innerJoin('myb_news_data as e', 'd.newsid=e.newsid')
                ->where(['a.role_type' => 2])
                ->andWhere(['a.uid' => $uid])
                ->andWhere(['c.status' => 1])
                ->andWhere(['d.status' => 0])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('c.ctime DESC')
                ->all();
        foreach ($models as $key => $val) {
            $models[$key]['thumb'] = static::getPic($val['thumb']);
        }
        return ['models' => $models, 'uid' => $uid, 'pages' => $pages];
    }

    /**
     * 获取图片
     * @param type $rid
     * @return type
     */
    static public function getPic($rid) {
        $img = Resource::findOne(['rid' => $rid]);
        $imgArray = json_decode($img['img'], 1);
        return $imgArray['n']['url'];
    }

    /**
     * 获取出版社下面的图书来添加推荐
     * @param type $uid 出版社id
     * @param type $type 出版社类型
     */
    static function getUserBookList($uid, $type) {
        $bookid = RecommendBookAdv::find()->select('bookid')->where(['uid' => $uid, 'status' => 1, 'adv_type' => $type])->Asarray()->all();
        if (!empty($bookid)) {
            foreach ($bookid as $key => $val) {
                $bookStr[] = $val['bookid'];
            }
            $where = 1;
        } else {
            $where = 0;
            $bookStr = [0];
        }
        $query = (new \yii\db\Query())
                ->select(['count(*) as count'])
                ->from('ci_user_detail as a')
                ->innerJoin('myb_publishing_book as b', 'a.uid=b.uid ')
                ->innerJoin('myb_news as d', 'b.newsid=d.newsid')
                ->where(['a.role_type' => 2])
                ->andWhere(['a.uid' => $uid])
                ->andWhere(['b.status' => 1])
                ->andWhere(['d.status' => 0]);
        if ($where) { # ['in', 'id', [1, 2, 3]]
            $query->andWhere(['not in', 'b.bookid', $bookStr]);
        }
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        $models = (new \yii\db\Query())
                ->select(['b.bookid', 'd.title'])
                ->from('ci_user_detail as a')
                ->innerJoin('myb_publishing_book as b', 'a.uid=b.uid')
                ->innerJoin('myb_news as d', 'b.newsid=d.newsid')
                ->where(['a.role_type' => 2])
                ->andWhere(['a.uid' => $uid])
                ->andWhere(['b.status' => 1])
                ->andWhere(['d.status' => 0])
                ->andWhere(['not in', 'b.bookid', $bookStr])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('b.ctime DESC')
                ->all();
        return ['models' => $models, 'uid' => $uid, 'type' => $type, 'pages' => $pages];
    }

    /**
     * 去除图书编辑列表缓存
     */
    static public function setCaheBook($bookid = '', $uid = '', $type = '') {
        //
        //出版社图书信息 编辑图书时，去掉缓存
        //"publishing_book_".$bookid
        //
        //
        //美院帮图书推荐列表 美院帮推荐时去掉缓存
        //"myb_recomend_books_".$f_catalog_id;
        // 
        //出版社图书列表  出版社列表
        //"publishing_books_".$uid
        //
        //$rediskey="publishing_book_count_".$uid;
        //去除图书缓存
        $redis = Yii::$app->cache;
        if ($type == 1) {
            $rediskeyBook = "publishing_book_" . $bookid;
            $redis->delete($rediskeyBook);
            $rediskeyUid = "publishing_books_" . $uid;
            $redis->delete($rediskeyUid);
        } else if ($type == 2) {
            $rediskey = "publishing_books_" . $uid;
            $redis->delete($rediskey);
        } else if ($type == 3) {
            $rediskey = "capacitymodelmaterial_" . $uid;
            $redis->delete($rediskey);
        } else if ($type == 4) {
            $rediskey = "publishing_book_count_" . $uid;
            $redis->delete($rediskey);
        } else if ($type == 5) {
            $rediskey = "myb_recomend_books_" . $uid;
            $redis->delete($rediskey);
        }
    }

}
