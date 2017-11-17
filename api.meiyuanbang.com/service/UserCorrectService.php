<?php
namespace api\service;
use common\models\myb\UserCorrect;
use common\models\myb\CorrectPayteacherArrange;
use Yii;
use common\redis\Cache;
use api\service\UserDetailService;
use  common\service\dict\CorrectIosProductIdService;
/**
* 
*/
class UserCorrectService extends UserCorrect
{
    /**
     * 获取批改老师列表
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
   public static function getUserCorrectCount(){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select count(*) as count  from '.parent::tableName());
        $data = $command->queryAll()[0]['count'];
        return $data;
   }

    /**
     * 批改老师列表
     * @param  [type] $uid    [description]
     * @param  [type] $lastid [description]
     * @param  [type] $rn     [description]
     * @return [type]         [description]
     */
    public static function getTeacherListRedis(){
        $redis = Yii::$app->cache;
        $rediskey = 'correct_teacher_listz';
        
        //从缓存获取批改老师列表       
        $teacher_uids_arr=$redis->zrangebyscore($rediskey,'-inf','+inf');
        if(empty($teacher_uids_arr)){
        	//没有缓存则重新建立缓存后在获取数据
            static::buildCache();
            $teacher_uids_arr=$redis->zrangebyscore($rediskey,'-inf','+inf');
        }
        //返回所有数据 分页放到外面
        return $teacher_uids_data=array_splice($teacher_uids_arr, 0,-1);
        /*//分页数据获取
        if(!$lastid){
            $idx=0;
            $teacher_uids_data=array_splice($teacher_uids_arr, 0,$rn);
        }else{
            $idx = array_search($lastid, $teacher_uids_arr);
            $teacher_uids_data=array_splice($teacher_uids_arr, $idx+1,$rn);
        }
        return $teacher_uids_data;*/
    }

    /**
     * 检查老师状态
     * 更新缓存
     */
    private static function buildCache(){
    	$redis = Yii::$app->cache;
    	$rediskey = 'correct_teacher_listz';
    	
    	if($redis->exists($rediskey)){
    		return;
    	}    	
    	//(1)检查繁忙状态的老师是否需要恢复
    	static::checkTeacherStatus();
    	//(2)建立缓存
    	//先取正常状态的老师
    	$ids = self::getTeacherListDb();
    	$i=0;
    	foreach ($ids as $key => $value) {
    		$score = 100000+$i;
    		$i++;
    		$redis->zadd($rediskey, $score, $value['uid']);
    	}
    	//取休息和繁忙状态的老师
    	$ids = self::getRestTeacherListDb();
    	if($ids){
    		$i=0;
	    	foreach ($ids as $key => $value) {
	    		if($value['status']==3){
	    			//繁忙中老师
	    			$score = 200000+$i;
	    		}
	    		else{
	    			//休息中老师
	    			$score = 300000+$i;
	    		}
	    		$redis->zadd($rediskey, $score, $value['uid']);
	    	}
    	}
    	//缓存30分钟
    	$redis->expire($rediskey, 1800);
    }
    
    /**
     * 老师变为繁忙后处理缓存
     * @param unknown $teacherUid
     * @return boolean
     */
    static function changeRestCache($teacherUid){
    	$redis = Yii::$app->cache;
    	$redis_key='correct_teacher_listz';
    	
    	//判断是否有缓存，如果缓存已失效则不做处理
    	if(!$redis->exists($redis_key)){
    		return;
    	}
    	//把老师缓存中的score为200000开头
    	$redis->zadd($redis_key, 200000, $teacherUid);  	
    }
    
