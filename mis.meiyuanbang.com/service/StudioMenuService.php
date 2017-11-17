<?php

namespace mis\service;

use Yii;
use common\models\myb\StudioMenu;
use yii\data\Pagination;
use common\service\dict\CourseDictDataService;

class StudioMenuService extends StudioMenu {

    /**
     * 分页获取画室列表
     */
    public static function getByPage($uid, $sname) {
        $query = (new \yii\db\Query())
                ->select("a.menuid,a.uid,a.menu_type,a.ctime,a.listorder,a.studiomenuid")
                ->innerJoin('myb_studio as b', 'b.uid=a.uid')
                ->from(parent::tableName() . ' as a')
                ->where(['<>', 'b.status', 2])
                ->andWhere(['a.menu_type' => 1])
                ->andWhere(['a.uid' => $uid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据    	
        $rows_query = (new \yii\db\Query())
                ->select("a.menuid,a.uid,a.menu_type,a.ctime,a.listorder,a.studiomenuid")
                ->innerJoin('myb_studio as b', 'b.uid=a.uid')
                ->from(parent::tableName() . ' as a')
                ->where(['<>', 'b.status', 2])
                ->andWhere(['a.menu_type' => 1])
                ->andWhere(['a.uid' => $uid]);
        $rows = $rows_query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.listorder DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages, 'uid' => $uid, 'sname' => $sname];
    }

    /**
     * 删除缓存
     */
    public static function delCache($classtypeid, $uid) {
        $redis = \Yii::$app->cache;
        $redis_key = 'studio_class_type_' . $classtypeid . '_' . $uid; //缓存key
        $redis->delete($redis_key);
        $redis->delete('studio_menu_list_' . $uid);
    }

}
