<?php

namespace mis\service;

use Yii;
use common\models\myb\CorrectReward;
use yii\data\Pagination;

class CorrectRewardService extends CorrectReward {

    /**
     * @desc 返回打赏信息 
     * @param type $search 搜索的数组对象
     * @return type
     */
    public static function getTeacherGiftList($search) {
        $query = (new \yii\db\Query())->from(parent::tableName() . ' as a')
                ->select("a.rewardid,count(*) as cu,sum(gift_price) as pic,teacheruid,GROUP_CONCAT(rewardid),b.sname")
                ->innerJoin("ci_user_detail as b", 'a.teacheruid=b.uid');
        if ($search['teachername'] != NULL) {
            $query->andWhere(['like', 'b.sname', $search['teachername']]);
        }
        if ($search['subjecttype'] != NULL) {
            $query->andWhere(['a.gift_id' => $search['subjecttype']]);
        }
        if ($search['stime'] != NULL) {
            $query->andWhere(['>', 'a.ctime', strtotime($search['stime'])]);
        }
        if ($search['etime'] != NULL) {
            $query->andWhere(['<', 'a.ctime', strtotime($search['etime'])]);
        }
        $query->andWhere(['a.status'=>1])->groupBy(' teacheruid ');
        $countQuery = clone $query; 
        //获取总礼物数 和 礼物总金额
        $arr = self::getSum($countQuery);
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据     
        $rows['models'] = $countQuery->offset($pages->offset)
                ->limit($pages->limit)
               # ->createCommand()->getRawSql();
                ->all();
        return ['models' => $rows, 'pages' => $pages, 'array' => $arr];
    }

    /**
     * @desc 返回总记录
     * @param type $countQuery
     * @return type
     */
    static public function getSum($countQuery) {
        $array = [
            'count' => '',
            'money' => ''
        ];
        $data = $countQuery->all();
        if ($data) {
            foreach ($data as $k => $v) {
                $array['count']+=$v['cu'];
                $array['money']+=$v['pic'];
            }
        }
        return $array;
    }

}
