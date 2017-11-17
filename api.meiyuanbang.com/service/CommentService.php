<?php
namespace api\service;

use Yii;
use common\models\myb\Comment;
use api\service\UserDetailService;
use api\service\ResourceService;
use api\service\TeamInfoService;
use api\service\TweetService;
use common\service\CommonFuncService;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
/**
 * 
 * @author Administrator
 * subjecttype: 0帖子 1专家动态评论 2小组讨论 3 文章 4考点 5活动 6 正能文章
 * 去掉原 add  update 可用yii 原生方法方法
 */
class CommentService extends Comment
{
	/**
	 * 获取某个评论主体的评论数
	 * @param unknown $subjecttype 评论类型
	 * @param unknown $subjectid 被评论主体的id
	 */
	static function getCommentNum($subjecttype,$subjectid) {
		$ret = static::find()->where(['subjecttype'=>$subjecttype])
		->andWhere(['subjectid'=>$subjectid])
		->andWhere(['is_del'=>0])
		->count('cid'); //默认是*,可能会影响性能		
		return $ret;
	}


    /**
     * 分页获取后面的评论
     * @param unknown $cid 最后一条评论id
     * @param unknown $subjecttype 评论主题类型
     * @param unknown $subjectid 评论主题id
     * @param unknown $limit 页数
     * @return boolean|NULL
     */
    static function getListByCidSubject($cid, $subjecttype,$subjectid,$limit) {
        $query=new \yii\db\Query();
        $ret = $query->select('*')->from(parent::tableName())->where(['is_del'=>0])
        ->andWhere(['subjecttype'=>$subjecttype])
        ->andWhere(['subjectid'=>$subjectid])
        //edit by l[jq,为什么是]>?
        //edit by ljq,由cid排序改为按照时间排序
        ->andWhere('ctime <(select `ctime` from `'. parent::tableName() .'` WHERE `cid` ='. $cid .')')
        ->orderBy(['ctime'=>SORT_DESC])  //SORT_ASC
        ->limit($limit)
        ->all();        
        return  $ret;
    }


    /**
     * 获取最新的增量评论 
     * @param  [type] $cid         [description]
     * @param  [type] $subjecttype [description]
     * @param  [type] $subjectid   [description]
     * @param  [type] $limit       [description]
     * @return [type]              [description]
     */
    static function getListByCidSubjectInc($cid, $subjecttype,$subjectid,$limit) {
        $query=new \yii\db\Query();
        $ret = $query->select('*')->from(parent::tableName())->where(['is_del'=>0])
        ->andWhere(['subjecttype'=>$subjecttype])
        ->andWhere(['subjectid'=>$subjectid])
        //edit by ljq,由cid排序改为按照时间排序
        ->andWhere('ctime >(select `ctime` from `'. parent::tableName() .'` WHERE `cid` ='. $cid .')')
        ->orderBy(['ctime'=>SORT_DESC])  //SORT_ASC
        ->limit($limit)
        ->all();        
        return  $ret;
    }

    /**
     * 根据评论类型和主题id获取评论列表,一般为获取第一页评论
     * @param unknown $subjecttype 评论类型
     * @param unknown $subjectid   评论主题id
     * @param unknown $limit   页数
     * @return boolean|NULL
     */
    static function getListBySubject($subjecttype,$subjectid, $limit) {
        $query=new \yii\db\Query();
        $ret = $query->select('*')->from(parent::tableName())->where(['is_del'=>0])
        ->andWhere(['subjecttype'=>$subjecttype])
        ->andWhere(['subjectid'=>$subjectid])
        //改为根据ctime排序
        ->orderBy(['ctime'=>SORT_DESC])  //SORT_ASC
        ->limit($limit)
        ->all();        
        return  $ret;
    }

