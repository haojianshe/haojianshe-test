<?php
namespace mis\service;

use Yii;
use yii\redis\Cache;
use yii\data\Pagination;
use mis\models\User;
use mis\models\MisUserVest;

/**
 * 用户角色相关逻辑
 */
class MisUserVestService extends MisUserVest
{    
    /**
     * 根据用户昵称获取用户信息
     * @param $keyword 用户昵称，根据like查询
     * @param $limit 返回的数据条数
     * @return 
     */
    public static function getVestUser($mis_userid){
    	//获取数据    	
    	$rows = (new \yii\db\Query())
    	->select(['uids'])
    	->from(parent::tableName())
    	->where("mis_userid=$mis_userid")
    	->one();
    	return $rows['uids'];
    }    
}
