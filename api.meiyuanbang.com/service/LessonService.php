<?php
namespace api\service;

use Yii;
use common\models\myb\Lesson;
use common\service\DictdataService;
use common\service\CommonFuncService;
use api\service\LessonSectionService;
use api\service\LessonPicService;
use api\service\FavoriteService;
use api\service\LessonDescService;

/**
 * 
 * @author Administrator
 *
 */
class LessonService extends Lesson 
{
	/**
	 * 根据主类型获取最新的指定条数的考点基本信息
	 * @param unknown $maintypeid
	 * @param unknown $limit
	 */
	static function getIdsByMainType($maintypeid,$limit,$correctid=0){		
		//缓存
		$redis = Yii::$app->cache;
		$redis_key = 'lesson_maintype_topnew'.$limit.'_'.$maintypeid.'_'.$correctid;
		
		//检查缓存是否存在，如果不存在才重建
		$ret = $redis->getValue($redis_key);
		if($ret){
			return json_decode($ret,true);
		}
		if (!$ret) {
			//从数据库中获取
			$ret = parent::find()
			->select('lessonid')
			->where(['status'=>0])
			->andWhere(['maintype'=>$maintypeid])
			->limit($limit)
			->orderBy('lessonid DESC')
			->all();
			//缓存半小时
			if($ret){
				$ids=[];
				foreach($ret as $k=>$v){
					$ids[]=$v['lessonid'];
				}
				$redis->setValue($redis_key,json_encode($ids),1800);
				return $ids;  
			}
			
		}
		return null;
	}
		/**
	 * 根据主类型和子类型获推荐考点id
	 * @param unknown $maintypeid
	 * @param unknown $subtypeId
	 * @return unknown
	 */
	static function getIdsByTypeLimit($maintypeid,$subtypeid,$correctid=0,$limit=0){
		$redis = Yii::$app->cache;
		$redis_key = 'lesson_newsids_correctid_'.$correctid;
	
		$ret = $redis->lrange($redis_key,0, -1);
		if($ret){
			return $ret;
		}
		//从数据库获取
		$query=(new \yii\db\Query())
		->select(['lessonid'])
		->from(parent::tableName())
		->where(['maintype'=>$maintypeid])
		->andWhere(['subtype'=>$subtypeid])
		->andWhere(['status'=>0])
		->orderBy('lessonid');
		if($limit){
			$query->limit($limit);
		}
		$ids =$query ->all();
		
		if($ids){
			foreach($ids as $k=>$v){
				$redis->lpush($redis_key,$v['lessonid']);
				$ret[] = $v['lessonid'];
			}
			//缓存3天
			$redis->expire($redis_key, 3600);
		}		
		return $ret;
	}


	/**
	 * 根据主类型和子类型获取所有考点id
	 * @param unknown $maintypeid
	 * @param unknown $subtypeId
	 * @return unknown
	 */
	static function getIdsByType($maintypeid,$subtypeid,$correctid=0){
		$redis = Yii::$app->cache;
		$redis_key = 'lesson_newsids_'.$maintypeid.'_'.$subtypeid.'_'.$correctid;
	
		$ret = $redis->lrange($redis_key,0, -1);
		if($ret){
			return $ret;
		}
		//从数据库获取
		$ids = (new \yii\db\Query())
		->select(['lessonid'])
		->from(parent::tableName())
		->where(['maintype'=>$maintypeid])
		->andWhere(['subtype'=>$subtypeid])
		->andWhere(['status'=>0])
		->orderBy('lessonid')
		->all();
		if($ids){
			foreach($ids as $k=>$v){
				$redis->lpush($redis_key,$v['lessonid']);
				$ret[] = $v['lessonid'];
			}
			//缓存3天
			$redis->expire($redis_key, 3600*24*3);
		}		
		return $ret;
	}
	
	/**
	 * 分页获取考点id列表
	 * @param unknown $maintypeid
	 * @param unknown $subtypeId
	 * @param unknown $lastid 首页请填0
	 * @param unknown $limit
	 */
	static function getIdsByPage($maintypeid,$subtypeid,$lastid,$limit){
		//首先获取到所有lessonid
		$ids = static::getIdsByType($maintypeid, $subtypeid);
		$num = 0;
		$ret = [];
		foreach ($ids as $k=>$v){
			if($lastid==0){
				$ret[] = $v;
				$num++;
			}
			else{
				if($v<$lastid){
					$ret[] = $v;
					$num++;
				}
			}
			if($num>=$limit){
				break;
			}
		}
		return $ret;
	}
	
