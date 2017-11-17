<?php
namespace api\service;

use Yii;
use common\models\myb\Tweet;
use common\service\CommonFuncService;
use api\service\TagService;
use api\service\ResourceService;
use api\service\UserDetailService;
use api\service\CommentService;
use api\service\ZanService;
use api\service\UserRelationService;
use api\service\UserCoinService;
use api\service\CorrectService;
use common\service\DictdataService;
use common\service\dict\MaterialDictDataService;
use yii\db\Command;

/**
 * 
 * @author Administrator
 *
 */
class TweetService extends Tweet 
{
	/**
     * 新增帖子时处理广场列表缓存
     * 目前只处理新增，删除还在老接口
     * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
     */
    public function save($runValidation = true, $attributeNames = NULL){
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $redis_key = 'tweet_list_plaza';
        
        $ret = parent::save($runValidation,$attributeNames);
        //清除单个帖子的缓存,之前有没清掉缓存的情况，所以改为每次更新都会重新清缓存
        $redis->delete('tweet_' . $this->tid);
        //处理列表缓存
        if($isnew){
        	//如果是作品则更新缓存列表
        	if($this->type<3){
        		//增加到列表缓存里
        		$redis->zadd($redis_key . '_t', $this->utime*-1, $this->tid);
        	}            
        	//作品数+1
        	$redis_num='userext_'.$this->uid;
        	$num=$redis->hincrby($redis_num, 'tweet_num', 1);
        }
        else{
            //如果是删除则在广场列表中删除掉帖子id
            if($this->is_del==1){
            	//删除时作品和求批改都删缓存，为防止批改转作品的情况
            	$redis->zrem('tweet_list_plaza_t',$this->tid);
            	$redis->zrem('tweet_list_plaza_c_80',$this->tid);
            	//用户作品数-1
            	$redis_num='userext_'.$this->uid;
            	$num=$redis->hincrby($redis_num, 'tweet_num', -1);
            	if($num < 0) {
            		$redis->delete($redis_num);
            	}
            }
            else{
            	//只要不是删除都更新列表，因为暂时不能判断是否utime改变
            	if($this->type==4){
            		//如果是已批改则判断分数是否超过80分，目前只有80分存缓存
            		$model = CorrectService::getCorrectDetail($this->correctid);
            		if($model && $model['score'] && $model['score']>=80 && $model['correct_fee']==0){
            			$redis->zadd($redis_key.'_c_80', $this->utime*-1, $this->tid);
            		}
            	}
            	//如果是作品则更新缓存列表
            	if($this->type<3){
            		//增加到列表缓存里
            		$redis->zadd($redis_key . '_t', $this->utime*-1, $this->tid);
            	}
            }            
        } 
        return $ret;
    }
	
	/**
	 * 直接从数据库读取帖子信息
	 * 用来替代1.1.0版本的缓存方案，1.2后使用其zrange方式缓存
	 * @param unknown $tid 
	 * @param unknown $rn 每页条数
	 * @param string $with_corrent 是否取批改的记录 
	 * @return
	 */
	static function getPageByTid($tid,$rn,$with_corrent=true){
		$query = new \yii\db\Query();
		$query = $query->select('*')
		->from(parent::tableName())
		->where(['is_del'=>0])
		->andWhere(['<>','resource_id','']);
		//第一页时不需要tid条件
		if($tid!=0){
			$query = $query->andWhere(['<','tid',$tid]);
		}
		//是否带批改
		if(!$with_corrent){
			$query = $query->andWhere(['<','type',3]);
		}
		$query = $query->orderBy('tid DESC')
		->limit($rn);		
		$ret = $query->all();
		return $ret;
	}
	
