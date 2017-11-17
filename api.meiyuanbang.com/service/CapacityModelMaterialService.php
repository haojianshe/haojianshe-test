<?php
namespace api\service;

use common\models\myb\CapacityModelMaterial;
use Yii;
use common\redis\Cache;
use common\service\CommonFuncService;
use api\service\CapacityModelMaterialZanService;
use api\service\UserDetailService;
use api\service\FavoriteService;
use common\service\dict\CapacityModelDictDataService;
/**
* 能力模型图对应素材
*/
class CapacityModelMaterialService extends CapacityModelMaterial
{   
	/**
	 * 根据画作类型id和打分类型id获取推荐素材
	 * @param unknown $catalogid
	 * @param unknown $itemid
	 */
	static function getRecommend($fcatalogid,$scatalogid,$itemid,$limit){
		$redis = Yii::$app->cache;
		$redis_key = 'CapacityModelMaterial_' . $fcatalogid . '_' . $scatalogid . '_' . $itemid;
		
		$json = $redis->get($redis_key);
		if($json){
			$ret = json_decode($json,true);
		}
		else{
			//没有取到则从数据库获取
			$query = (new \yii\db\Query());
			$query->select('*')
			->from(parent::tableName())
			->where(['status'=>0]);
			if($fcatalogid>0){
					$query->andWhere(['f_catalog_id'=>$fcatalogid]);
			}
			if($scatalogid>0){
					$query->andWhere(['s_catalog_id'=>$scatalogid]);
			}
			
			
			$ret=$query->andWhere(['item_id'=>$itemid])
			->orderBy('rand()')
			->limit($limit)
			->all();
			//数据必有不做无数据检查
			//存缓存
			$strjson = json_encode($ret);
			$redis->set($redis_key, $strjson,3600*24);
		}
		//将picurl改为不同大小图片的数组
		foreach ($ret as $k=>$v){
			//获取各种尺寸的图片，l n是必须有的
			$arrtmp = json_decode($v['picurl'], true);
			if(empty($arrtmp['l'])){
				$arrtmp['l'] = CommonFuncService::getPicByType($arrtmp['n'], 'l');
			}
			$ret[$k]['imgs']=$arrtmp;
			unset($ret[$k]['picurl']);	
		}	
		return $ret;
	}	/**
	 * 根据画作类型id和打分类型id获取推荐素材
	 * @param unknown $catalogid
	 * @param unknown $itemid
	 */
	static function getRecommendByCorrectid($correctid,$fcatalogid,$scatalogid,$itemid,$limit){
		$redis = Yii::$app->cache;
		$redis_key = 'CapacityModelMaterialByCorrectid_' . $correctid."_".$fcatalogid."_".$scatalogid."_".$itemid;
		
		$json = $redis->get($redis_key);
		if($json){
			$ret = json_decode($json,true);
		}
		else{
			//没有取到则从数据库获取
			$query = (new \yii\db\Query());
			$query->select('*')
			->from(parent::tableName())
			->where(['status'=>0]);
			if($fcatalogid>0){
					$query->andWhere(['f_catalog_id'=>$fcatalogid]);
			}
			if($scatalogid>0){
					$query->andWhere(['s_catalog_id'=>$scatalogid]);
			}
			
			
			$ret=$query->andWhere(['item_id'=>$itemid])
			->orderBy('rand()')
			->limit($limit)
			->all();
			//数据必有不做无数据检查
			//存缓存
			$strjson = json_encode($ret);
			$redis->set($redis_key, $strjson,3600*1);
		}
		//将picurl改为不同大小图片的数组
		foreach ($ret as $k=>$v){
			//获取各种尺寸的图片，l n是必须有的
			$arrtmp = json_decode($v['picurl'], true);
			if(empty($arrtmp['l'])){
				$arrtmp['l'] = CommonFuncService::getPicByType($arrtmp['n'], 'l');
			}
			$ret[$k]['imgs']=$arrtmp;
			unset($ret[$k]['picurl']);	
		}	
		return $ret;
	}

