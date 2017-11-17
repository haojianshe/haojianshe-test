<?php

namespace mis\service;

use Yii;
use common\models\myb\StudioAddress;
use yii\data\Pagination;
use common\service\dict\CourseDictDataService;

class StudioAddressService extends StudioAddress {

    /**
     * 分页获取画室列表
     */
    public static function getByPage($uid) {

        $query = parent::find()->where(['<>', 'status', 2]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows_query = (new \yii\db\Query())
                ->select("q.*")
                ->innerJoin('myb_studio as b', 'q.uid=b.uid')
                ->from(parent::tableName() . ' as q')
                ->where(['q.status' => 1])
                ->andWhere(['<>', 'b.status', 2])
                ->andWhere(['q.uid' => $uid])
                ->andWhere(['q.status' => 1]);
        $rows = $rows_query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('q.ctime DESC')
                ->all();

        return ['models' => $rows, 'pages' => $pages, 'uid' => $uid];
    }

    /**
     * 保存前处理缓存
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        $redis_key = 'studio_menu_synopsis_' .$this->uid; //缓存key
        $redis->delete($redis_key);
        return $ret;
    }

}
