<?php
namespace mis\service;

use Yii;
use common\models\myb\FastCorrect;
use yii\data\Pagination;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use common\redis\Cache;

/**
 * mis用户相关的业务逻辑层
 * 本方法实现了IdentityInterface，可以做为yii\web\user类的登录验证类使用
 * @author Administrator
 *
 */
class FastCorrectService extends FastCorrect 
{

    /**
     * 分页获取所有后台活动信息
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照
     */
    public static function getFastCorrectByPage($where=''){
        $query = parent::find();

        $countQuery = $query//->select('COUNT(*)') 
                 ->from(parent::tableName())
                 ->where(['is_del' => 0])
                ->count();
        //分页对象计算分页数据           
        $pages = new Pagination(['totalCount' => $countQuery]);        
        $query=new \yii\db\Query();
        //获取数据
        $models =$query
                 ->select('*') 
                 ->from(parent::tableName())
                 ->where(['is_del' => 0])
                 ->offset($pages->offset)
                 ->limit($pages->limit)
                 ->orderBy('fastcorrectid DESC')
                 ->all();

        return ['models' => $models,'pages' => $pages,'pageSize'=>1];
    }
    /**
     * 保存时操作缓存
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL){       
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation,$attributeNames);   
        //清除缓存     
        $redis->delete("nowfastcorrect"); 
        $redis->delete("waitfastcorrect");
        return $ret;
    }

}
