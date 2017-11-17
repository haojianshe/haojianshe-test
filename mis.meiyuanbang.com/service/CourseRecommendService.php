<?php
namespace mis\service;
use Yii;
use common\models\myb\CourseRecommend;
use yii\data\Pagination;

class CourseRecommendService extends CourseRecommend{
	/**
     * 分页获取列表
     */
    public static function getByPage($recommendid) {
        $query = parent::find()->where(['recommendid'=>$recommendid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select("*")
                ->from(parent::tableName()." as b" )
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->leftJoin("myb_course as a","a.courseid=b.courseid")
                ->where(['recommendid'=>$recommendid])
                ->orderBy('sort_id asc')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }
    public static function getRecommendCourseIds($recommendid){
        $return_arr=[];
        $courseids= self::find()->select("courseid")->where(['recommendid'=>$recommendid])->asArray()->all();
        foreach ($courseids as $key => $value) {
             $return_arr[]=$value['courseid'];
        }
        return $return_arr;
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
