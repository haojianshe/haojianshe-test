<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\MybActivityArticle;
use common\models\myb\News;

/**
 * 联考问答列表页面
 */
class MsService extends News {

    /**
     * 分页获取所有联考问答列表页面
     */
    public static function getByPage($lkid,$id) {
        $sql = "SELECT count(*) as count FROM `myb_news` as mn  INNER JOIN myb_activity_qa as maq on mn.newsid=maq.newsid where maq.activity_type=1";
     
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $count = $command_count->queryAll();
        $pages = new Pagination(['totalCount' => $count[0]['count'], 'pageSize' => 100]);
        //获取数据
        $page_ruls = " limit " . $pages->limit . " offset " . $pages->offset;
        //查找
        $query = "SELECT mn.newsid,mn.title,mn.username,mn.ctime FROM `myb_news` as mn  INNER JOIN myb_activity_qa as maq on mn.newsid=maq.newsid where maq.activity_type=1 $page_ruls"; //cc.subjecttype=0 and
        $command = $connection->createCommand($query);
        $models['data'] = $command->queryAll();
        $models['liid'] = $lkid;
        $models['ids'] = $id;
        return ['models' => $models, 'pages' => $pages];
    }

//    /**
//     * 重载model的save方法，保存后处理缓存
//     */
//    public function save($runValidation = true, $attributeNames = NULL){
//        $isnew = $this->isNewRecord;
//        $redis = Yii::$app->cache;
//         
//        $ret = parent::save($runValidation,$attributeNames);
//        //处理缓存
//        if($isnew==false){
//            $rediskey = "dkactivity".$this->activityid;
//            $redis->delete($rediskey);
//            $redis->delete("dkactivity");
//            //$redis->delete("dkactivity");
//        }else{
//            //$redis->rpush("dkactivity", $this->activityid, true);
//        }
//        return $ret;
//    }
}