    /**
     * 检查批改老师状态，把待批该数少于阀值的老师改为正常状态
     * @return Ambigous <multitype:, \yii\db\mixed>
     */
    private static function checkTeacherStatus(){
    	$connection = \Yii::$app->db;
    	//休息阀值
    	$restCount = 12;
    	//取出所有繁忙状态的老师
    	$query = new \yii\db\Query();
    	$models = $query->select(['uid'])
    	->from(parent::tableName())
    	->where(['status'=>3])
        //增加免费老师筛选 付费老师繁忙状态不用这个状态
        ->andWhere(['correct_fee'=>0])
    	->all();
    	if(!$models){
    		return;
    	}
    	//检查每个老师是否还处于繁忙状态
    	foreach ($models as $k=>$v){
    		$retainCount = CorrectService::getWaitCorrectCount($v['uid']);
    		if($retainCount<$restCount){
    			$model = static::findOne(['uid'=>$v['uid']]);
    			$model->status=0;
    			$model->save();
    		}
    	}
    }
    
    /**
     * 获取正常状态的批改老师列表
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
   public static function getTeacherListDb(){
        $connection = \Yii::$app->db;
        //v2.0.1版本改为每一小时随机排序
        $command = $connection->createCommand('select uid from '.parent::tableName().' where status=0 and correct_fee=0 order by rand()');
        $data = $command->queryAll();
        return $data;
   }
   
   /**
    * 获取休息的老师列表
    * @return Ambigous <multitype:, \yii\db\mixed>
    */
   public static function getRestTeacherListDb(){
   	$connection = \Yii::$app->db;
   	//v2.0.1版本改为每一小时随机排序
   	$command = $connection->createCommand('select uid,`status` from '.parent::tableName().' where status=2 or status=3  and correct_fee=0 order by rand()');
   	$data = $command->queryAll();
   	return $data;
   }

    public static function getTeacherListByType($type,$uid,$rn){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select uid from '.parent::tableName().' where '.$type.'=1 and uid>'.$uid.' and  status<>1  and correct_fee=0 order by uid desc limit '.$rn);
        $data = $command->queryAll();
        return $data;
   }
   /**
     * 获取单个批改老师表详情--包含批改总数，赞赏总数
     * @param  [type] $rid [description]
     * @return [type]      [description]
     */
    public static function getUserCorrectDetail($uid){
        $resource_detail_redis='usercorrect_detail_';
        $rediskey=$resource_detail_redis.$uid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $user_correct_detail=$redis->hgetall($rediskey);
        if (empty($user_correct_detail)) {
           $data_obj=UserCorrect::findOne(['uid'=>$uid]);
           if($data_obj){
                $data=$data_obj->attributes;
                $redis->hmset($rediskey,$data);
                $data['productid']=CorrectIosProductIdService::getIosProductidByPrice($data['correct_fee_ios']);
                return $data; 
           }else{
                return array();
           }
        }else{
            $user_correct_detail['productid']=CorrectIosProductIdService::getIosProductidByPrice($user_correct_detail['correct_fee_ios']);
            return $user_correct_detail;
        }
    }

    /**
     * 保存时操作缓存
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL){
        $usercorrect_detail_redis='usercorrect_detail_';  
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;         
        $ret = parent::save($runValidation,$attributeNames);
        $rediskey=$usercorrect_detail_redis.$this->uid;
        //处理缓存
       // $data=Correct::findOne(['correctid',$this->correctid])->attributes; 
        if($isnew==false){
            //新建节点需要清理掉对应的缓存
            $redis->delete($rediskey);
        }else{                    
           //$redis->delete($rediskey);                                 
        }
        return $ret;
    }
    
    /**
     * 随机获取一个推荐老师id
     */
    public static function getRecommendId(){
    	//从缓存获取id列表，暂定取top1000
    	$ids = self::getTeacherListRedis();
    	$randnum = rand(0, count($ids)-1);
    	return $ids[$randnum];
    }
    