	/**
	 * 根据最后更新时间获取帖子列表
	 * 从缓存中获取，如果没有取到则简历缓存
	 * score采用的是帖子最后时间*-1,最新的帖子乘-1后值越小，排在前边
	 * 1.2版本新增方法
	 * 2.0.1增加了$tweettype参数，''代表全部类型   t代表tweet c代表批改  c_1代表已批改
	 * @param unknown $lasttime
	 * @param unknown $rn
	 * @param string $tweettype
	 * @return Ambigous <NULL, unknown>|NULL
	 */	
	static function getPageByUtime($lasttime,$rn,$tweettype=''){
		$redis = Yii::$app->cache;
		$redis_key = 'tweet_list_plaza'; //广场帖子的缓存key
		
		if($tweettype != ''){
			$redis_key = $redis_key . '_' .$tweettype;
		}
		//从缓存中获取，如果没有取到则尝试建立缓存,只缓存  已批改的类型和老版本的帖子列表不需要缓存，只存批改和作品
		if($tweettype =='t' || $tweettype=='c_80'){
			static::buildListCache($tweettype);
			if($lasttime == 0){
				$min = '-inf';
				$ret = $redis->zrangebyscore($redis_key,$min,'+inf',[0,$rn]);
			}
			else{
				$min = $lasttime*-1;
				$ret = $redis->zrangebyscore($redis_key,$min,'+inf',[1,$rn]);
			}
			if($ret){
				return $ret;
			}
		}		
		//从数据库获取数据返回		
		$query = static::find()->select(['tid'])
		->where(['is_del'=>0])
		->andWhere(['<>','resource_id','']);
		if($lasttime){
			$query = $query->andWhere(['<','utime',$lasttime]);
		}
		switch ($tweettype){
			case 't':
				//帖子
				$query = $query->andWhere(['<','type',3]);
				break;
			case 'c':
				//批改
				$query = $query->andWhere(['>=','type',3])
				->andWhere(['<=','type',4]);
				$query = $query->andWhere("tid in (select  tid from myb_correct where correct_fee=0 order by correctid desc)");

				break;
			case 'c_1':
				//已批改
				$query = $query->andWhere(['type'=>4]);
				$query = $query->andWhere("tid in (select  tid from myb_correct where correct_fee=0 order by correctid desc)");
				break;
			case 'c_80':
				//80-100分的批改
				$query = $query->andWhere(['type'=>4]);
				$query = $query->andWhere("tid in (select  tid from myb_correct where score>=80 and correct_fee=0  order by correctid desc)");
				break;
			case 'c_70':
				//70-80分的批改
				$query = $query->andWhere(['type'=>4]);
				$query = $query->andWhere("tid in (select  tid from myb_correct where score<80 and score>=70 and correct_fee=0   order by correctid desc)");
				break;
			case 'c_60':
				$query = $query->andWhere(['type'=>4]);
				$query = $query->andWhere("tid in (select  tid from myb_correct where score<70 and score>=60 and correct_fee=0  order by correctid desc)");
				//60-70分的批改
				break;
		}
		$tids = $query->orderBy('utime DESC')
			->limit($rn)
			->all();
		if($tids){
			$ret = null;
			foreach ($tids as $tid){
				$ret[]=$tid['tid'];
			}
			return $ret;
		}
		return null;
	}
	
	/**
	 * 建立广场帖子id列表缓存
	 * @param string $tweettype 参考 getPageByUtime中的相同参数
	 * @param string $isExsits
	 */
	private static function buildListCache($tweettype='',$isExsits=true){
		$redis = Yii::$app->cache;
		$redis_key = 'tweet_list_plaza'; //广场帖子的缓存key
		$cachesize = 600;
		
		if($tweettype != ''){
			$redis_key = $redis_key . '_' .$tweettype;
		}		
		//检查缓存是否存在，如果不存在才重建
		if($isExsits && $redis->exists($redis_key)){
			return;
		}
		$redis->delete($redis_key);
		//建立缓存
		$query = static::find()
		->select(['tid','utime'])
		->where(['is_del'=>0]);
		switch ($tweettype){
			case 't':
				//帖子
				$query = $query->andWhere(['<','type',3]);
				break;
			case 'c':
				//批改
				$query = $query->andWhere(['>=','type',3])
				->andWhere(['<=','type',4]);
				$query = $query->andWhere("tid in (select  tid from myb_correct where correct_fee=0 order by correctid desc)");

				break;
			case 'c_80':
				//80-100分的批改
				$cachesize = 1000; //默认选项缓存长度加长
				$query = $query->andWhere(['type'=>4]);
				$query = $query->andWhere("tid in (select  tid from myb_correct where score>=80 and correct_fee=0 order by correctid desc)");
				break;
		}
		$tweetlist = $query->orderBy('utime DESC')
		->limit($cachesize)
		->all();
		if($tweetlist){
			foreach ($tweetlist as $model){
				$utime = $model['utime']*-1;
				$redis->zadd($redis_key, $utime, $model['tid']);
			}
			//缓存1天
			$redis->expire($redis_key, 3600*24);
		}		
	} 
	
