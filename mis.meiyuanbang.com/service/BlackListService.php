<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use mis\models\Blacklist;
use mis\service\UserService;

/**
 * 黑名单相关逻辑
 */
class BlackListService extends Blacklist
{    
    /**
     * 分页获取所有黑名单用户信息
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照
     */
    public static function getByPage(){
    	$query = parent::find();
    	$countQuery = clone $query;
    	//分页对象计算分页数据
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>100]);
    	//获取数据    	
    	$rows = (new \yii\db\Query())
    	->select(['a.uid', 'desc','ctime','sname','avatar','umobile','oauth_type','create_time'])
    	->from(parent::tableName(). ' as a')
    	->innerJoin('ci_user_detail as b','a.uid=b.uid')
    	->innerJoin('ci_user as c','a.uid=c.id')    	
    	->offset($pages->offset)
    	->limit($pages->limit)
    	->orderBy('ctime DESC')
    	->all();
    	//处理头像
    	foreach ($rows as $k=>$v){
    		$v['avatars'] = UserService::getAvatar($v['avatar']);
    		$rows[$k] = $v;    		
    	}
    	return ['models' => $rows,'pages' => $pages];
    }
}
