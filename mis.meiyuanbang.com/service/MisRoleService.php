<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use mis\models\MisRole;

/**
 * 用户角色相关逻辑
 */
class MisRoleService extends MisRole
{    
    /**
     * 获取所有数据，按照角色名字排序
     * 按名字排序查看起来比较方便
     */
    public static function getAllOrderByName(){
    	$models = parent::find()->orderBy('rolename,roleid')
    	         ->all();
    	return $models;
    }
    
    /**
     * 根据角色名获取实例
     */
    public static function findByRolename($rolename){
    	return static::findOne(['rolename' => $rolename]);
    }
}
