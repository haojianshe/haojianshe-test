<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\Startpage;
use common\redis\Cache;

/**
 * 启动页
 * @author Administrator
 *
 */
class StartpageService extends Startpage
{   
	/**
     * 分页获取所有启动页数据
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage(){
    	$countQuery = parent::find()->where(['status' => 0]);    	
    	//分页对象计算分页数据
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>50]);
    	//获取数据    	
    	$rows = (new \yii\db\Query())
    	->select('*')
    	->from(parent::tableName())
    	->where(['status' => 0])   //已审核
    	->offset($pages->offset)
    	->limit($pages->limit)
    	->orderBy('pageid DESC')
    	->all();
    	return ['models' => $rows,'pages' => $pages];
    }
    
    public function save($runValidation = true, $attributeNames = NULL){
    	$isnew = $this->isNewRecord;
    	$redis = Yii::$app->cache;
    	$ret = parent::save($runValidation,$attributeNames);
    	
    	if(!$isnew){
    		//处理缓存
    		$rediskey = "startpage_detail_" .$this->pageid;
    		//清除单个帖子的缓存
    		$redis->delete($rediskey);
    	}	
    	return $ret;
    }    
}