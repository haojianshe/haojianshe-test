<?php
namespace api\service;
use common\models\myb\GroupBuy;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use common\redis\Cache;
use common\service\DictdataService;

use Yii;

/**
 * 团购相关信息
 */
class GroupBuyService extends GroupBuy { 
    /**
        获取正在进行的团购信息
    **/
    public static function getGroupBuyInfo($courseid){
        $groupbuyinfo= self::find()->where(['courseid'=>$courseid])->andWhere(['<',"start_time",time()])->andWhere(['>',"end_time",time()])->andWhere(['status'=>1])->one();
        if($groupbuyinfo){
            //增加参与人数
            self::updateJoinPersionCount($groupbuyinfo);
            $grouparr=$groupbuyinfo->attributes;
            $grouparr['current_time']=time();
            $grouparr['productid'] = DictdataService::getIosProductidByPrice($groupbuyinfo['course_group_fee_ios']);
            return  $grouparr; 
        }else{
            return [];
        }
    }
    /**
        得到正在进行的团购
    **/
    public static function getInfoByGroupBuyId($groupbuyid){
        $groupbuyinfo = self::find()->where(['groupbuyid'=>$groupbuyid])->andWhere(['<',"start_time",time()])->andWhere(['>',"end_time",time()])->andWhere(['status'=>1])->one();
        return $groupbuyinfo;
    }
    /**
        程序算法增加团购参与人数
    **/
    public static function updateJoinPersionCount($groupbuyinfo){
        //团购总时长
        $diff_time=$groupbuyinfo->end_time-$groupbuyinfo->start_time;
        //当前过了多久
        $now_diff_time=time()-$groupbuyinfo->start_time;
        //团购已经开始
        if($now_diff_time>0){
            //计算当前应该显示的数值
            $now_show_count=intval($groupbuyinfo->person_count_init+($now_diff_time/$diff_time)*($groupbuyinfo->person_count_final-$groupbuyinfo->person_count_init));
            //若真实显示的值小于应该显示的数值
            if($groupbuyinfo->person_count_show<$now_show_count){
                $groupbuyinfo->person_count_show=$now_show_count;
                $groupbuyinfo->save();
            }
        }
    }
    /**
        实际购买增加展示人数
    **/
    public static function updateBuyCount($groupbuyid){
        $groupbyinfo=self::find()->where(['groupbuyid'=>$groupbuyid])->andWhere(['status'=>1])->one();
        $groupbyinfo->person_count_show=$groupbyinfo->person_count_show+1;
        $groupbyinfo->save();
    }

    /**
     * 获取新团购数量，用于小红点展示
     * 从缓存中读取数据,如果缓存失效则不用处理
     * @param unknown $uid
     * @return number|unknown
     */
    static function getNewGroupBuyNum($uid) {
        $redis = Yii::$app->cache;
        $redis_key = 'ms:groupbuymsg';
        
        $ret = $redis->zscore($redis_key, $uid);
        if(empty($ret)){
            return 0;
        }
        else{
            return $ret;
        } 
    }

    /**
     * 查看团购后清除小红点
     * @param unknown $uid
     * @param unknown $otheruid
     */
    static function removeRed($uid){
        $redis = Yii::$app->cache;
        $redis_key = 'ms:groupbuymsg';
        $redis->zrem($redis_key,$uid);
    }
    
}
