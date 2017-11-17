<?php
namespace mis\service;
use Yii;
use common\models\myb\CourseSection;
use yii\data\Pagination;

class CourseSectionService extends CourseSection{
	/**
     * 分页获取列表
     */
    public static function getByPage($courseid) {
        $query = parent::find()->where(['status' => 1])->andWhere(['courseid' => $courseid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select("*")
                ->from(parent::tableName() )
                ->where(['status' => 1])
                ->andWhere(['courseid' => $courseid])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('sectionid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

     /**
     * 保存前处理缓存
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        $redis->delete("course_detail_".$this->courseid);
        $redis->delete("course_section_".$this->courseid);
        $redis->delete("course_section_detail_".$this->sectionid);
        
        return $ret;
    }
} 