	/**
	 * 获取考点的基本信息
	 * 缓存里添加了对应的所有节点id
	 * 缓存与老版本兼容
	 * @param unknown $lessonid
	 */
	static function getById($lessonid,$uid=-1){
		$redis = Yii::$app->cache;
		$redis_key = 'lesson_detail_' . $lessonid;
		$ret = $redis->hgetall($redis_key);		
		if(!$ret){
			//从数据库读取
			$ret = parent::findOne(['lessonid'=>$lessonid])->attributes;
			$sectionids = LessonSectionService::getIdsByLessonid($lessonid);
			$strids = '';
			foreach ($sectionids as $k=>$v){
				$strids .= $v['sectionid'].',';
			}
			$strids = trim($strids,',');
			$ret['sectionids'] = $strids;
			//存缓存,保留24*3小时
			$redis->hmset($redis_key, $ret);
			$redis->expire($redis_key,3600*24*3);
		}
		

		$ret['fav']=FavoriteService::getFavStatusByUidTid($uid, $lessonid, 9);
		$favinfo=FavoriteService::getFavInfoByContent($lessonid,9);
        $ret=array_merge($ret,$favinfo);
		return $ret;
	}
	
	/**
	 * 获取考点基本信息和第一张图片
	 * 主要给跟着画首页和分类型列表页获取数据
	 * @param unknown $lessonid
	 */
	static function getLessonWithFirstPic($lessonid){
		$ret = static::getById($lessonid);
		//获取考点的第一个节点
		if(!$ret['sectionids']){
			return null;
		}
		//改为取最后一张图,所以取最后一个节点
		$arrsec = explode(',', $ret['sectionids']);
		$num = count($arrsec)-1;
		$sectionid = $arrsec[$num];		
		//获取节点的图片信息
		$arrpic = LessonPicService::getBySectionId($sectionid);
		if(!$arrpic){
			return null;
		}
		//获取第一张图片
		//$ret['imgs']['l'] = CommonFuncService::getPicByType2($arrpic[0]['picurl'], $arrpic[0]['picw'], $arrpic[0]['pich'], 'l');
		//获取最后一张图片
		$num = count($arrpic)-1;
		$ret['imgs']['l'] = CommonFuncService::getPicByType2($arrpic[$num]['picurl'], $arrpic[$num]['picw'], $arrpic[$num]['pich'], 'l');
		//判断new,一周内
		$timespan = time() - $ret['ctime']; 
		if($timespan < 3600*24*7){
			$ret['isnew'] = 1;
			$ret['ishot'] = 0;
		}
		else{
			//非new才判断hot
			$ret['isnew'] = 0;
			if($ret['hits'] > 100){
				$ret['ishot'] = 1;
			}
			else {
				$ret['ishot'] = 0;
			}			
		}		 
		return $ret;
	}
	
	/**
	 * 对标题进行模糊搜索
	 * @param unknown $keyword
	 */
	static function getIdsByTitle($keyword,$lastid,$limit){
		$query = (new \yii\db\Query())
		->select(['lessonid'])
		->from(parent::tableName())
		->where(['status'=>0])
		->andWhere(['<>','maintype',7]) //不搜试卷
		->andWhere(['like','title',$keyword]); //不搜试卷
		//分页
		if($lastid>0){
			$query = $query->andWhere(['<','lessonid',$lastid]);
		}
		$ids = $query->orderBy('lessonid desc')
		->limit($limit)
		->all();
		return $ids;
	}
	
	/**
	 * 增加点击量
	 * @param unknown $lessonid
	 */
	static function addHits($lessonid){
		$model = static::findOne(['lessonid'=>$lessonid]); 		
		$model->IsNewRecord = false;
		$model->hits += 1; 
		//数据库中点击量+1
		if($model->save()){
			//更新缓存中点击量
			$redis = Yii::$app->cache;
			$redis_key = 'lesson_detail_' . $lessonid;
			$redis->hincrby($redis_key,'hits',1);
		}		
	}
	
