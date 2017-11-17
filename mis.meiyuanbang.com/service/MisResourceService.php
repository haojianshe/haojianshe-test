<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use mis\models\MisResource;

/**
 * 资源所有相关逻辑
 */
class MisResourceService extends MisResource
{    
    /**
     * 获取所有数据，按照资源名称排序
     * 按名字排序查看起来比较方便
     */
    public static function getAllOrderByName(){
    	$models = parent::find()->orderBy('resourcename,resourceid')
    	         ->all();
    	return $models;
    }
    
    /**
     * 根据资源名获取实例
     */
    public static function findByRolename($resourcename){
    	return static::findOne(['resourcename' => $resourcename]);
    }
}
