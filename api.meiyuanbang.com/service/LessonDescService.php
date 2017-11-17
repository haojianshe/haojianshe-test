<?php
namespace api\service;

use Yii;
use common\models\myb\LessonDesc;
use common\models\myb\SoundResource;
use common\service\CommonFuncService;
/**
 * 
 * @author Administrator
 *
 */
class LessonDescService extends LessonDesc 
{
	/**
	 * 获取跟着画描述信息
	 * @param  [type] $lessonid [description]
	 * @return [type]           [description]
	 */
	public static function getLessonDescByLessonid($lessonid){
		$lessondescids=self::getLessondescidsRedis($lessonid);
		return self::getLessonDescListDetail($lessondescids);
	}
	/**
	 * 通过lessonids 获取详细信息
	 * @param  [type] $lessondescids [description]
	 * @return [type]                [description]
	 */
	public static function getLessonDescListDetail($lessondescids){
		$ret=[];
		if($lessondescids){
			foreach ($lessondescids as $key => $value) {
				$ret[]=self::getDetail($value);
			}
		}
		return $ret;
	}
	/**
	 * 缓存获取所有跟着画lessondescid 
	 * @param  [type] $lessonid [description]
	 * @return [type]           [description]
	 */
	public static function getLessondescidsRedis($lessonid){
	    $redis = Yii::$app->cache;
	    $rediskey="lesson_desc_list_".$lessonid;
	   	//$redis->delete($rediskey);
	    $list_arr=$redis->lrange($rediskey,0, -1);
	    //判断缓存是否有内容 若无则重新建立缓存
	    if(empty($list_arr)){
	        $model=self::find()->select("lessondescid")->where(['lessonid'=>$lessonid])->asArray()->all();
	        foreach ($model as $key => $value) {
	            $list_arr[]=$value['lessondescid'];
	            $ret = $redis->rpush($rediskey, $value['lessondescid'],true);
	        }
	        $redis->expire($rediskey,3600*24*3);
	    }

	    return $list_arr;
	}

	/**
	 * 获取详情
	 */
	public static function getDetail($lessondescid){
	    $rediskey="lesson_desc_detail_".$lessondescid;
	    $redis = Yii::$app->cache;
	    // $redis->delete($rediskey);
	    $detail=$redis->hgetall($rediskey);
	    if (empty($detail)) {
	       $detail=self::find()->where(['lessondescid'=>$lessondescid])->asArray()->one();
	       //处理语音
	       $sound=SoundResource::find()->where(['soundid'=>$detail['soundid']])->asArray()->one();
	       $detail['sound']=json_encode($sound);
	       if($detail){
	            $redis->hmset($rediskey,$detail);
	            $redis->expire($rediskey,3600*24*3);
	       }
	    }
	    //处理语音返回
	    $detail['sound']=json_decode( $detail['sound']);
	    $img=json_decode($detail['imgurl'],true);
	    $detail['img']['n']=$img;
	    $detail['img']['l']=CommonFuncService::getPicByType($img,"l");
	    $detail['img']['s']=CommonFuncService::getPicByType($img,"s");
	    $detail['img']['t']=CommonFuncService::getPicByType($img,"t");
	    return $detail;
	}
}
