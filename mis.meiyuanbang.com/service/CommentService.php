<?php
namespace mis\service;

use Yii;
use mis\models\Comment;
use yii\data\Pagination;

use mis\service\LectureService;
use mis\service\ActivityService;
use mis\service\LessonService;
use mis\service\LiveService;
use mis\service\CourseService;

class CommentService extends Comment 
{

    /**
     * 分页获取所有后台评论信息
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照
     */
     public static function getCommentByPage($where){
            //获取总数 分页
           $sql="select count(*) as count from eci_comment as cc inner join ci_user_detail as cd on cd.uid=cc.uid left join ci_tweet as ct on ct.tid=cc.subjectid where  cc.is_del=0 $where"; //cc.subjecttype=0 and
            $connection = Yii::$app->db; //连接
            $command_count = $connection->createCommand($sql);
            $count_1 = $command_count->queryAll();
            $pages = new Pagination(['totalCount' =>$count_1[0]['count'],'pageSize'=>100]);
            //获取数据
            $page_ruls=" limit ".$pages->limit." offset ".$pages->offset;
            //查找
            $sql="select ct.*,cr.*,cd.*,cc.*,ce.sname as rsname,ce.avatar as ravatar,cc.content as content,cc.ctime as ctime,cc.uid as uid from eci_comment as cc inner join ci_user_detail as cd on cd.uid=cc.uid left join ci_user_detail ce  on ce.uid=cc.reply_uid left join ci_tweet as ct on ct.tid=cc.subjectid  left join ci_resource cr on cc.content=cr.rid where  cc.is_del=0 $where order by cc.ctime desc $page_ruls";//cc.subjecttype=0 and
            $command = $connection->createCommand($sql);
            $models = $command->queryAll();
            return ['models' => $models,'pages' => $pages];
    }
    /**
     * 获取单个评论信息
     * @param  [type] $cid [description]
     * @return [type]      [description]
     */
    public static function findCommentInfo($cid)
    {
        return static::findOne(['cid' => $cid]);
    }
    /**
     * 统计帖子数
     * @param  [type] $where      [description]
     * @param  [type] $where_time [description]
     * @param  [type] $fontcount  [description]
     * @return [type]             [description]
     */
    public static function getStatCommentByPage($where,$where_time,$fontcount){
            //获取总数 分页
            $sql="select count(*) as count from ci_user_detail cut inner join ci_user cu on cu.id=cut.uid where cu.register_status=0  $where";
            $connection = Yii::$app->db; //连接
            $command_count = $connection->createCommand($sql);
            $count_1 = $command_count->queryAll();
            $pages = new Pagination(['totalCount' =>$count_1[0]['count'],'pageSize'=>20]);
            //获取数据
            $page_ruls=" limit ".$pages->limit." offset ".$pages->offset;
            //查找
            $sql=" select uid,avatar,sname,(select count(*) from eci_comment as cc where cc.uid=cut.uid and subjecttype=0 and is_del=0 $where_time) as total_count,(select count(*) from eci_comment as cc where cc.uid=cut.uid and char_length(cc.content)>$fontcount and subjecttype=0 and is_del=0 $where_time) as limit_count from ci_user_detail cut inner join ci_user cu on cu.id=cut.uid where cu.register_status=0  $where order by total_count desc $page_ruls";
            $command = $connection->createCommand($sql);
            $models = $command->queryAll();
            return ['models' => $models,'pages' => $pages];
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
            //增加帖子评论数
           /* if($this->is_del==1){ 
                if($redis->hincrby('tweet_'.$this->subjectid, 'comment_num', -1)<0){
                    $redis->hset('tweet_'.$this->subjectid, array('comment_num' => 0));
                }
            }else{
                 $redis->hincrby('tweet_'.$this->subjectid, 'comment_num', 1); 
            }*/
            $redis->delete($redis_key);
        }       
        return $ret;
    }


    /**
     * 得到批改老师发评论数量（帖子及批改下）按时间
     * @param  [type] $uid   [description]
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    public static function getCorrectCmtCount($uid,$starttime,$endtime){
        $connection = Yii::$app->db; //连接      
        //查找
        $sql="select count(*) as count from ".parent::tableName()." where subjecttype=0 and  is_del=0 and uid=$uid  and ctime between $starttime and  $endtime";
        $command = $connection->createCommand($sql);
        $models = $command->queryAll();
        return  $models[0]['count'];
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
     * 清除评论缓存
     * @param  [type] $subjecttype [description]
     * @param  [type] $subjectid   [description]
     * @return [type]              [description]
     */
    public static function updateCmtCountRedis($subjecttype,$subjectid){
            $redis = Yii::$app->cache;        
            switch ($subjecttype) {
                      //0帖子 1专家动态评论 2小组讨论 3精讲 4考点 5活动
                case 0:
                  $num = $redis->hincrby('tweet_'.$subjectid, 'comment_num', -1);
                  if($num < 0) {
                  $redis->hset('tweet_'.$subjectid, array('comment_num' => 0)); 
                  }
                  break;
                case 1:
                  $num = $redis->hincrby('newsid_data_'.$subjectid, 'comment_count', -1);
                  if($num < 0) {
                     $redis->hset('newsid_data_'.$subjectid, array('comment_count' => 0)); 
                  }
                  break;
                case 2:
                  break;
                case 3:
                  LectureService::udpate_cmtcount($subjectid);
                  break;
                case 4:
                  LessonService::udpate_cmtcount($subjectid);
                  break;
                case 5:
                  ActivityService::udpate_cmtcount($subjectid);
                  break;  
                case 10:
                  LiveService::udpate_cmtcount($subjectid);
                  break; 
                case 11:
                  CourseService::udpate_cmtcount($subjectid);
                  break;                   
                default:
                  break;
           }

    }
}