    /**
     * 随机获取多个推荐老师id
     * @return \api\service\[type]
     */
    public static function getRecommendIds($maintype,$num){
    	//从缓存获取id列表，暂定取top1000
    	$ids = self::getTeacherListRedis();
    	//取的数量>老师
    	if(count($ids)<=$num){
    		return $ids;
    	}   
    	$ret = [];
        $i=0;
    	while (count($ret)<$num){
            if($maintype==2 && $i>500){
                break;
            }
    		$randnum = rand(0, count($ids)-1);
    		$teacherid = $ids[$randnum];
    		//老师未被推荐并且老师状态不是休息或繁忙
    		if(array_search($teacherid,$ret)===false){
    			$data = static::getUserCorrectDetail($teacherid);
                if(self::IsCatalogCorrectTeacher($maintype,$data)){
                    if($data['status']==0){
                        $ret[] = $teacherid;
                    }   
                }
    		}
            $i++;
    	}
    	return $ret;
    }

    public static function IsCatalogCorrectTeacher($maintype,$data_arr){
        $is_catalog_teacher=true;
        switch (intval($maintype)) {
            case 5:
                //是否素写批改老师
                $is_catalog_teacher=(boolean)($data_arr['issketch']==1);
                break;
            case 4:
                //是否素描批改老师
                $is_catalog_teacher=(boolean)($data_arr['isdrawing']==1);
                break;
            case 1:
                //是否色彩批改老师
                $is_catalog_teacher=(boolean)($data_arr['iscolor']==1);
                break;
            case 2:
                //是否设计批改老师
                $is_catalog_teacher=(boolean)($data_arr['isdesign']==1);
                break;
            default:
                break;
            
        }
        return $is_catalog_teacher;
    }
    /**
     * 获取当前付费批改老师
     * @return [type] [description]
     */
    public static function getPayTeacher(){
        $teachers=self::find()->select("uid")->where(['status'=>0])->andWhere(['>','correct_fee',0])->asArray()->all();
        $teacheruid_arr=[];
        if($teachers){
            foreach ($teachers as $key => $value) {
                $teacheruid_arr[]=$value['uid'];
            } 
        }
       
        return $teacheruid_arr;
    }
    /**
     * 获取当前时段的付费批改老师
     * @return [type] [description]
     */
    public static function getPayTeacherNow(){
        $payteacher=CorrectPayteacherArrange::find()->where(['<','btime',time()])->andWhere(['>','etime',time()])->one();
        if(!$payteacher){
            return [];
        }
        return explode(",", $payteacher->teacheruids);
    }
    /**
     * 得到繁忙的老师
     * @return [type] [description]
     */
     public  static function getBusyTeacherNow(){
        $teacheruid_arr = static::getPayTeacherNow();
        $busy_teacher=[];
        if($teacheruid_arr){
            //检查每个老师是否还处于繁忙状态
            foreach ($teacheruid_arr as $k=>$v){
                $retainCount = CorrectService::getWaitCorrectCount($v,2);

                if($retainCount>0){
                    $busy_teacher[]=$v;
                }
            }
        }
        return $busy_teacher;
    }
    /**
     * 获取付费批改老师信息
     * @param  [type] $teacheruids        [description]
     * @param  [type] $pay_teacher_status [description]
     * @return [type]                     [description]
     */
    
   


    public static  function getPayCorrectTeacherInfo($teacheruids,$pay_teacher_status=0,$type=0){
        $ret=[];
        foreach ($teacheruids as $key => $value) {
            $data_arr=UserCorrectService::getUserCorrectDetail($value);
            switch (intval($type)) {
                   //0/1/2/3 全部/素描/色彩/速写
                case 4:
                    if($data_arr['isdrawing']!=1){
                        $data_arr=[];
                    }
                    break;
                case 1:
                    if($data_arr['iscolor']!=1){
                        $data_arr=[];
                    }
                    break;
                case 5:
                    if($data_arr['issketch']!=1){
                        $data_arr=[];
                    }
                case 2:
                    if($data_arr['isdesign']!=1){
                        $data_arr=[];
                    }
                    break;
                default:
                    break;
            }
            if($data_arr){
                $data_arr['pay_teacher_status']=$pay_teacher_status;
                $ret[]=array_merge(UserDetailService::getByUid($value),$data_arr);
            }
        }
        return $ret;
    }
}