	/**
	 * 获取帖子的相关信息
	 * 包括：帖子基本信息，所对应的的资源，资源对应的tag，评论数
	 * 与老版相比，去掉了发帖人信息，与fill方法中的重复
	 */
	static function getTweetInfo($tid){
		$redis = Yii::$app->cache;
		$redis_key = 'tweet_'.$tid;
		$ret = $redis->hgetall($redis_key);
		if ($ret) {
			return $ret;
		}
		//缓存不存在
		//(1)从数据库取基本信息
		$ret = static::findOne(['tid'=>$tid]);
		if(!$ret){
			return $ret;
		}
		$ret = $ret->attributes;
		//(2)处理图片资源和标签
		if(!$ret['resource_id']){
			return null;
		}		
		$imgs = [];
		$rids = explode(',', $ret['resource_id']);
		foreach($rids as $rid) {
			$resourcemodel = ResourceService::findOne(['rid'=>$rid]);
			//容错，rid有效，并且img有效
			if(!$resourcemodel){
				continue;
			}
			if(empty($resourcemodel['img'])){
				continue;
			}
			//获取各种尺寸的图片，l s t,n是必须有的
			$arrtmp = json_decode($resourcemodel['img'], true);
			if(empty($arrtmp['l'])){
				$arrtmp['l'] = CommonFuncService::getPicByType($arrtmp['n'], 'l');
			}
			if(empty($arrtmp['s'])){
				$arrtmp['s'] = CommonFuncService::getPicByType($arrtmp['n'], 's');
			}
			if(empty($arrtmp['t'])){
				$arrtmp['t'] = CommonFuncService::getPicByType($arrtmp['n'], 't');
			}
			if($resourcemodel['description']){
				$arrtmp['content'] = $resourcemodel['description'];
			}
			else{
				$arrtmp['content'] = ''; //兼容旧版本，不支持null
			}
			$arrtmp['resource_id'] = $rid;
			//获取图片对应的标签信息
			$arrtmp['taginfos'] = [];
			$tagmodels = TagService::findAll(['rid'=>$rid]);
			foreach ($tagmodels as $tagmodel){
				$userinfo = UserDetailService::getByUid($tagmodel['uid']);
				$taginfo = $tagmodel->attributes;
				$taginfo['sname'] = $userinfo['sname'];
				$taginfo['avatar'] = $userinfo['avatar'];
				$taginfo['ukind'] = $userinfo['ukind'];
				$taginfo['ukind_verify'] = $userinfo['ukind_verify'];	
				$arrtmp['taginfos'][] = $taginfo;
			}
			//加入图片数组
			$imgs[]=$arrtmp;
		}
		//encodeimgs信息用于缓存
		$ret['imgs']=json_encode($imgs);
		//(3)获取帖子评论信息,帖子subjecttype为0
		$ret['comment_num'] = CommentService::getCommentNum(0, $tid);
		//(4)保存到缓存
		//如果是未批改，则需要有批改id才进行缓存
		if($ret['type']!=3 || $ret['correctid']){
			$redis->hmset($redis_key,$ret);
			$redis->expire($redis_key, 3600*24);
		}
		return $ret;
	}
	
