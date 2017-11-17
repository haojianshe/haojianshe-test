<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\MybLkMaterialRelation;

/**
 * 联考问答列表页面
 */
class MybLkMaterialRelationService extends MybLkMaterialRelation {

    /**
     * 分页获取所有联考问答列表页面
     */
    public static function getByPage($lkid, $id) {
        $sql = "SELECT count(*) as count FROM `myb_news` as mn  INNER JOIN myb_activity_qa as maq on mn.newsid=maq.newsid where maq.activity_type=1 and mn.status=0";

        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $count = $command_count->queryAll();
        $pages = new Pagination(['totalCount' => $count[0]['count'], 'pageSize' => 100]);
        //获取数据
        $page_ruls = " limit " . $pages->limit . " offset " . $pages->offset;
        //查找
        $query = "SELECT mn.newsid,mn.title,mn.username,mn.ctime FROM `myb_news` as mn  INNER JOIN myb_activity_qa as maq on mn.newsid=maq.newsid where maq.activity_type=1 $page_ruls and mn.status=0"; //cc.subjecttype=0 and
        $command = $connection->createCommand($query);
        $models['data'] = $command->queryAll();
        $models['liid'] = $lkid;
        $models['ids'] = $id;
        return ['models' => $models, 'pages' => $pages];
    }

    /**
     * 判断
     */
    public static function getZtypeStatus($lkid, $zp_type, $newsid) {

        switch ($zp_type) {
            case 2:
                $type = 3;
                break;
            case 3:
                $type = 2;
                break;
        }
        return self::find()->select('reid')->where(['lkid' => $lkid, 'newsid' => $newsid, 'zp_type' => $type, 'status' => 1])->Asarray()->one();
    }

}
