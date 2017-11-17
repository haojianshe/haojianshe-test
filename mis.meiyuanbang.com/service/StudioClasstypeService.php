<?php

namespace mis\service;

use Yii;
use common\models\myb\StudioClasstype;
use yii\data\Pagination;
use common\service\dict\CourseDictDataService;
use common\models\myb\StudioEnroll;
use common\models\myb\Orderinfo;

class StudioClasstypeService extends StudioClasstype {

    /**
     * 取出画室下面不同班型的列表
     * @param int $studiomenuid
     * @return intger
     */
    public static function getByPage($studiomenuid, $uid) {

        $query = (new \yii\db\Query())
                ->select("q.*")
                ->innerJoin('myb_studio as b', 'q.uid=b.uid')
                ->from(parent::tableName() . ' as q')
                ->where(['<>', 'q.status', 2])
                ->andWhere(['q.uid' => $uid])
                ->andWhere(['<>', 'b.status', 2]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 30]);
        //获取数据    	
        $rows_query = (new \yii\db\Query())
                ->select("q.*")
                ->innerJoin('myb_studio as b', 'q.uid=b.uid')
                ->from(parent::tableName() . ' as q')
                ->where(['<>', 'q.status', 2])
                ->andWhere(['q.uid' => $uid])
                ->andWhere(['<>', 'b.status', 2]);
        $rows = $rows_query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('q.ctime DESC')
                ->all();

        return ['models' => $rows, 'pages' => $pages, 'uid' => $uid];
    }

    public static function getPayList($uid, $classtypeid) {
        //获取总数 分页
        $sqls = "SELECT DISTINCT count(`mo`.`mark`)  FROM `myb_studio_signuser` `mss` 
                    INNER JOIN `myb_orderinfo` `mo` ON mss.enrollid=mo.mark
                    WHERE (`subjecttype`=3)  AND mss.classtypeid=$classtypeid AND mss.uid=mo.uid and mo.ctime=mss.ctime  ORDER BY `mss`.`ctime` DESC";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sqls);
        $count_1 = $command_count->queryAll();
        $pages = new Pagination(['totalCount' => $count_1[0]['count'], 'pageSize' => 20]);
        $sql = "SELECT DISTINCT `mss`.`ctime` AS `cctime`,`mo`.`mark`, `mss`.`name`, `mss`.`mobile`, `mss`.`QQ`,  `mss`.`qq`, `mss`.`school`, `mo`.`ordertitle`, `mo`.`fee`, `mo`.`paytype` AS `type`, `mo`.`paytime`, `mo`.`status` AS `paytype` 
                    FROM `myb_studio_signuser` `mss` 
                    INNER JOIN `myb_orderinfo` `mo` ON mss.enrollid=mo.mark
                    WHERE (`subjecttype`=3)  AND mss.classtypeid=$classtypeid AND mss.uid=mo.uid and mo.ctime=mss.ctime  ORDER BY `mss`.`ctime` DESC ";
        $command = $connection->createCommand($sql);
        $rows = $command->queryAll();

        # return ['models' => $models,'pages' => $pages];
        return ['models' => $rows, 'pages' => $pages, 'uid' => $uid];
    }

    public static function getPayListUser($uid, $classtypeid) {
        //获取总数 分页
        $sqls = "SELECT  signuserid,uid,mobile,enrollid,QQ,school,name,enrollid,ctime from myb_studio_signuser where  classtypeid=$classtypeid";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sqls);
        $count_1 = $command_count->queryAll();
        $pages = new Pagination(['totalCount' => $count_1[0]['count'], 'pageSize' => 20]);

        $sql = "SELECT  signuserid,uid,mobile,enrollid,QQ,school,name,enrollid,ctime from myb_studio_signuser where  classtypeid=$classtypeid";
        $command = $connection->createCommand($sql);
        $rows = $command->queryAll();

        # return ['models' => $models,'pages' => $pages];
        return ['models' => $rows, 'pages' => $pages, 'uid' => $uid];
    }

    //查看报名方式
    public static function getEnrollData($id) {
        return StudioEnroll::find()->select(['enroll_title', 'discount_price'])->where(['enrollid' => $id])->asArray()->one();
    }

    //查看是否支付
    public static function getOrderPay($uid, $id,$time) {
        return Orderinfo::find()->select(['status', 'paytype','paytime'])->where(['uid' => $uid])->andWhere(['subjecttype'=>3])->andWhere(['mark'=>$id])->andWhere(['ctime'=>$time])->asArray()->one();
    }

    /**
     * 删除用户缓存
     */
    public static function delCache($uid) {
        $redis = \Yii::$app->cache;
        $redis_key = 'studio_menu_class_' . $uid;
        $redis->delete($redis_key);
    }

}