	/**
	 * 获取帖子详情，用于帖子详情页或者列表页
	 * @param unknown $tid 
	 * @param unknown $uid 当前用户的id
	 * @param unknown $withRelation 是否获取发帖人和浏览者的关注关系，在列表时不需要
	 */
	static function fillExtInfo($tid,$uid,$withRelation = false){
		//(1)获取帖子的基本信息
		$ret = static::getTweetInfo($tid);
		//批改并且没有批改id时不返回
		if($ret['type']==3 && !$ret['correctid']){
			return null;
		}
		//decode图片信息,兼容旧版本程序,
		if(isset($ret['imgs'])){
			$ret['imgs'] = json_decode($ret['imgs'], true);
		}
		else{
			if(isset($ret['img'])){
				//老版本接口，资源信息保存到img字段里
				$ret['imgs'] = json_decode($ret['img'], true);
			}
		}
        //处理批改
        if($ret['type']==4 or $ret['type']==3){
            $ret['correct']=CorrectService::getFullCorrectInfo($ret['correctid'],$uid);
             foreach ($ret['imgs'] as $key => $value) {
                if(empty($ret['imgs'][$key]['s'])){
                    $ret['imgs'][$key]['s']=CommonFuncService::getPicByType($ret['imgs'][$key]['n'],'s');
                }
            }
        }else{
            $ret['correct']=(object)null;
        }
		$ret['picnum'] = count($ret['imgs']);
		//(2)处理tag
		if(empty($ret['tags'])){
			$ret['tags'] = [];
		}
		else{
			$ret['tags'] = explode(',', $ret['tags']);
			if(count($ret['tags'])>3){
				 $ret['tags']= array_slice($ret['tags'], 0, 2);
			}
		}
		//(3)添加发帖人信息,包括基本信息和等级金币信息
		$userinfo = UserDetailService::getByUid($ret['uid']);
		$usercoin = UserCoinService::getByUid($ret['uid']);
		$ret['sname'] = $userinfo['sname'];
		$ret['avatar'] = $userinfo['avatar'];
		$ret['ukind'] = intval($userinfo['ukind']);
		$ret['intro'] = $userinfo['intro'];
		$ret['ukind_verify'] = $userinfo['ukind_verify'];
		$ret['featureflag'] = $userinfo['featureflag'];
        $ret['genderid'] = $userinfo['genderid'];
        $ret['provinceid'] = $userinfo['provinceid'];
        $ret['role_type'] = $userinfo['role_type'];
        $ret['professionid'] = $userinfo['professionid'];
        $ret['gender'] = DictdataService::getGenderByid($userinfo['genderid']);
        $ret['province'] = DictdataService::getUserProvinceById($userinfo['provinceid']);
        $ret['profession'] = DictdataService::getProfessionById($userinfo['professionid']);
		//金币等级
		if($usercoin){
			$ret['gradeid'] = $usercoin['gradeid'];
			$ret['remain_coin'] = $usercoin['remain_coin'];
		}
		//(4)分享
		$shareurl = Yii::$app->params['sharehost']. "/tweet/share?tid=" . $tid;
		$ret['share'] = ['num'=>2,'url'=>$shareurl];
		//(5)评论数
		if(isset($ret['comment_num'])) {
			$ret['comment'] = ['num'=>intval($ret['comment_num'])];
		}
		//(6)获取点赞信息和点赞标识
		$zanlist = ZanService::getByTid($tid);
        
		//判断用户是否赞过帖子
		$iszan = in_array($uid,$zanlist);
		$ret['praise'] = ['num' => count($zanlist),'flag' => $iszan];
        //点赞总数，赞人列表
        $user=array();
        $zanlist=array_slice($zanlist,0,10);
        $user=[];
        $username=[];
        foreach ($zanlist as $key => $value) {
            $user[] = UserDetailService::getByUid($value);
            $username[] = UserDetailService::getByUid($value)['sname'];
        }
        $ret['praise']['user_list']=$user;
        $ret['praise']['user']=$username;
		//(7)获取当前用户与发帖人的关注关系
		if($withRelation){
			$ret['follow_type'] = UserRelationService::getBy2Uid($uid, $ret['uid']);
		}
		else{
			$ret['follow_type'] = 0;
		}
		//(8)判断帖子是否点评类型
		$ret['istag'] = 0;
		foreach($ret['imgs'] as $imgmodel){
			if(count($imgmodel['taginfos'])>0){
				$ret['istag'] = 1;
				break;
			}
		}
		//处理title为空的老数据
		if(empty($ret['title'])){
			$ret['title']='帮星人 '.$ret["sname"].' 发布了'.count($ret['imgs']).'幅'.$ret["f_catalog"].'作品~';
		}
		//1.3版后内容不能为空
		if(empty($ret['content'])){			
			$ret['content']=$ret['title'];
		}
        //收藏  兼容老版本
        $ret['fav']=0;
		return $ret;
	}
	
