<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\DkPrizes;
use common\models\myb\DkPrizeUser;

/**
 * 活动相关逻辑
 */
class DkPrizesService extends DkPrizes {

    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage() {
        $query = parent::find()->where(['status' => 1]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName())
                ->where(['status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('prizesid DESC')
                ->all();

        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 分页获取所有获奖名单
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序
     */
    public static function getUserListData($activityid, $time = '', $end_time = '', $type = '') {
        $where = '';
        if (!empty($time)) {
            $start_time = strtotime($time);
            $endtime = strtotime($end_time);
            $where = ' dpu.ctime between ' . $start_time . ' and ' . $endtime;
        }
        if (!empty($type) && !empty($time)) {
            $where .= ' and dp.type=' . $type . ' and ';
        }
        if (!empty($type) && empty($time)) {
            $where .= ' dp.type=' . $type . ' and ';
        }
        if (empty($type) && !empty($time)) {
            $where .= ' and ';
        }

        if (empty($type) && empty($time)) {
            $where = '1=1 and ';
        }
        $sql = "SELECT count(*) as count  FROM `dk_activity` as da 
                INNER JOIN dk_prize_user as dpu on da.activityid=dpu.activityid 
                INNER JOIN ci_user_detail as cud on dpu.uid=cud.uid 
                INNER JOIN dk_prizes as dp on dp.prizesid=dpu.prizesid
                where $where da.status=1 and dp.status=1 and da.activityid=$activityid";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $count = $command_count->queryAll();
        $pages = new Pagination(['totalCount' => $count[0]['count'], 'pageSize' => 20]);
        //获取数据
        $page_ruls = " limit " . $pages->limit . " offset " . $pages->offset;
        //查找
        $query = "SELECT cud.sname,cud.uid,cud.avatar,`da`.`activityid`, `da`.`title`, `da`.`activity_img`, `dpu`.`ctime`, `dpu`.`name`, `dpu`.`mobile`, `dpu`.`address`, `dp`.`img`, `dp`.`title` dptitle FROM `dk_activity` `da` 
                 INNER JOIN `dk_prize_user` `dpu` ON da.activityid=dpu.activityid 
                 INNER JOIN ci_user_detail as cud on dpu.uid=cud.uid 
                 INNER JOIN `dk_prizes` `dp` ON dp.prizesid=dpu.prizesid 
                 where $where (`da`.`status`=1) AND (`dp`.`status`=1) and da.activityid=$activityid order by `dpu`.`ctime` desc $page_ruls"; //cc.subjecttype=0 and
        $command = $connection->createCommand($query);
        $models['data'] = $command->queryAll();
        foreach ($models['data'] as $key => $val) {
            $models['data'][$key]['avatar'] = json_decode($val['avatar'], true)['img']['n']['url'];
        }
        $models['activityid'] = $activityid;
        $models['type'] = $type;
        $models['start_time'] = $time;
        $models['end_time'] = $end_time;
        return ['models' => $models, 'pages' => $pages];
    }

}
