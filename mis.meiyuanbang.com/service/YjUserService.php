<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\yj\YjUser;

/**
 * 用户
 */
class YjUserService extends YjUser {
    
    const PAGESIZE = 50;

    /**
     * @获取所有学员数据
     */
    public static function getByPage(array $search = []) {
        $query = (new \yii\db\Query())
                ->select(['*'])
                ->from(parent::tableName());
        if ($search['is_sign']) {
            $query->andWhere(['is_sign' => $search['is_sign']]);
        }
        if ($search['start_time']) {
            $query->andWhere(['>=', 'create_time', $search['start_time']]);
        }
        if ($search['end_time']) {
            $query->andWhere(['<=', 'create_time', $search['end_time']]);
        }
        if ($search['user_name']) {
            $query->andWhere(['like', 'user_name', $search['user_name']]);
        }
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => self::PAGESIZE]);
        //获取数据
        $rows = $countQuery->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('create_time DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }
}