	/**
	 * 给帖子添加加精 推荐等状态
	 * @param unknown $tweet
	 */
	static function fillFlag($tweet){
		//加精和推荐的值
		$digest = 1;
		$recommand = 2;
		
		//加精
		if($tweet['flag'] & $digest){
			$tweet['is_digest'] = 1;
		}
		else{
			$tweet['is_digest'] = 0;
		}
		//推荐
		if($tweet['flag'] & $recommand){
			$tweet['is_recommand'] = 1;
		}
		else{
			$tweet['is_recommand'] = 0;
		}
		//置顶,目前置顶未使用
		$tweet['is_top'] = 0;
		return $tweet;
	}

	 /**
     * 获取用户作品数
     *
     * @param int uid
     */
    static function getTweetNum($uid) {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select count(*) as count  from '.parent::tableName().' where uid='.$uid.' and is_del=0');
        return $command->queryAll()[0]['count'];
    }
    
    /**
     * 获取素材id列表和总条数
     * @param unknown $mlevel 主类型id
     * @param unknown $limit  
     * @param number $lattid  最后一条lastid
     * @param number $slevel  分类型id
     * @param unknown $tags   tag条件
     */
    static function getMaterialIds($mlevel,$limit,$lattid=0,$slevel=0,$tags=null){
    	//如果只是根据主类型选择，则使用缓存
    	if($slevel==0 && $tags==null){
    		$ret['ids'] = static::getMaterialIdsByMlevelFromCache($mlevel,$limit,$lattid);
    		return $ret;
    	}
    	
    	//直接数据库获取
    	$query = static::find()
    	->select(['tid'])
    	->where(['is_del'=>0])
    	->andWhere(['type'=>1])
    	->andWhere(['f_catalog_id'=>$mlevel]);
    	//二级分类
    	if($slevel!=0){
    		$query = $query->andWhere(['s_catalog_id'=>$slevel]);
    	}
    	//tags
    	if($tags){
    		foreach ($tags as $k=>$v){
    			$query = $query->andWhere(['>',"LOCATE('".$v."', tags)",0]);
    		}
    	}
    	//是否为分页
    	if($lattid!=0){
    		//非第一页时不在计算总数
    		$query = $query->andWhere('ctime <(select `ctime` from `'. parent::tableName() .'` WHERE `tid` ='. $lattid .')');
    	}
    	else{
    		$countquery = $query;
    		$count = $countquery->count('tid');  		
    		$ret['totalnum'] = $count;
    	}
    	$ids = $query->orderBy('ctime DESC')
    			->limit($limit)
    			->all();
    	$ret['ids'] = $ids;
    	return $ret;
    }

    /**
     * 从缓存中获取素材首页应该显示的ids
     * 建立缓存时根据提前设定的比例保证不同子类型的素材依次展现
     */
    private static function getMaterialIdsByMlevelFromCache($mlevel,$limit,$lattid){
    	//从缓存中获取
    	$redis = Yii::$app->cache;
    	$redis_key = 'material_list_mlevel' . '_' .$mlevel;
    	
    	//检查缓存是否存在，如果不存在才重建
    	if(! $redis->exists($redis_key)){
    		//如果缓存不存在则建立缓存
    		static::materialBuildCache($mlevel);
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
    		$tmp['tid'] = $v; 
    		$ret[] = $tmp;
    	}
    	return $ret;
    }