	/**
	 * 获取考点总数
	 * @return Ambigous <string, boolean>
	 */
	static function getLessonNum(){
		//缓存
		$redis = Yii::$app->cache;
		$redis_key = 'lesson_totalnum'; 
	
		//检查缓存是否存在，如果不存在才重建
		$ret = $redis->getValue($redis_key);
		if (!$ret) {
			//从数据库中获取
			$ret = $query = static::find()
			->where(['status'=>0])
			->count('lessonid');
			//缓存1小时
			$redis->setValue($redis_key,$ret,3600);
		}
		return $ret;
	}
	
	/**
	 * 首页无能力模型时推荐的跟着画id
	 */
	static function getRecommendIds(){
		$redis = Yii::$app->cache;
		$redis_key = 'lesson_nocapacity_ids';
	
		$ret = $redis->getValue($redis_key);
		if($ret){
			$ret=explode(',',$ret );
			return $ret;
		}
		//从数据库获取
		$ids = (new \yii\db\Query())
		->select(['lessonid'])
		->from(parent::tableName())
		->where(['status'=>0])
		->orderBy('lessonid')
		->limit(2)
		->all();
		$ret = '';
		if($ids){
			foreach($ids as $k=>$v){
				if($ret != ''){
					$ret .= ',';
				}
				$ret .= $v['lessonid'];
			}
			//缓存5分钟
			$redis->setValue($redis_key,$ret,300);
			$ret = explode(',',$ret );
		}
		return $ret;
	}

