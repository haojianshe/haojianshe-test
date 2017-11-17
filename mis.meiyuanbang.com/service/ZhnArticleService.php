<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\ZhnArticle;
use common\redis\Cache;

/**
 * 正能文章
 * @author Administrator
 *
 */
class ZhnArticleService extends ZhnArticle
{    	
    /**
     * 分页获取所有正能文章
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage(){
    	$query = parent::find()->where(['status' => 0])->orWhere(['status' => 2]);    	
    	$countQuery = clone $query;
    	//分页对象计算分页数据
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>50]);
    	//获取数据    	
    	$rows = (new \yii\db\Query())
    	->select(['b.*','a.*','c.supportcount'])
    	->from(parent::tableName(). ' as a')
    	->innerJoin('myb_news as b','a.newsid=b.newsid')
    	->innerJoin('myb_news_data as c','a.newsid=c.newsid')
    	->where(['a.status' => 0])   //已审核
    	->orWhere(['a.status' => 2]) //待审核
    	->offset($pages->offset)
    	->limit($pages->limit)
    	->orderBy('a.newsid DESC')
    	->all();
    	return ['models' => $rows,'pages' => $pages];
    }
}