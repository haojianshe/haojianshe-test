<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\DkActivity;
/**
 * 大咖改画
 */
class DkActivityService extends DkActivity
{    
	
    /**
     * 分页获取所有大咖改画列表
     */
    public static function getByPage(){
    	$query = parent::find()->where(['<>','status' , 2]);    	
    	$countQuery = clone $query;
    	//分页对象计算分页数据
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>50]);
    	//获取数据    	
    	$rows = (new \yii\db\Query())
    	->select(['a.*','b.sname','b.avatar'])
    	->from(parent::tableName(). ' as a')
    	->innerJoin('ci_user_detail as b','a.teacheruid=b.uid')  
    	->where(['<>','a.status' , 2])
    	->offset($pages->offset)
    	->limit($pages->limit)
    	->orderBy('activityid DESC')
    	->all();
    	return ['models' => $rows,'pages' => $pages];
    }
    /**
     * 重载model的save方法，保存后处理缓存
     */
    public function save($runValidation = true, $attributeNames = NULL){
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
         
        $ret = parent::save($runValidation,$attributeNames);
        //处理缓存
        if($isnew==false){
            $rediskey = "dkactivity".$this->activityid;
            $redis->delete($rediskey);
            $redis->delete("dkactivity");
            //$redis->delete("dkactivity");
        }else{
            //$redis->rpush("dkactivity", $this->activityid, true);
        }
        return $ret;
    }

}
