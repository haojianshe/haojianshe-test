<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\DkCorrect;
/**
 * 大咖改画
 */
class DkCorrectService extends DkCorrect
{   
	public static function getSubmitUids($activityid){
         $query=new \yii\db\Query();
         
        //获取数据
         $models =$query
                 ->select('submituid') 
                 ->from(parent::tableName())
                 ->where(['activityid' => $activityid])
                 //->andWhere($where)
                 //->offset($pages->offset)
                 //->limit($pages->limit)
                 //->leftJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')  
                 //->orderBy('tid DESC')
                 ->all();
        //返回用户uid数组
       // var_dump($models );exit;
        $uids=[];
        foreach ($models as $key => $value) {
            $uids[]=$value['submituid'];
        }
        return $uids;
    }
     /**
     * 分页获取所有求批改列表
     */
    public static function getByPage($activityid){
        $query = parent::find()->where(['activityid' => $activityid]);        
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>50]);
        //获取数据      
        $rows = (new \yii\db\Query())
        ->select(['a.*','b.sname','b.avatar','c.*'])
        ->from(parent::tableName(). ' as a')
        ->where(['activityid'=>$activityid])
        ->leftJoin('ci_user_detail as b','a.submituid=b.uid')  
        ->leftJoin('ci_resource as c','a.source_pic_rid=c.rid')  
        //->where(['<>','a.status' , 2])
        ->offset($pages->offset)
        ->limit($pages->limit)
        ->orderBy('zan_num DESC')
        ->all();
        //var_dump($rows);exit;
        return ['models' => $rows,'pages' => $pages];
    }
    
}