	/**
     * 根据一二级分类随机获取指定数量考点
     * @param  [type] $course_search_catalog [description]
     * @param  [type] $limit                 [description]
     * @return [type]                        [description]
     */
    public static function getLessonByCatalogRand($lesson_search_catalog){
    	$ret_data=[];
    	if($lesson_search_catalog){
    		foreach ($lesson_search_catalog as $key => $value) {
    			$slessonid=self::find()->select('lessonid')->where(['status'=>0])->andWhere(['maintype'=>$value['f_catalog_id']])->andWhere(['subtype'=>$value['s_catalog_id']])->limit($value['limit'])->orderBy("rand()")->all();

    			foreach ($slessonid as $key1 => $value1) {
    				 $lesson_info=lessonService::getLessonWithFirstPic($value1['lessonid']);
		            if($lesson_info){
		            	$ret_data[]=$lesson_info;
		            }
    			}
    		}
    	}
    	return $ret_data;
    
    }
    /**
     * 通过一二级分类获取课程列表
     * @param  integer $maintype [description]
     * @param  integer $subtype  [description]
     * @param  [type]  $lastid   [description]
     * @param  integer $rn       [description]
     * @return [type]            [description]
     */
    public static function getLessonList($maintype=0,$subtype=0,$lastid=NULL,$rn=50){ 
        $redis = Yii::$app->cache;
        $rediskey="lesson_list_".$maintype."_".$subtype;
       // $redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::getLessonIds($maintype,$subtype);
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value['lessonid'].',';
                $ret = $redis->rpush($rediskey, $value['lessonid'],true);
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
            $ids_data=array_splice($list_arr, $idx+1,$rn);
           /* $ids_data=$redis->lrange($rediskey,$idx+1, $idx+$rn);*/
        }
        return $ids_data;
    }
    /**
     * 数据库获取对应分类下缓存
     * @param  [type] $maintype [description]
     * @param  [type] $subtype  [description]
     * @return [type]           [description]
     */
    public static function getLessonIds($maintype,$subtype){
    	$query=self::find()->select("lessonid")->where(['status'=>0]);
    	if($maintype){
    		$query->andWhere(['maintype'=>$maintype]);
    	}
    	if($subtype){
    		$query->andWhere(['subtype'=>$subtype]);
    	}
    	return $query->all();
    }
    /**
     * 列表获取详情
     * @param  [type] $lessonids [description]
     * @return [type]            [description]
     */
    public static function getListDetail($lessonids){
    	$ret=[];
    	foreach ($lessonids as $key => $value) {
    		$lesson=self::getLessonWithFirstPic($value);
			$ret[]=$lesson; 
    	}
    	return $ret;
    }
    /**
     * 课程详情页获取
     * @param  [type] $lessonid [description]
     * @return [type]           [description]
     */
    public static function getLessonDetail($lessonid,$uid=-1){
		$ret=self::getById($lessonid);
		if(empty($ret)){
			return [];
		}
		$sectionids = explode(',', $ret['sectionids']);
    	$imgcount = 0;
    	foreach ($sectionids as $k=>$v){
    		//取考点信息
    		$section = LessonSectionService::getById($v);
    		$imgs = LessonPicService::getBySectionId($v);
    		$section['img'] = $imgs;
    		$imgcount += count($imgs);
    		$ret['section'][] = $section;

    	}

    	$ret['lessondesc']=LessonDescService::getLessonDescByLessonid($lessonid);
    	$ret['imgcount'] = $imgcount;
        //增加批改分享url ji图片
        $ret['shareurl'] = Yii::$app->params['sharehost'].'/lesson?lessonid='.$lessonid;
        $ret['shareimg'] = LessonService::getLessonWithFirstPic($lessonid)['imgs']['l']['url'];
        return $ret;
	}
        
        /**
     * 跟着画
     * @param type $tid
     * @return type
     */
    public static function getLessonOne($tid) {
        $res = self::find()->select(['lessonid', 'title', 'coverurl'])->where(['lessonid' => $tid])->asArray()->one();
        return $res;
    }
    /**
     * 跟着画根据分类随机获取对应列表
     * @param  [type]  $lessonid   [description]
     * @param  [type]  $maintypeid [description]
     * @param  [type]  $subtypeid  [description]
     * @param  integer $limit      [description]
     * @param  string  $order      [description]
     * @return [type]              [description]
     */
    public static function getLessonByType($lessonid=NULL,$maintypeid=NULL,$subtypeid=NULL,$limit=4,$order="rand"){
    	$query=self::find()->select("lessonid")->where(['status'=>0]);
    	//一级分类
    	if($maintypeid){
    		$query->andWhere(['maintype'=>$maintypeid]);
    	}
    	//二级分类
    	if($subtypeid){
    		$query->andWhere(['subtype'=>$subtypeid]);
    	}
    	//推荐刨除本身
    	if($lessonid){
    		$query->andWhere(['<>','lessonid',$lessonid]);
    	}
    	//排序
    	switch ($order) {
    		case 'rand':
    			$query->orderBy("rand()");
    			break;
    		case 'desc':
    			$query->orderBy("lessonid desc");
    			break;
    	}
    	//数量
    	if($limit){
			$query->limit($limit);
    	}
    	$lessonids=$query->asArray()->all();
    	$ret=[];
    	//处理返回数据
    	if($lessonids){
    		foreach ($lessonids as $key => $value) {
	    		$ret[]=$value['lessonid'];
	    	}
    	}
    	return $ret;
    }
   	/**
   	 * 获取跟着画内容推荐
   	 * @param  [type]  $lessonid   [description]
   	 * @param  [type]  $maintypeid [description]
   	 * @param  [type]  $subtypeid  [description]
   	 * @param  integer $limit      [description]
   	 * @return [type]              [description]
   	 */
    public static function getLessonRecommend($lessonid,$maintypeid=NULL,$subtypeid=NULL,$limit=4){
    	//获取对应二级分类推荐内容
    	$lessonids=self::getLessonByType($lessonid,$maintypeid,$subtypeid,$limit);
    	if(count($lessonids)<4){
    		//二级分类内容若不及四个则获取一级分类
			$lessonids=self::getLessonByType($lessonid,$maintypeid);
    	}
    	if(count($lessonids)<4){
    		//一级分类若不及四个 则获取所有
			$lessonids=self::getLessonByType($lessonid);
    	}
    	return $lessonids;
    }
    /**
     * 缓存获取跟着画推荐内容
     * @param  [type]  $lessonid   [description]
     * @param  [type]  $maintypeid [description]
     * @param  [type]  $subtypeid  [description]
     * @param  integer $limit      [description]
     * @return [type]              [description]
     */
    public static function getLessonRecommendRedis($lessonid,$maintypeid=NULL,$subtypeid=NULL,$limit=4){
    	$redis = Yii::$app->cache;
		$redis_key = 'lesson_recommend_'.$lessonid;
		$ret = $redis->get($redis_key);
		//$redis->delete($redis_key);
		if($ret){
			return json_decode($ret,true);
		}
		//数据库获取
		$ret = static::getLessonRecommend($lessonid,$maintypeid,$subtypeid,$limit);
		if($ret){
			
			$ret=json_encode($ret);
			//存缓存,保留1小时
			$redis->set($redis_key, $ret);
			$redis->expire($redis_key,3600);
		}
		return json_decode($ret,true); 
    }



      /**
     * 从缓存中获取素材首页应该显示的ids
     * 建立缓存时根据提前设定的比例保证不同子类型的跟着画依次展现
     */
    public static function getLessonIdsByLevelFromCache($limit,$lattid){
    	//从缓存中获取
    	$redis = Yii::$app->cache;
    	$redis_key = 'Lesson_list_recommend';
    	
    	//检查缓存是否存在，如果不存在才重建
    	if(! $redis->exists($redis_key)){
    		//如果缓存不存在则建立缓存
    		static::LessonBuildCache();
    	}
    	//从缓存获取ids
    	$ids = $redis->lrange($redis_key,0, -1);
    	//分页数据获取
    	$idx = 0;
    	if(isset($lattid)){
    		$idx = array_search($lattid, $ids);
    		if($idx){
    			$idx += 1;
    		}
    		else{
    			$idx = 0;
    		}	
    	}
    	$tmpids = array_slice($ids,$idx,$limit);
    	//兼容以前版本，修改为tid模式的数组
    	$ret = [];
    	foreach ($tmpids as $k=>$v){
    		//$tmp['lessonid'] = $v; 
    		$ret[] = $v;
    	}
    	return $ret;
    }

    /**
     * 跟着画推荐显示列表检查缓存
     * 如果没有缓存则建立
     * 在建立缓存过程中需加锁
     * @param unknown $mlevel
     */
    private static function LessonBuildCache(){
    	//从缓存中获取
    	$redis = Yii::$app->cache;
    	$redis_key = 'Lesson_list_recommend';
    	$cachesize = 1000;
    	 
    	//todo 此处加锁，保证用户不会重复建立缓存
    	//检查缓存是否存在，如果不存在才重建,通过此处检查和前面的锁保证不重复建立
    	if(! $redis->exists($redis_key)){
    		$allsubdata = DictdataService::getLessonDisplayCountType();
    		
    		//取出所有分类的数据
    		foreach ($allsubdata as $k=>$v){
    			$sublimit = DictdataService::getLessonSelectCountByMainType($v['maintype']);
    			foreach ($v['subtype'] as $k1 => $v1) {
    				$ids = static::find()
	    			->select(['lessonid'])
	    			->where(['status'=>0])
	    			->andWhere(['maintype'=>$v['maintype']])
	    			->andWhere(['subtype'=>$v1['subtype']])
	    			->orderBy('ctime DESC')
	    			->limit($sublimit)
	    			->asArray()
	    			->all();
	    			$allsubdata[$k]['subtype'][$k1]['ids'] = $ids;
    			}
    		}
    			

    		//按照比例把id存到缓存
    		$iscontinue = true;
    		$cacheNum = 0;
    		//所有ids数组都为空或者已经缓存够数据都会退出
    		while($iscontinue){
    			//如果所有ids数组都为空则停止循环
    			$iscontinue = false;
    			foreach ($allsubdata as $k=>$v){
    				$sublimit = DictdataService::getLessonSelectCountByMainType($v['maintype']);
    				foreach ($v['subtype'] as $k1 => $v1) {
	    				//按比例存
	    				if(count($v1['ids'])>0){
	    					for($i=0;$i<$v1['displaycount'];$i++){
	    						if(count($v1['ids'])>0){
	    							//写缓存
	    							$ret = $redis->rpush($redis_key, $v1['ids'][0]['lessonid'],true);
	    							array_splice($v1['ids'],0,1);
	    							$cacheNum += 1;
	    						}
	    					}
	    					if(count($v1['ids'])>0){
	    						//如果不是所有子类型的数据都为空则可以继续循环
	    						$iscontinue = true;
	    					}
	    					$allsubdata[$k]['subtype'][$k1]=$v1;
	    				}
    				}
    			}
    			//循环一次后检测缓存数据是否已经达到最大值
    			if($cacheNum>=$cachesize){
    				$iscontinue = false;
    			}
    		}
    		//缓存3小时失效
    		$redis->expire($redis_key, 3600*3);
    	}
    	//todo 释放锁    	
    }


}
