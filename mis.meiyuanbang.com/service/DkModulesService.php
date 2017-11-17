<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\DkModules;
/**
 * 活动相关逻辑
 */
class DkModulesService extends DkModules
{    
    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage($activityid){
    	$query = parent::find()->where(['status' => 1,'activityid' => $activityid]);    	
    	$countQuery = clone $query;
    	//分页对象计算分页数据
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>50]);
    	//获取数据    	
    	$rows = (new \yii\db\Query())
    	->select(['*'])
    	->from(parent::tableName())
    	->where(['status' => 1,'activityid' => $activityid])
    	->offset($pages->offset)
    	->limit($pages->limit)
    	->orderBy('modulesid asc')
    	->all();
    	return ['models' => $rows,'pages' => $pages];
    }
    
}
