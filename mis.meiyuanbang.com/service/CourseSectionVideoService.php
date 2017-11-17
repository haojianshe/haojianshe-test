<?php
namespace mis\service;
use Yii;
use common\models\myb\CourseSectionVideo;
use yii\data\Pagination;

class CourseSectionVideoService extends CourseSectionVideo{
	/**
     * 分页获取列表
     */
    public static function getByPage($sectionid) {
        $query = parent::find()->where(['status' => 1])->andWhere(['sectionid'=>$sectionid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select("*")
                ->from(parent::tableName() )
                ->where(['status' => 1])
                ->andWhere(['sectionid'=>$sectionid])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('coursevideoid DESC')
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
        $redis->delete("course_section_video_".$this->coursevideoid);
        $redis->delete("section_video_".$this->sectionid );
        //$redis->delete($this->lecture_list_rediskey);
        return $ret;
    }
} 