    /**
     * 素材首页显示列表检查缓存
     * 如果没有缓存则建立
     * 在建立缓存过程中需加锁
     * @param unknown $mlevel
     */
    private static function materialBuildCache($mlevel){
    	//从缓存中获取
    	$redis = Yii::$app->cache;
    	$redis_key = 'material_list_mlevel' . '_' .$mlevel;
    	$cachesize = 1000;
    	 
    	//todo 此处加锁，保证用户不会重复建立缓存
    	//检查缓存是否存在，如果不存在才重建,通过此处检查和前面的锁保证不重复建立
    	if(! $redis->exists($redis_key)){
    		$allsubdata = MaterialDictDataService::getDisplayCountByMainType($mlevel);
    		$sublimit = MaterialDictDataService::getSelectCountByMainType($mlevel);
    		//取出所有分类的数据
    		foreach ($allsubdata as $k=>$v){
    			$subtypeid = $v['subtypeid'];
    			$ids = static::find()
    			->select(['tid'])
    			->where(['is_del'=>0])
    			->andWhere(['type'=>1])
    			->andWhere(['f_catalog_id'=>$mlevel])
    			->andWhere(['s_catalog_id'=>$subtypeid])
    			->orderBy('ctime DESC')
    			->limit($sublimit)
    			->all();
    			$allsubdata[$k]['ids'] = $ids;
    		}
    		//按照比例把id存到缓存
    		$iscontinue = true;
    		$cacheNum = 0;
    		//所有ids数组都为空或者已经缓存够数据都会退出
    		while($iscontinue){
    			//如果所有ids数组都为空则停止循环
    			$iscontinue = false;
    			foreach ($allsubdata as $k=>$v){
    				//按比例存
    				if(count($v['ids'])>0){
    					for($i=0;$i<$v['displaycount'];$i++){
    						if(count($v['ids'])>0){
    							//写缓存
    							$ret = $redis->rpush($redis_key, $v['ids'][0]['tid'],true);
    							array_splice($v['ids'],0,1);
    							$cacheNum += 1;
    						}
    					}
    					if(count($v['ids'])>0){
    						//如果不是所有子类型的数据都为空则可以继续循环
    						$iscontinue = true;
    					}
    					$allsubdata[$k]=$v;
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
    
    /**
     * 得到缓存评论   
     * @return [type] [description]
     */
   static function getCmtRedis($subjecttype,$subjectid,$limit){
        $redis=Yii::$app->cache;
        $redis_key="comment_thread_".$subjecttype."_".$subjectid;
        //$redis->delete($redis_key);
        $comment_json=$redis->get($redis_key);
        if(empty($comment_json)){
            $comment_arr=CommentService::getListBySubject($subjecttype,$subjectid, $limit);
            $comment_arr=CommentService::getCmtInfo($comment_arr,$subjectid);
            $redis->set($redis_key,json_encode($comment_arr));
            //缓存1天
            $redis->expire($redis_key, 3600*24*1);
            return $comment_arr;
        }
        return json_decode($comment_json);
   }

    
    /**
     * 获取素材总数，缓存1小时
     * @return unknown
     */
    static function getMaterialNum(){
    	//缓存
    	$redis = Yii::$app->cache;
    	$redis_key = 'tweet_material_num'; //素材总数
    	    	
    	//检查缓存是否存在，如果不存在才重建
    	$ret = $redis->getValue($redis_key);
    	if (!$ret) {
			//从数据库中获取
			$ret = $query = static::find()
    			->where(['is_del'=>0])
    			->andWhere(['type'=>1])
    			->count('tid');
			//缓存1小时
			$redis->setValue($redis_key,$ret,3600);
		}
    	return $ret;
    }
    /**
     * 通过用户id数组获取帖子id数组 
     * @param  [type] $uids  [description]
     * @param  [type] $utime [description]
     * @param  [type] $limit [description]
     * @return [type]        [description]
     */
    static function getTidArrByUidArr($uids,$utime,$limit){
        $query=new \yii\db\Query();
         $query->select('tid')
        ->from(parent::tableName())
        ->where(['in','uid',$uids]);
        if(!empty($utime)){
            $query->andWhere(['<','utime',$utime]);
        }        
        return $query->andWhere(['is_del'=>0])
        ->limit($limit)
        ->all();
    }
    /**
     * 根据用户获取发帖记录
     * @param  [type] $uid    [description]
     * @param  [type] $lastid [description]
     * @param  [type] $limit  [description]
     * @return [type]         [description]
     */
    static function getTidByUid($uid,$last_tid,$limit,$type,$teacherid=''){
        //判断是否是下拉
        if($last_tid==0){
            $mark=">";
        }else{
            $mark="<"; 
        }
        //是否只显示批改
        if($type==0){
            $typewhere=[">=","type",0];
        }else if($type==1){
            $typewhere=[">=","type",3];
        }else if($type==2){
            $typewhere=["<","type",3];
        }else if($type==4){ 
           //获取用户被老师已批改过的作品
            $typewhere=["type"=>4];
        }
        $query=new \yii\db\Query();
        $query=$query->select("tid")
        ->from(parent::tableName())
        ->where(['uid'=>$uid])
        ->andWhere(['is_del'=>0])
        ->andWhere([$mark,'tid',$last_tid])
        ->andWhere($typewhere);
        //批改
        if ($type == 4 || $type == 1) {
            $where = '';
            if ($type == 4) { //我批改的
                $where = " and teacheruid=$teacherid ";
            }
            $query = $query->andWhere("tid in (select tid from myb_correct where correct_fee=0 and submituid=$uid $where )");
        }
        //批改和作品
       	if($type==0){
       			$query=$query->andWhere("tid not in (select  tid from myb_correct where correct_fee>0 and submituid=$uid )");
       	}
        return $query->orderBy('tid desc')
		        ->limit($limit)
		       ->all();
    }
    /**
     * 得到随机取素材里面的图片
     * @return [type] [description]
     */
    static function getExampleRandByMaterial($limit=1000){
        $redis = Yii::$app->cache;
        $redis_key = 'material_list_rand'; //随机的获取素材图片
        $res=$redis->get($redis_key);
        //$redis->delete($redis_key);
        if(empty($res)){
            //读库获取
            $query=new \yii\db\Query();
            $resources_array=$query->select("resource_id")
            ->from(parent::tableName())
            ->where(['type'=>1])
            ->orderBy('rand()')
            ->where("tid not in (select tid from ci_tweet where resource_id in (select rid from ci_resource where img=''))")
            ->limit($limit)
            ->all();
            $resource_string='';
            foreach ($resources_array as $key => $value) {
                $resource_string .= $value['resource_id'].',';
            }
            $resource_string=substr($resource_string, 0,strlen($resource_string)-1);
            $redis->set($redis_key,$resource_string);
            //3小时有效期
            $redis->expire($redis_key, 3600*3);
            return explode(",", $resource_string);
        }
       return explode(",", $res);
    }
    
    /**
     * 获取能力模型推荐的普通素材
     * @param unknown $fcatalogid
     * @param unknown $scatalogid
     * @return multitype:
     */
    static function getRecommendMaterialIds($fcatalogid,$scatalogid,$limit){
    	$redis = Yii::$app->cache;
    	$redis_key = 'Capacity_Material_'.$fcatalogid.'_'.$scatalogid; 
    	$res=$redis->get($redis_key);
    	if(empty($res)){
    		//读库获取
    		$query=new \yii\db\Query();
    		$query->select("tid")
    		->from(parent::tableName())
    		->where(['type'=>1])
    		->andWhere(['is_del'=>0]);
    		if($fcatalogid>0){
    			$query->andWhere(['f_catalog_id'=>$fcatalogid]);
    		}	
    		if($scatalogid>0){
    			$query->andWhere(['s_catalog_id'=>$scatalogid]);
    		}

    		$tids=$query->orderBy('rand()')
    		->limit($limit)
    		->all();
    		$tid_string='';
    		foreach ($tids as $k => $v) {
    			$tid_string .= $v['tid'].',';
    		}
    		$tid_string = trim($tid_string,',');
    		$redis->set($redis_key,$tid_string,3600*24);
    		return explode(",", $tid_string);
    	}
    	return explode(",", $res);    	
    }
    
    /**
     * 获取批改事例图片id（默认三张）
     * @return [type] [description]
     */
    static function getCorrectExampleImgRand($imgcount=3){
        $result=self::getExampleRandByMaterial();
        $keys=array_rand($result,$imgcount);
        foreach ($keys as $key => $value) {
            $data[]=$result[$key];
        }
        return implode(",",$data);
    }
    /**
     * 列表中获取帖子详情
     * @return [type] [description]
     */
    static function getTweetListDetailInfo($tid,$uid,$withRelation=false){
        $model = self::fillExtInfo($tid, $uid,$withRelation); 
        if(!$model){
        	return null;
        }   
        $model['comment_list']=self::getCmtRedis(0,$model['tid'],2);        
        //判断加精 推荐等状态
        $model = self::fillFlag($model);
        //添加图片列表
        $model['imgs_list'] = $model['imgs'];
        //多图时显示第一图
        if($model['picnum']>0){
            $model['imgs'] = $model['imgs'][0]; 
        }
        //跳转跟着画  0为空 不显示
        if(empty($model['lessonid'])){
            $model['lessonid']=0;
        }
       return $model;
    }
    
    /**
     * 更新点击量
     * @param unknown $tid
     */
    static function addHits($tid){
    	$connection = \Yii::$app->db;
    	
    	$query="UPDATE `ci_tweet` SET `hits` = `hits`+1 WHERE `tid` =  ".$tid;
    	$command = $connection->createCommand($query);
    	$command->execute();
    }
    
    /**
     * 获取用户当天求批改数量
     * @param unknown $uid
     */
    static function getCorrectCountToday($uid){
    	//当天时间戳
    	$currentdate = strtotime(date("Y-m-d"));
    	$models = (new \yii\db\Query())
    	->select(['tid'])
    	->from(parent::tableName())
    	->where(['uid'=>$uid])
    	->andWhere(['>=','type',3])
    	->andWhere(['is_del'=>0])
    	->andWhere(['>','ctime',$currentdate])
    	->all();
    	if($models){
    		return count($models);
    	}
    	return 0;
    }
    
    /**
     * 用户是否连续求批改
     * @param unknown $uid
     * @param unknown $days 要检查的天数
     */
    static function isContinueCorrect($uid,$days){
    	//当天时间戳
    	$currentdate = strtotime(date("Y-m-d"));
    	//检查起始日期时间戳
    	$sdate = $currentdate-$days*24*3600;
    	$models = (new \yii\db\Query())
    	->select(["FROM_UNIXTIME( ctime, '%Y%m%d' ) as c"])
    	->distinct(true)
    	->from(parent::tableName())
    	->where(['uid'=>$uid])
    	->andWhere(['is_del'=>0])
    	->andWhere(['>=','ctime',$sdate])
    	->all();
    	if($models && count($models)==$days){
    		return true;
    	}
    	else{
    		return false;
    	}    	
    }

     /**
     * 获取详情页推荐id列表
     * @param unknown $tid
     * @param unknown $f_catalog_id
     * @param unknown $s_catalog_id
     * @param unknown $limit
     */
    static function getRecommendIdsByTid($tid,$f_catalog_id,$s_catalog_id,$limit){
    	$ids = static::find()->select(['tid'])
    	->where(['<','tid',$tid])
    	->andWhere(['type'=>1])
    	->andWhere(['is_del'=>0])
    	->andWhere(['f_catalog_id'=>$f_catalog_id])
    	->andWhere(['s_catalog_id'=>$s_catalog_id])
    	->andWhere(['<>','tid',$tid])
    	->orderBy('tid desc')
    	->limit($limit)
    	->all();
    	if($ids){
    		$ret = null;
    		foreach ($ids as $id){
    			$ret[]=$id['tid'];
    		}
    		return $ret;
    	}
    	return null;
    }
     /**
     * 获取出版社推荐素材
     * @param unknown $tid
     * @param unknown $f_catalog_id
     * @param unknown $s_catalog_id
     * @param unknown $limit
     */
    static function getPublishingRecommendIdsByUid($uid,$f_catalog_id,$s_catalog_id,$limit){
    	$ids = static::find()->select(['tid'])
    	->where(['uid'=>$uid])
    	->andWhere(['is_del'=>0])
    	->andWhere(['f_catalog_id'=>$f_catalog_id])
    	->andWhere(['s_catalog_id'=>$s_catalog_id])
    	->orderBy('tid desc')
    	->limit($limit)
    	->all();
    	if($ids){
    		$ret = null;
    		foreach ($ids as $id){
    			$ret[]=$id['tid'];
    		}
    		return $ret;
    	}
    	return null;
    }
}
