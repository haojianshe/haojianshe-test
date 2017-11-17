<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use mis\models\MisXingePush;
/*use common\redis\Cache;
*/
/**
 * 精讲相关逻辑
 * 0精讲 1课程 2活动
 */
class MisXingePushService extends MisXingePush
{    
	   /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage($where){
        $query = parent::find()->where($where);      
        $countQuery = clone $query;
        //分页对象计算分页数据 ->where(['<>','device_open_detail',$device_open_detail])
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>50]);
        //获取数据      
        $rows = (new \yii\db\Query())
        ->select('*')
        ->from(parent::tableName())
        ->where($where)
      //  ->where(['<>','status',1])
         //->where(['=','device_open_detail',$device_open_detail])
        ->offset($pages->offset)
        ->limit($pages->limit)
        ->orderBy('id DESC')
        ->all();
        return ['models' => $rows,'pages' => $pages];
    }
}