	/**
	 * 获取能力模型素材详情
	 */
	public static function getMatreialDetail($materialid,$uid=-1){

	    $rediskey="capacitymodelmaterial_".$materialid;
	    $redis = Yii::$app->cache;
	    // $redis->delete($rediskey);
	    $detail=$redis->hgetall($rediskey);
	    if (empty($detail)) {
	       $detail=self::find()->where(['materialid'=>$materialid])->asArray()->one();
	       if($detail){
	            $redis->hmset($rediskey,$detail);
	            $redis->expire($rediskey,3600*24*3);
	       }
	    }
	    
	    //处理图片
    	$arrtmp = json_decode($detail['picurl'], true);
		if(empty($arrtmp['l'])){
			$arrtmp['l'] = CommonFuncService::getPicByType($arrtmp['n'], 'l');
		}
		if(empty($arrtmp['t'])){
			$arrtmp['t'] = CommonFuncService::getPicByType($arrtmp['n'], 't');
		}
		if(empty($arrtmp['s'])){
			$arrtmp['s'] = CommonFuncService::getPicByType($arrtmp['n'], 's');
		}
		$detail['imgs']=$arrtmp;
		
		//点赞收藏信息
		$detail['fav']=0;
		$detail['is_zan']=0;
		$detail['follow_type']=0;
		if($uid>0){
			$detail['fav']=FavoriteService::getFavStatusByUidTid($uid,$materialid,5);
			$detail['is_zan']=CapacityModelMaterialZanService::getZanStatusByUid($materialid,$uid);
			$detail['follow_type'] = UserRelationService::getBy2Uid($uid, $detail['uid']);
		}
		//获取分类信息
		$detail['f_catalog']=CapacityModelDictDataService::getCorrectMainTypeNameById($detail['f_catalog_id']);
		$detail['s_catalog']=CapacityModelDictDataService::getCorrectSubTypeById($detail['f_catalog_id'],$detail['s_catalog_id']);
		//获取用户信息
		$user_info=UserDetailService::getByUid($detail['uid']);
		//兼容IOS 能力模型素材标签崩溃
		$detail['tags']=[];
		$detail=array_merge($user_info,$detail);
	    return $detail;
	}

	  /**
     * 获取详情页推荐id列表
     * @param unknown $materialid
     * @param unknown $f_catalog_id
     * @param unknown $s_catalog_id
     * @param unknown $limit
     */
    static function getRecommendIdsByMatreialId($materialid,$f_catalog_id,$s_catalog_id,$limit){
    	$ids = static::find()->select(['materialid'])
    	->where(['<','materialid',$materialid])
    	->andWhere(['status'=>0])
    	->andWhere(['f_catalog_id'=>$f_catalog_id])
    	->andWhere(['s_catalog_id'=>$s_catalog_id])
    	->andWhere(['<>','materialid',$materialid])
    	->orderBy('materialid desc')
    	->limit($limit)
    	->all();
    	if($ids){
    		$ret = null;
    		foreach ($ids as $id){
    			$ret[]=$id['materialid'];
    		}
    		return $ret;
    	}
    	return null;
    }
  /**
     * 获取详情页推荐出版社id列表
     * @param unknown $materialid
     * @param unknown $f_catalog_id
     * @param unknown $s_catalog_id
     * @param unknown $limit
     */
    public static function getPublishingMatreialId($uid,$f_catalog_id,$s_catalog_id,$limit){
    	$ids = static::find()->select(['materialid'])
    	->where(['uid'=>$uid])
    	->andWhere(['status'=>0])
    	->andWhere(['f_catalog_id'=>$f_catalog_id])
    	->andWhere(['s_catalog_id'=>$s_catalog_id])
    	->orderBy('materialid desc')
    	->limit($limit)
    	->all();
    	if($ids){
    		$ret = null;
    		foreach ($ids as $id){
    			$ret[]=$id['materialid'];
    		}
    		return $ret;
    	}
    	return null;
    }
	
}