    /**
     * 根据评论id获取评论详情
     * @param int cid 评论id
     * @return array 评论详情
     */
    static function getDetailByCid($cid) {
        $res=static::findOne(['cid'=>$cid]);
        if($res){
                return $res->attributes;
        }else{
            return array();
        }
    }
    /**
     * 获取评论详情
     * @param  [type] $content   [description]
     * @param  [type] $subjectid [description]
     * @return [type]            [description]
     */
    static function getCmtInfo($content,$subjectid){
    	$avartar = 'http://img.meiyuanbang.com/user/default/app_default.png';
        foreach($content as $idx => $comment) {
            //格式化时间
            $content[$idx]['ctime'] = $comment['ctime'];
            //获取当前评论用户数据
            $uid = $comment['uid'];
            if( $uid == "-1" || $uid == -1){
                $content[$idx]['sname'] = '游客';
                $content[$idx]['avatar'] = $avartar;
                $content[$idx]['ukind_verify'] = '0';
                $content[$idx]['ukind'] = '0';
                $content[$idx]['featureflag'] = '0';
                $content[$idx]['role_type'] = '1';
                
            }else{
                $ret = UserDetailService::getByUid($uid);
                if ($ret) {
                    $content[$idx]['ukind_verify'] = $ret['ukind_verify'];
                    $content[$idx]['ukind'] = $ret['ukind'];
                    $content[$idx]['sname'] = $ret['sname'];
                    if($ret['avatar']){
                    	$content[$idx]['avatar'] = $ret['avatar'];
                    }
                    else {
                    	$content[$idx]['avatar'] = $avartar;
                    }      
                    $content[$idx]['role_type'] = $ret['role_type'];              
                    $content[$idx]['featureflag'] = $ret['featureflag'];
                } else {
                    $content[$idx]['ukind_verify'] = '0';
                    $content[$idx]['ukind'] = '0';
                    $content[$idx]['sname'] = '';
                    $content[$idx]['avatar'] = '';
                    $content[$idx]['featureflag'] = '0';
                    $content[$idx]['role_type'] = '1';
                } 
            }           
            //获取原评论信息和用户数据(回复评论)
            $reply_cid = $comment['reply_cid'];
            $reply_uid = $comment['reply_uid'];
            if($reply_cid && $reply_uid) {
                if( $reply_uid==-1){
                    $content[$idx]['reply_sname'] = '游客';
                }else{
                    $ret_reply = UserDetailService::getByUid($reply_uid);
                    if ($ret_reply) {
                        $content[$idx]['reply_sname'] = $ret_reply['sname'];
                    }else {
                        $content[$idx]['reply_sname'] = '';
                    }   
                }               
            }
            //处理图片,声音
            if($comment['ctype']==1){
               $content[$idx]['resource']=json_decode($comment['content']);
               $imgarr=array();
               $imgarr=(array)json_decode($comment['content'])->n;
               $content[$idx]['resource']->t=CommonFuncService::getPicByType($imgarr,'t');
            }elseif($comment['ctype']==0){
                $content[$idx]['resource'] =(object)null;
            }elseif($comment['ctype']==2){
                $content[$idx]['voice']=json_decode($comment['content']);
            }elseif($comment['ctype']==3){
                //帖子类型
                $content[$idx]['tweet']=TweetService::fillExtInfo($comment['content'],$uid);          
            }elseif($comment['ctype']==4){
                //课程类型
                $content[$idx]['course']=CourseService::getDetail($comment['content'],$uid);
            }
            //添加小组讨论成员是否是管理员 is_admin 0/1/2 普通用户/管理员/群主
            if($comment['subjecttype']==2){
                $is_admin=TeamInfoService::commentUserIsAdmin($subjectid,$uid);
                $content[$idx]['is_admin'] = $is_admin;
            }
        }
        return $content;
    }
    /**
     * 评论增加评论数缓存
     * @param  [type] $type [description]
     * @param  [type] $id   [description]
     * @return [type]       [description]
     */
    static function incCmtCountRedis($type,$id){
        $redis=Yii::$app->cache;
        switch ($type) {
            case 0:
                //帖子
                $redis->hincrby('tweet_'.$id, 'comment_num', 1); 
                break;
            case 1:    
                //动态
                $redis->hincrby('newsid_data_'.$id, 'comment_count', 1);
                    break;
            case 2:
                //小组
                 break;
            case 3:
                //精讲                    
                $query="UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`+1 WHERE `newsid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                $redis->hincrby('lecture_detail_new_'.$id, 'cmtcount', 1);
                break;
            case 4:
                //4考点
                $query="UPDATE `myb_lesson` SET `cmtcount` = `cmtcount`+1 WHERE `lessonid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                $redis->hincrby('lesson_detail_'.$id, 'cmtcount', 1);
                break;
            case 5:
                //5活动
                $query="UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`+1 WHERE `newsid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                $redis->hincrby('activity_detail_'.$id, 'cmtcount', 1);
                break;  
            case 7:
                //7活动文章
                $query="UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`+1 WHERE `newsid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                $redis->hincrby('activity_article_'.$id, 'cmtcount', 1);
                break; 
            case 8:
                //8 活动问答
                $query="UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`+1 WHERE `newsid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                $redis->hincrby('activity_qa_'.$id, 'cmtcount', 1);
                break; 
            case 10:
                //10、直播
                $query="UPDATE `myb_live` SET `cmtcount` = `cmtcount`+1 WHERE `liveid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                $redis->hincrby('live_detail_'.$id, 'cmtcount', 1);
                break; 
            case 11:
                //11、课程
                $query="UPDATE `myb_course` SET `cmtcount` = `cmtcount`+1 WHERE `courseid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                $redis->hincrby("course_detail_".$id, 'cmtcount', 1);
                break; 
            case 12:
                //12 专题
                $query="UPDATE `myb_material_subject` SET `cmtcount` = `cmtcount`+1 WHERE `subjectid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                $redis->hincrby("material_subject_detail".$id, 'cmtcount', 1);
                break; 
        }
    }
    /**
     * 删除评论减评论数缓存
     * @param  [type] $type [description]
     * @param  [type] $id   [description]
     * @return [type]       [description]
     */
    static function decCmtCountRedis($type,$id){
        $redis=Yii::$app->cache;
        switch ($type) {
            case 0:
                //帖子               
                if($redis->hincrby('tweet_'.$id, 'comment_num', -1)<0){
                    $redis->hset('tweet_'.$id, array('comment_num' => 0));
                }
                break;
            case 1:    
                //动态
                if($redis->hincrby('newsid_data_'.$id, 'comment_count', -1)<0){
                    $redis->hset('newsid_data_'.$id, array('comment_count' => 0));
                }
                break;
            case 2:
                //小组
                 break;
            case 3:
                //精讲                    
                $query="UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`-1 WHERE `newsid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                if($redis->hincrby('lecture_detail_new_'.$id, 'cmtcount', -1)<0){
                    $redis->hset('lecture_detail_new_'.$id, array('cmtcount' => 0));
                }
             
                break;
            case 4:
                //4考点
                $query="UPDATE `myb_lesson` SET `cmtcount` = `cmtcount`-1 WHERE `lessonid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存               
                if($redis->hincrby('lesson_detail_'.$id, 'cmtcount', -1)<0){
                    $redis->hset('lesson_detail_'.$id, array('cmtcount' => 0));
                }
                break;
            case 5:
                //5活动
                $query="UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`-1 WHERE `newsid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                if($redis->hincrby('activity_detail_'.$id, 'cmtcount', -1)<0){
                    $redis->hset('activity_detail_'.$id, array('cmtcount' => 0));
                }
                break;
            case 7:
                //活动文章
                $query="UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`-1 WHERE `newsid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                if($redis->hincrby('activity_article_'.$id, 'cmtcount', -1)<0){
                    $redis->hset('activity_article_'.$id, array('cmtcount' => 0));
                }
                break;
            case 8:
                //活动问答
                $query="UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`-1 WHERE `newsid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                if($redis->hincrby('activity_qa_'.$id, 'cmtcount', -1)<0){
                    $redis->hset('activity_qa_'.$id, array('cmtcount' => 0));
                }
                break;
            case 10:
                //10直播
                $query="UPDATE `myb_live` SET `cmtcount` = `cmtcount`-1 WHERE `liveid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                if($redis->hincrby('live_detail_'.$id, 'cmtcount', -1)<0){
                    $redis->hset('live_detail_'.$id, array('cmtcount' => 0));
                }
                break;  
            case 11:
                //11课程
                $query="UPDATE `myb_course` SET `cmtcount` = `cmtcount`-1 WHERE `courseid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                if($redis->hincrby('course_detail_'.$id, 'cmtcount', -1)<0){
                    $redis->hset('course_detail_'.$id, array('cmtcount' => 0));
                }
                break;
             case 12:
                //12 专题
                $query="UPDATE `myb_material_subject` SET `cmtcount` = `cmtcount`-1 WHERE `subjectid` =  ".$id;
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $data = $command->execute();
                //更新缓存
                if($redis->hincrby('material_subject_detail'.$id, 'cmtcount', -1)<0){
                    $redis->hset('material_subject_detail'.$id, array('cmtcount' => 0));
                }
                break;
        }
   }


    /**
     * 评论
     * 推送消息发送的缓存服务器地址为cachequeue对应的服务器
     * @param unknown $fromid 发批改请求的学生uid
     * @param unknown $touid  被请求的老师的uid
     * @param unknown $correctid 批改id
     */
    static function commentPushMsg($from_uid,$to_uid,$cid){
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;
        
        $params['action_type'] = SysMsgTypeEnum::COMMENT;
        $params['from_uid'] = $from_uid;
        $params['to_uid'] = $to_uid;
        $params['content_id'] = $cid;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $value = json_encode($params);
        
        $redis->lpush($rediskey,$value);
    }

    /**
     * 评论回复
     * 推送消息发送的缓存服务器地址为cachequeue对应的服务器
     * @param unknown $fromid 发批改请求的学生uid
     * @param unknown $touid  被请求的老师的uid
     * @param unknown $correctid 批改id
     */
    static function commentReplyPushMsg($from_uid,$to_uid,$cid){
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;
        
        $params['action_type'] = SysMsgTypeEnum::COMMENT_REPLY;
        $params['from_uid'] = $from_uid;
        $params['to_uid'] = $to_uid;
        $params['content_id'] = $cid;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $value = json_encode($params);
        
        $redis->lpush($rediskey,$value);
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
        //清除列表缓存
        if($this->subjecttype == 0){
            $redis_key="comment_thread_".$this->subjecttype."_".$this->subjectid;
            $redis->delete($redis_key);
        }       
        return $ret;
    }
    /**
     * 已回复的活动问答评论
     * @param  [type] $newsid [description]
     * @return [type]         [description]
     */
    public static function getReplyQaCmt($newsid,$sort="desc"){
        $return_data=[];
        $reply_cids=self::getHasReplyCids($newsid,$sort);
        foreach ($reply_cids as $key => $value) {
            //获取所有回复评论id
            $cid_arr[]=$value;
            $cid_arr=array_merge($cid_arr,self::getCidByReplyCid($value));
        }   
        if($cid_arr){
            $return_data=self::getFullCmtByCidArr($cid_arr,8);
        }
        return $return_data;
    }
    /**
     * 得到有回复的所有cid
     * @return [type] [description]
     */
    public static function getHasReplyCids($newsid,$sort="desc"){
        $cid_arr=[];
        $reply_cids=self::find()->distinct()->select("reply_cid")->where(["subjecttype"=>8])->andWhere(['subjectid'=>$newsid])->andWhere([">","reply_uid",0])->andWhere([">","reply_cid",0])->andWhere(['is_del'=>0])->orderBy("ctime $sort")->asArray()->all();
         foreach ($reply_cids as $key => $value) {
            //获取所有回复评论id
            $cid_arr[]=$value['reply_cid'];
        }
        return $cid_arr;
    }
    /**
     * 未回复的活动问答评论
     * @param  [type] $newsid   [description]
     * @param  [type] $last_cid [description]
     * @param  [type] $rn       [description]
     * @return [type]           [description]
     */
    public static function getNoReplyQaCmt($newsid,$last_cid,$rn){
        $return_data=[];
        //未回答评论
        $query=self::find()->select("cid")->where(["subjecttype"=>8])->andWhere(['subjectid'=>$newsid])->andWhere(["reply_uid"=>0])->andWhere(['is_del'=>0])->andWhere(['not in','cid',self::getHasReplyCids($newsid)]);
        if(!empty($last_cid)){
            //分页
            $query->andWhere(["<","cid",$last_cid]);
        }
        $cids=$query->limit($rn)->orderBy("ctime desc")->asArray()->all();  
        //未回答用户提问
        foreach ($cids as $key => $value) {
            $no_reply_arr[]=$value['cid'];
        }
        if($no_reply_arr){
            $return_data=self::getFullCmtByCidArr($no_reply_arr,8);
        }
        return $return_data;
    }
    /**
     * 获取问答评论列表
     * @param  [type] $newsid   [description]
     * @param  [type] $last_cid [description]
     * @param  [type] $rn       [description]
     * @return [type]           [description]
     */
    public static function getQaCmtList($newsid,$last_cid,$rn){
        $return_data['reply']=[];
        //未回复列表
        $return_data['noreply']=[];
        
        //已回答评论
        if(empty($last_cid)){
            $return_data['reply']=self::getReplyQaCmt($newsid);
            $return_data['noreply']=self::getNoReplyQaCmt($newsid,$last_cid,$rn);
        }else{
            $return_data['reply']=[];
            $return_data['noreply']=self::getNoReplyQaCmt($newsid,$last_cid,$rn);
        }
        //返回评论列表
        return  $return_data;           
            
    }
    /**
     * 处理评论
     * @param  [type] $cid_arr     [description]
     * @param  [type] $subjecttype [description]
     * @return [type]              [description]
     */
    public static function getFullCmtByCidArr($cid_arr,$subjecttype){
        //获取评论信息
        foreach ($cid_arr as $key => $value) {
           $return_arr[]=self::find()->select("*")->where(['cid'=>$value])->asArray()->one();
        }
        return self::getCmtInfo($return_arr,$subjecttype);
    }
    /**
     * 根据回复评论id 获取所有回答用户
     * @param  [type] $cid [description]
     * @return [type]      [description]
     */
    public static function getCidByReplyCid($cid){
        $return_cids=[];
        $cids=self::find()->select("cid")->where(['reply_cid'=>$cid])->andWhere(["subjecttype"=>8])->andWhere(['is_del'=>0])->orderBy("ctime desc")->asArray()->all();
        foreach ($cids as $key => $value) {
            $return_cids[]=$value['cid'];
        }
        return $return_cids;
    }
    /**
     * 根据评论id 得到回答列表
     * @param  [type] $cid [description]
     * @return [type]      [description]
     */
    public static function getQaReplyCmtByCid($cid){
        $return_data=[];
        $cids=self::getCidByReplyCid($cid);
        $cids=array_merge([$cid],$cids);
        if($cids){
            $return_data=self::getFullCmtByCidArr($cids,8);
        }
        return $return_data;
    }

    /**
     * 增加语音转换任务
     * @param [type] $cid [description]
     */
    public static function AddVoiceToMp3Task($cid){
        $redis = Yii::$app->cachequeue;
        $rediskey = 'cmtsoundtomp3';
        $params['tasktype'] = 8;
        $params['cid'] = $cid;
        $params['tasktctime'] = time();
        $value = json_encode($params);
        $redis->lpush($rediskey,$value); 

    }
}
