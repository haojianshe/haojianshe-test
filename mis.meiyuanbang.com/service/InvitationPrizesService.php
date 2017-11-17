<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\InvitationPrizes;
use common\redis\Cache;

/**
 * 邀请活动列表
 * @author Administrator
 *
 */
class InvitationPrizesService extends InvitationPrizes
{   
    /**
     * 分页获取所有邀请活动列表
     */
    public static function getByPage(){
    	$query = parent::find()->where(['status' => 1]);    	
    	$countQuery = clone $query;
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>50]);
    	//获取数据    	
    	$rows = (new \yii\db\Query())
    	->select(['*'])
    	->from(parent::tableName())
//    	->innerJoin('myb_news as b','a.newsid=b.newsid')
//    	->innerJoin('myb_news_data as c','a.newsid=c.newsid')
     	->where(['status' => 1])   //已审核
//    	->orWhere(['a.status' => 2]) //待审核
    	->offset($pages->offset)
    	->limit($pages->limit)
    	->orderBy('prizes_id DESC')
    	->all();
    	return ['models' => $rows,'pages' => $pages];
    }
}