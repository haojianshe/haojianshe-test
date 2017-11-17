<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\DkPrizeGamePrizes;

/**
 * 活动相关逻辑
 */
class DkPrizeGamePrizesService extends DkPrizeGamePrizes {

    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage() {
        $query = parent::find()->where(['status' => 1]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 2]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName())
                ->where(['status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('gameid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 获取活动关联奖品列表
     * @param type $gameid
     * @return array 列表数据
     */
    public static function getRewardList($gameid) {
        #select a.title,b.num,b.probability_start,b.probability_end,b.sort,c.title as name,c.img from dk_prize_game as a inner join dk_prize_game_prizes as b on a.gameid=b.gameid inner join dk_prizes as c on c.prizesid=b.prizesid
        $rows = (new \yii\db\Query())
                ->select('dpg.gameid,dpg.title,dpgp.prizesid,dpgp.num,dpgp.probability_start,dpgp.probability_end,dpgp.sort,dp.title as name,dp.img')
                ->from('dk_prize_game as dpg')
                ->innerJoin('dk_prize_game_prizes as dpgp', 'dpg.gameid=dpgp.gameid')
                ->innerJoin('dk_prizes as dp', 'dp.prizesid=dpgp.prizesid')
                ->where(['dpg.gameid' => $gameid, 'dp.status' => 1])
                ->all();
        return ['models' => $rows];
    }

    /**
     * 删除活动后,删除掉用户对应活动的中奖名单
     * @param  int $prizesid 活动id
     * @return bool/falce
     */
    public static function setPrizeGamePrizesStatus($gameid) {
        $sql = "UPDATE `dk_prize_game_prizes` SET `status` =  0 WHERE `gameid` = $gameid";
        $connection = Yii::$app->db; //连接
        return $connection->createCommand($sql)->query();
      
    }

}
