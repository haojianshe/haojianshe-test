<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\UserCorrect;

/**
 * 红笔老师相关逻辑
 */
class UserCorrectService extends UserCorrect
{       
    /**
     * 分页获取红笔老师列表
     */
    public static function getByPage($pay_type){
        if($pay_type==1){
            $pay_where=['>','correct_fee',0];
        }else if($pay_type==0){
            $pay_where=['=','correct_fee',0];
        }else{
            $pay_where=[];
        }
    	$query = parent::find()->where(['<>','status',1])->andWhere($pay_where);   
    	$countQuery = clone $query;
    	//分页对象计算分页数据
    	$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>25]);
    	//获取数据
    	$rows = (new \yii\db\Query())
    	->select(['b.*','a.uid','sname','avatar'])
    	->from(parent::tableName(). ' as b')
    	->innerJoin('ci_user_detail as a','a.uid=b.uid')
    	->where(['<>','status',1])
        ->andWhere($pay_where)
    	->offset($pages->offset)
    	->limit($pages->limit)
    	->orderBy('a.uid')
    	->all();
    	//处理头像
    	foreach ($rows as $k=>$v){
    		$v['avatars'] = UserService::getAvatar($v['avatar']);
    		$rows[$k] = $v;
    	}
    	return ['models' => $rows,'pages' => $pages];
    }
    
    /**
     * 快速批改批改老师选择
     */
    public static function getByPageOnFc($pagesize=500){
        $query = parent::find()->where(['=','status',0]);   
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$pagesize]);
        //获取数据
        $rows = (new \yii\db\Query())
        ->select(['b.*','a.uid','sname','avatar'])
        ->from(parent::tableName(). ' as b')
        ->innerJoin('ci_user_detail as a','a.uid=b.uid')
        ->where(['=','status',0])
        ->offset($pages->offset)
        ->limit($pages->limit)
        ->orderBy('a.uid DESC')
        ->all();
        //处理头像
        foreach ($rows as $k=>$v){
            $v['avatars'] = UserService::getAvatar($v['avatar']);
            $rows[$k] = $v;
        }
        return ['models' => $rows,'pages' => $pages];
    }
    
    /**
     * 删除单个用户的缓存
     * $uid
     */
    public static function removecache($uid){
    	$redis = Yii::$app->cache;
    	$key = "usercorrect_detail_" .$uid;
    	$redis->delete($key);
    	
    	//清列表缓存
    	$key = 'correct_teacher_listz';
    	$redis->delete($key);
    }
    /**
     * 得到所有付费老师列表
     * @return [type] [description]
     */
    public static function getPayTeachers(){
        $list = parent::find()->alias('a')->select("a.*,b.sname,b.avatar")->where(['>','correct_fee',0])->asArray()->leftJoin("ci_user_detail as b","a.uid=b.uid")->all();
        return $list;
    }
}
