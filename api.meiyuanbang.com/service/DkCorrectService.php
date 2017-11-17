<?php
namespace api\service;

use Yii;
use common\models\myb\DkCorrect;
use api\service\ResourceService;
use api\service\UserDetailService;
use api\service\CorrectService;

/**
 * 大咖改画批改
 */
class DkCorrectService extends DkCorrect
{   
    /**
     * 根据活动获取参加批改id
     * @param  [type] $activityid [description]
     * @return [type]             [description]
     */
	public static function getDkCorrectids($activityid,$field='dkcorrectid'){
         $query=new \yii\db\Query();
         
        //获取数据
         $models =$query
                 ->select("$field") 
                 ->from(parent::tableName())
                 ->where(['activityid' => $activityid])
                 //->andWhere($where)
                 //->offset($pages->offset)
                 //->limit($pages->limit)
                 //->leftJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')  
                 ->orderBy('zan_num desc,ctime asc')
                 ->all();
        //返回用户uid数组
       // var_dump($models );exit;
        $dkcorrectids=[];
        foreach ($models as $key => $value) {
            if($value["$field"]){
                $dkcorrectids[]=$value["$field"];
            }            
        }
        return $dkcorrectids;
    }
    /**
     * 获取用户提交详情
     * @param  [type] $dkcorrectid [description]
     * @return [type]              [description]
     */
    public static function getDkCorrectDetail($dkcorrectid){
        $detail=self::findOne(['dkcorrectid'=>$dkcorrectid]);
        if($detail){
            $detail_arr=$detail->attributes;
        }
        $detail_arr['source_pic']=ResourceService::getResourceDetail( $detail_arr['source_pic_rid']);
        $detail_arr['submit']=UserDetailService::getByUid( $detail_arr['submituid']);
        if($detail_arr['correctid']){
             $detail_arr['correct']=CorrectService::getFullCorrectInfo($detail_arr['correctid'],1);
        }else{
            $detail_arr['correct']=[];
        }
       
        return $detail_arr;
    }

    /**
     * 取得用户活动提交列表
     * @param  [type] $activityid [description]
     * @return [type]             [description]
     */
    public static function getDkCorrectList($activityid,$lastid,$rank,$rn){
        $re_list=[];
        $dkcorrectids=self::getDkCorrectidsRedis($activityid,$lastid,$rn);
        foreach ($dkcorrectids as $key => $value) {
                $res=self::getDkCorrectDetail($value);
                $res['rank']=++$rank;
                $re_list[]=$res;
                unset($res);
        }
        return $re_list;
    }
    /**
     * 获取列表缓存
     * @param  [type]  $activityid [description]
     * @param  [type]  $lastid     [description]
     * @param  integer $rn         [description]
     * @return [type]              [description]
     */
    public static function getDkCorrectidsRedis($activityid,$lastid=NULL,$rn=20){
        $redis = Yii::$app->cache;
        $rediskey="dkcorrectlist".$activityid;
        //$redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::getDkCorrectids($activityid);
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value.',';
                $ret = $redis->rpush($rediskey, $value,true);
            }
            $redis->expire($rediskey,60);
            $ids=substr($ids, 0,strlen($ids)-1);
            $list_arr=explode(',', $ids);
        }
        //分页数据获取
        if(empty($lastid)){
            $idx=0;
            $ids_data=$redis->lrange($rediskey,0, $rn-1);
        }else{
            $idx = array_search($lastid, $list_arr);
            $ids_data=$redis->lrange($rediskey,$idx+1, $idx+$rn);
        }
        return $ids_data;
    }
    /**
     * 获得个人用户排名详情
     * @param  [type] $activityid [description]
     * @param  [type] $uid        [description]
     * @return [type]             [description]
     */
    public static function getSubmitRank($activityid,$uid){
        $rank_obj=self::findOne(['activityid'=>$activityid,'submituid'=>$uid]);
        if($rank_obj){
            $rank=$rank_obj->attributes;
        }else{
            return [];
        }
        $ret_arr=self::getDkCorrectDetail($rank['dkcorrectid']); 
        $ret_arr['key']=self::find()->where(['activityid'=>$activityid])->andWhere(['>','zan_num',$rank['zan_num']])->orderBy("ctime desc")->count()+1;
        //var_dump(expression)
        return $ret_arr;
    }
}
