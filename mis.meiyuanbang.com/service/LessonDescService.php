<?php

namespace mis\service;
use Yii;
use yii\data\Pagination;
use common\models\myb\LessonDesc;
use mis\service\SoundResourceService;
/**
 * 考点描述
 */
class LessonDescService extends LessonDesc {
    //考点  描述列表
    private  $lessondesc_list_rediskey = 'lesson_desc_list_';
    //考点  描述详情
    private  $lessondesc_detail_rediskey = 'lesson_desc_detail_';
    public static function getLessonDescCount($lessonid){
        $count = LessonDesc::find()->where(['lessonid'=>$lessonid])->count();
        return $count;
    }
   /**
    * 分页获取
    * @param  [type] $lessonid [description]
    * @return [type]           [description]
    */
    public static function getByPage($lessonid) {
        $soundtable=SoundResourceService::tableName();
        $query = LessonDesc::find()->alias("a")
                ->select("a.*,$soundtable.*,a.imgurl")
                ->leftJoin("$soundtable","a.soundid=$soundtable.soundid")
                ->where(['lessonid'=>$lessonid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows_query = clone $query;
        $rows = $rows_query                
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('lessondescid DESC')
                ->asArray()
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 重载model的save方法，保存后处理缓存
     * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
     */
    public function save($runValidation = true, $attributeNames = NULL){
        $redis = Yii::$app->cache;          
        $ret = parent::save($runValidation,$attributeNames);
        //处理缓存
        $rediskey = $this->lessondesc_list_rediskey.$this->lessonid;
        $redis->delete($rediskey);
        $rediskey = $this->lessondesc_detail_rediskey.$this->lessondescid;
        $redis->delete($rediskey);
        return $ret;
    }
    
    /**
     * 重载delete，更新缓存
     * (non-PHPdoc)
     * @see \yii\db\ActiveRecord::delete()
     */
    public function delete(){
        $redis = Yii::$app->cache;          
        $ret = parent::delete();

        $rediskey = $this->lessondesc_list_rediskey.$this->lessonid;
        $redis->delete($rediskey);
        $rediskey = $this->lessondesc_detail_rediskey.$this->lessondescid;
        $redis->delete($rediskey);
        return $ret;
    }
}
