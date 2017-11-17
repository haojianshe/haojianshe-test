<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\RecommendBook;
use common\models\myb\RecommendBookAdv;
use mis\service\PublishingBookService;
use mis\service\UserService;

/**
 * 获取美院帮图书推荐
 */
class RecommendBookService extends RecommendBook {

    /**
     * 获取美院帮图书列表
     * @param type $uid myb_recommend_book
     */
    const Number = 50;

    static function getMybBookList($type) {
        $query = (new \yii\db\Query())
                ->select(['count(*)'])
                ->from('myb_recommend_book as a')
                ->innerJoin('myb_publishing_book as b', 'a.bookid=b.bookid')
                ->innerJoin('myb_news as c', 'b.newsid=c.newsid')
                ->innerJoin('myb_news_data as d', 'c.newsid=d.newsid')
                ->where(['a.status' => 1])
                ->andWhere(['b.status' => 1])
                ->andWhere(['c.status' => 0])
                ->andWhere(['a.f_catalog_id' => $type]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => self::Number]);
        $models = (new \yii\db\Query())
                ->select(['b.bookid', 'a.recid', 'c.thumb', 'c.title', 'b.publishing_name', 'a.ctime'])
                ->from('myb_recommend_book as a')
                ->innerJoin('myb_publishing_book as b', 'a.bookid=b.bookid')
                ->innerJoin('myb_news as c', 'b.newsid=c.newsid')
                ->innerJoin('myb_news_data as d', 'c.newsid=d.newsid')
                ->where(['a.status' => 1])
                ->andWhere(['b.status' => 1])
                ->andWhere(['c.status' => 0])
                ->andWhere(['a.f_catalog_id' => $type])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.ctime asc')
                ->all();
        foreach ($models as $key => $val) {
            $models[$key]['thumb'] = PublishingBookService::getPic($val['thumb']);
        }
        return ['models' => $models, 'type' => $type, 'pages' => $pages];
    }

    /**
     * 获取所有的出版社下面的图书来添加美院帮的推荐
     * @param type $type 出版社类型
     */
    static function getUserBookList($type, $uid) {
        $bookid = RecommendBook::find()->select('bookid')->where(['status' => 1, 'f_catalog_id' => $type])->Asarray()->all();
        if (!empty($bookid)) {
            foreach ($bookid as $key => $val) {
                $bookStr[] = $val['bookid'];
            }
        } else {
            $where = 0;
            $bookStr = [0];
        }
        $query = (new \yii\db\Query())
                ->select(['count(*)'])
                ->from('ci_user_detail as a')
                ->innerJoin('myb_publishing_book as b', 'a.uid=b.uid')
                ->innerJoin('myb_news as d', 'b.newsid=d.newsid')
                ->where(['a.role_type' => 2])
                ->andWhere(['a.uid' => $uid])
                ->andWhere(['b.status' => 1])
                ->andWhere(['d.status' => 0]);
        if ($where) {
            $query->andWhere(['not in', 'b.bookid', $bookStr]);
        }
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => self::Number]);
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
                ->orderBy('b.bookid DESC')
                ->all();
        return ['models' => $models, 'type' => $type, 'uid' => $uid, 'pages' => $pages];
    }

    /**
     * 获取所有的出版社
     */
    static function getPublishList() {
        $publishList = UserService::getPublish();
        $userList = [];
        if (!empty($publishList)) {
            foreach ($publishList['models'] as $key => $val) {
                $userList[$key] = [
                    'uid' => $val['uid'],
                    'sname' => $val['sname']
                ];
            }
        }
        return $userList;
    }

    /**
     * 获取能力模型图书推荐 =>美院帮推荐图书列表中的数据
     */
    static function getMybRecommendBoookList($type) {
        $sql = "select count(DISTINCT  a.bookid  ) as count from myb_recommend_book as a 
            INNER JOIN myb_recommend_book_adv as b on a.bookid=b.bookid 
            INNER JOIN myb_publishing_book as c on b.bookid=c.bookid 
            INNER JOIN  myb_news as d on c.newsid=d.newsid 
            where a.`status`=1 and b.adv_type=$type and b.uid=-1 and b.`status`=1 and d.`status`=0 and c.`status`=1 and d.`status`=0";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $count = $command_count->queryAll();
        $pages = new Pagination(['totalCount' => $count[0]['count'], 'pageSize' => self::Number]);
        //获取数据
        $page_ruls = " limit " . $pages->limit . " offset " . $pages->offset;
        //查找
        $query = "select DISTINCT b.advid,a.bookid,d.thumb,c.publishing_name,b.listorder,b.ctime,d.title from myb_recommend_book as a 
            INNER JOIN myb_recommend_book_adv as b on a.bookid=b.bookid 
            INNER JOIN myb_publishing_book as c on b.bookid=c.bookid 
            INNER JOIN  myb_news as d on c.newsid=d.newsid 
            where a.`status`=1 and b.adv_type=$type and b.uid=-1 and b.`status`=1 and d.`status`=0 and c.`status`=1 and d.`status`=0 order by b.ctime  $page_ruls"; //cc.subjecttype=0 and
        $command = $connection->createCommand($query);
        $models = $command->queryAll();
        foreach ($models as $key => $val) {
            $models[$key]['thumb'] = PublishingBookService::getPic($val['thumb']);
        }
        return ['models' => $models, 'type' => $type, 'pages' => $pages];
    }

    /**
     * 获取所有的出版社下面的图书来添加美院帮的推荐
     * @param type $type 出版社类型
     */
    static function getAddmybBookList($type, $uid = 0) {
        $connection = Yii::$app->db; //连接
        $bookid = RecommendBookAdv::find()->select('bookid')->where(['uid' => -1, 'status' => 1, 'adv_type' => $type])->Asarray()->all();
        if (!empty($bookid)) {
            foreach ($bookid as $key => $val) {
                $bookStr[] = $val['bookid'];
            }
            $idStr = implode(',', $bookStr);
            $where = "and b.bookid not in ($idStr)";
        } else {
            $where = '';
        }
        $sql = "SELECT count(DISTINCT b.bookid) as count FROM `ci_user_detail` as a 
                inner join myb_publishing_book as b on a.uid=b.uid 
                inner join myb_news as d on b.newsid=d.newsid 
                inner join myb_recommend_book as c on b.bookid=c.bookid 
                where a.uid=$uid and a.role_type=2 and b.status=1 and d.status=0 and c.status=1 $where ";
        $command_count = $connection->createCommand($sql);
        $count = $command_count->queryAll();
        $pages = new Pagination(['totalCount' => $count[0]['count'], 'pageSize' => self::Number]);
        //获取数据
        $page_ruls = " limit " . $pages->limit . " offset " . $pages->offset;
        $query = "SELECT DISTINCT b.bookid,d.title FROM `ci_user_detail` as a 
                inner join myb_publishing_book as b on a.uid=b.uid 
                inner join myb_news as d on b.newsid=d.newsid 
                inner join myb_recommend_book as c on b.bookid=c.bookid 
                where a.uid=$uid and a.role_type=2 and b.status=1 and d.status=0 and c.status=1 $where order by c.ctime desc $page_ruls"; //cc.subjecttype=0 and
        $command = $connection->createCommand($query);
        $models = $command->queryAll();
        return ['models' => $models, 'type' => $type, 'uid' => $uid, 'pages' => $pages];
    }

}
