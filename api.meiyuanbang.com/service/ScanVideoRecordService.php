<?php
namespace api\service;
use common\models\myb\ScanVideoRecord;
use Yii;
use common\redis\Cache;
use api\service\UserDetailService;
/**
* 相关方法
*/
class ScanVideoRecordService extends ScanVideoRecord
{
	/**
	 * 增加浏览记录 类型：1=>直播,2=>课程
	 * @param [type] $uid         [description]
	 * @param [type] $subjecttype [description]
	 * @param [type] $subjectid   [description]
	 */
	public static function addScanRecord($uid,$subjecttype,$subjectid){
		$find_record=self::find()->where(['uid'=>$uid])->andWhere(['subjecttype'=>$subjecttype])->andWhere(['subjectid'=>$subjectid])->one();
		$ret=false;
		if(empty($find_record)){
			$model=new ScanVideoRecord();
			$model->uid=$uid;
			$model->subjecttype=$subjecttype;
			$model->subjectid=$subjectid;
			$model->ctime=time();
			$ret=$model->save();
			$redis = Yii::$app->cache;
			$redis->delete("scan_video_list".$uid);
		}else{
			$find_record->ctime=time();
			$ret=$find_record->save();
			$redis = Yii::$app->cache;
			$redis->delete("scan_video_list".$uid);
		}
		return $ret;
	}
	/**
	 * 删除浏览记录
	 * @param  [type] $uid      [description]
	 * @param  [type] $recordid [description]
	 * @return [type]           [description]
	 */
	public static function delScanVideoRecord($uid,$recordid){
		$ret=self::deleteAll('recordid in('.$recordid.') and uid='.$uid);
        if($ret){
        	$redis = Yii::$app->cache;
	 	    $rediskey="scan_video_list".$uid;
	 	    $redis->delete($rediskey);
	 	    return true;
        }else{
        	return false;
        }
	}
 	public static function getScanVideoList($uid,$lastid=NULL,$rn=50){
 	    $redis = Yii::$app->cache;
 	    $rediskey="scan_video_list".$uid;
 	    //$redis->delete($rediskey);
 	    $list_arr=$redis->lrange($rediskey,0, -1);
 	    //判断缓存是否有内容 若无则重新建立缓存
 	    if(empty($list_arr)){
 	        $model=self::getScanVideoListDb($uid);
 	        $ids='';
 	        foreach ($model as $key => $value) {
 	            $ids.=$value['recordid'].',';
 	            $ret = $redis->rpush($rediskey, $value['recordid'],true);
 	        }
 	        $redis->expire($rediskey,3600*24*3);
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
 	public static function getScanVideoListDb($uid){
 		return self::find()->select("recordid")->where(['uid'=>$uid])->orderBy("ctime desc")->all();
 	}
 	/**
 	 * 获取详情
 	 */
 	public static function getDetail($recordid){
 	    $rediskey="scan_video".$recordid;
 	    $redis = Yii::$app->cache;
 	    // $redis->delete($rediskey);
 	    $detail=$redis->hgetall($rediskey);
 	    if (empty($detail)) {
 	       $detail=self::find()->where(['recordid'=>$recordid])->asArray()->one();
 	       if($detail){
 	            $redis->hmset($rediskey,$detail);
 	            $redis->expire($rediskey,3600*24*3);
 	       }
 	    }
 	    return $detail;
 	}
 	/**
 	 * 根据分类得到观看用户列表 类型：1=>直播,2=>课程
 	 * @param  [type] $subjecttype [description]
 	 * @param  [type] $subjectid   [description]
 	 * @return [type]              [description]
 	 */
 	public static function getScanUser($subjecttype,$subjectid){
 		$uids=self::getScanUserList($subjecttype,$subjectid,NULL,5);
 		$return_user=[];
 		foreach ($uids as $key => $value) {
 			$return_user[]=UserDetailService::getByUid($value);
 		}
 		return $return_user;
 	}
 	/**
 	 * 数据库获取直播 课程观看用户
 	 * @param  [type] $subjecttype [description]
 	 * @param  [type] $subjectid   [description]
 	 * @return [type]              [description]
 	 */
 	public static function getScanUserListDb($subjecttype,$subjectid){
 		return self::find()->select('uid')->where(['subjecttype'=>$subjecttype])->andWhere(['subjectid'=>$subjectid])->all();
 	}
 	/**
 	 * 获取直播课程观看用户id缓存
 	 * @param  [type]  $subjecttype [description]
 	 * @param  [type]  $subjectid   [description]
 	 * @param  [type]  $lastid      [description]
 	 * @param  integer $rn          [description]
 	 * @return [type]               [description]
 	 */
 	public static function getScanUserList($subjecttype,$subjectid,$lastid=NULL,$rn=10){
 	    $redis = Yii::$app->cache;
 	    $rediskey="scan_video_user_list".$subjecttype."_".$subjectid;
 	   // $redis->delete($rediskey);
 	    $list_arr=$redis->lrange($rediskey,0, -1);
 	    //判断缓存是否有内容 若无则重新建立缓存
 	    if(empty($list_arr)){
 	        $model=self::getScanUserListDb($subjecttype,$subjectid);
 	        $ids='';
 	        foreach ($model as $key => $value) {
 	            $ids.=$value['uid'].',';
 	            $ret = $redis->rpush($rediskey, $value['uid'],true);
 	        }
 	        $redis->expire($rediskey,300);
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
 	public static function getScanCount($uid){
 		return self::find()->where(['uid'=>$uid])->count();
 	}

}