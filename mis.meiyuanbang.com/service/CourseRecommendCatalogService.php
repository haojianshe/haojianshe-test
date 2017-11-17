<?php
namespace mis\service;
use Yii;
use common\models\myb\CourseRecommendCatalog;
use yii\data\Pagination;
use common\service\dict\CourseDictDataService;

class CourseRecommendCatalogService extends CourseRecommendCatalog{
	/**
     * 分页获取列表
     */
    public static function getByPage() {
        $query = parent::find();
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select("*")
                ->from(parent::tableName() )
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('sort_id asc')
                ->all();
        foreach ($rows as $key => $value) {
            $rows[$key]['f_catalog']=CourseDictDataService::getCourseMainTypeNameById($rows[$key]['f_catalog_id']);
            $rows[$key]['s_catalog']=CourseDictDataService::getCourseSubTypeById($rows[$key]['f_catalog_id'],$rows[$key]['s_catalog_id']);
        }
        return ['models' => $rows, 'pages' => $pages];
    }

     /**
     * 保存前处理缓存
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        $redis->delete("course_catalog");
        //$redis->delete($this->lecture_list_rediskey);
        return $ret;
    }
} 
