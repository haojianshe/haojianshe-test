<?php

namespace api\service;

use Yii;
use common\models\myb\Favorite;
use api\service\UserDetailService;

use api\service\TweetService;
use api\service\MaterialSubjectService;
use api\service\NewsService;
use api\service\ActivityQaService;
use api\service\ActivityArticleService;
use api\service\CapacityModelMaterialService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;
use api\service\CourseService;
use api\service\LectureService;
use common\service\CommonFuncService;
use api\service\LessonService;
/**
 * 
 * @author Administrator
 * 帖子赞
 *
 */
class FavoriteService extends Favorite {

    /**
     * 获取一条关注
     * @param $uid int
     * @param $tid int
     *
     * @return tweet list
     */
    static function getFavoriteByUidTid($uid, $tid, $type = 0) {
        return Favorite::findOne(['uid' => $uid, "tid" => $tid, "type" => $type]);
    }

    /**
     * 获取是否关注帖子
     * @param  [type] $uid [description]
     * @param  [type] $tid [description]
     * @return [type]      [description]
     */
    static function getFavStatusByUidTid($uid, $tid, $type = 0) {
        $res_fav = self::getFavoriteByUidTid($uid, $tid, $type);
        if (false === $res_fav || empty($res_fav)) {
            $fav = 0;
        } else {
            $fav = 1;
        }
        return $fav;
    }

    /**
     * 获取用户的关注列表(帖子)
     * @param unknown $uid
     * @param unknown $lastfid
     * @param unknown $limit
     * @return unknown
     */
    static function getListByUid($uid, $lastfid, $limit) {
        $query = new \yii\db\Query();
        $query = $query->select('*')
                ->from(parent::tableName())
                ->where(['uid' => $uid, 'type' => 0]);
        //第一页时不需要fid条件
        if ($lastfid != 0) {
            $query = $query->andWhere(['<', 'fid', $lastfid]);
        }
        $ret = $query->orderBy('fid DESC')
                ->limit($limit)
                ->all();
        return $ret;
    }

    /**
     * 获取用户的关注列表（所有）
     * @param unknown $uid
     * @param unknown $lastfid
     * @param unknown $limit
     * @return unknown
     */
    static function getAllListByUid($uid, $lastfid, $limit) {
        $query = new \yii\db\Query();
        $query = $query->select('*')
                ->from(parent::tableName())
                ->where(['uid' => $uid]);
        //第一页时不需要fid条件
        if ($lastfid != 0) {
            $query = $query->andWhere(['<', 'fid', $lastfid]);
        }
        $ret = $query->orderBy('fid DESC')
                ->limit($limit)
                ->all();
        return $ret;
    }

    /**
     * 搜索收藏帖子
     * @param  [type]  $uid     [description]
     * @param  [type]  $content [description]
     * @param  integer $limit   [description]
     * @return [type]           [description]
     */
    static function searchFavTweet($uid, $content, $type, $lastfid, $limit = 20) {

        $query = new \yii\db\Query();
        $query->select('fav.*')
                ->from(parent::tableName() . ' fav')
                ->where(['fav.uid' => $uid])
                ->andWhere(['fav.type' => 0]);
        if ($lastfid) {
            $query->andWhere(["<", "fid", $lastfid]);
        }
        $query->innerJoin('ci_tweet ct', "ct.tid = fav.tid");
        if (!empty($content)) {
            $query->andWhere(["like", "ct.content", $content]);
        }
        //搜改画收藏
        if ($type == 3) {
            $query->andWhere(["in", "ct.type", [3, 4]]);
        } elseif ($type == 0) {
            //搜作品收藏
            $query->andWhere(["in", "ct.type", [1, 2]]);
        }
        $query->andWhere(["ct.is_del" => 0]);
        $ret = $query->limit($limit)
                ->orderBy("fid desc")
                ->all();

        return $ret;
    }

    /**
     * 搜索专题
     * @param  [type]  $uid     [description]
     * @param  [type]  $content [description]
     * @param  integer $limit   [description]
     * @return [type]           [description]
     */
    static function searchFavMaterial($uid, $content, $lastfid, $limit = 20) {
        $query = new \yii\db\Query();
        $query->select('fav.*')
                ->from(parent::tableName() . ' fav')
                ->where(['fav.uid' => $uid])
                ->andWhere(['fav.type' => 1]);
        if ($lastfid) {
            $query->andWhere(["<", "fid", $lastfid]);
        }
        $query->leftJoin('myb_material_subject mms', "mms.subjectid = fav.tid");
        if (!empty($content)) {
            $query->andWhere(["like", "mms.title", $content]);
        }
        $ret = $query->limit($limit)
                ->orderBy("fid desc")
                ->all();
        return $ret;
    }

    /**
     * 搜索文章
     * @param  [type]  $uid     [description]
     * @param  [type]  $content [description]
     * @param  integer $limit   [description]
     * @return [type]           [description]
     */
    static function searchFavLecture($uid, $content, $lastfid, $limit = 20) {
        $query = new \yii\db\Query();
        $query->select('fav.*')
                ->from(parent::tableName() . ' fav')
                ->where(['fav.uid' => $uid])
                ->andWhere(['fav.type' => 2]);
        if ($lastfid) {
            $query->andWhere(["<", "fid", $lastfid]);
        }
        $query->leftJoin('myb_news mn', "mn.newsid = fav.tid");
        if (!empty($content)) {
            $query->andWhere(["like", "mn.title", $content]);
        }
        $ret = $query->limit($limit)
                ->orderBy("fid desc")
                ->all();
        return $ret;
    }

    /**
     * 搜索直播
     * @param  [type]  $uid     [description]
     * @param  [type]  $content [description]
     * @param  integer $limit   [description]
     * @return [type]           [description]
     */
    static function searchFavLive($uid, $content, $lastfid, $limit = 20) {
        $query = new \yii\db\Query();
        $query->select('fav.*')
                ->from(parent::tableName() . ' fav')
                ->where(['fav.uid' => $uid])
                ->andWhere(['fav.type' => 6]);
        if ($lastfid) {
            $query->andWhere(["<", "fid", $lastfid]);
        }
        $query->leftJoin('myb_live mn', "mn.liveid = fav.tid");
        if (!empty($content)) {
            $query->andWhere(["like", "mn.live_title", $content]);
        }
        $ret = $query->limit($limit)
                ->orderBy("fid desc")
                ->all();
        return $ret;
    }

    /**
     * 搜索课程
     * @param  [type]  $uid     [description]
     * @param  [type]  $content [description]
     * @param  integer $limit   [description]
     * @return [type]           [description]
     */
    static function searchFavCourse($uid, $content, $lastfid, $limit = 20) {
        $query = new \yii\db\Query();
        $query->select('fav.*')
                ->from(parent::tableName() . ' fav')
                ->where(['fav.uid' => $uid])
                ->andWhere(['fav.type' => 7]);
        if ($lastfid) {
            $query->andWhere(["<", "fid", $lastfid]);
        }
        $query->leftJoin('myb_course mn', "mn.courseid = fav.tid");
        if (!empty($content)) {
            $query->andWhere(["like", "mn.title", $content]);
        }
        $ret = $query->limit($limit)
                ->orderBy("fid desc")
                ->all();
        return $ret;
    }

    /**
     * 搜索精讲专题
     * @param  [type]  $uid     [description]
     * @param  [type]  $content [description]
     * @param  integer $limit   [description]
     * @return [type]           [description]
     */
    static function searchFavSubject($uid, $content, $lastfid, $limit = 20) {
        $query = new \yii\db\Query();
        $query->select('fav.*')
                ->from(parent::tableName() . ' fav')
                ->where(['fav.uid' => $uid])
                ->andWhere(['fav.type' => 8]);
        if ($lastfid) {
            $query->andWhere(["<", "fid", $lastfid]);
        }
        $query->leftJoin('myb_material_subject mn', "mn.subjectid = fav.tid");
        if (!empty($content)) {
            $query->andWhere(["like", "mn.title", $content]);
        }
        $ret = $query->limit($limit)
                ->orderBy("fid desc")
                ->all();
        return $ret;
    }

    /**
     * 搜索跟着画
     * @param  [type]  $uid     [description]
     * @param  [type]  $content [description]
     * @param  integer $limit   [description]
     * @return [type]           [description]
     */
    static function searchFavLesson($uid, $content, $lastfid, $limit = 20) {
        $query = new \yii\db\Query();
        $query->select('fav.*')
                ->from(parent::tableName() . ' fav')
                ->where(['fav.uid' => $uid])
                ->andWhere(['fav.type' => 9]);
        if ($lastfid) {
            $query->andWhere(["<", "fid", $lastfid]);
        }
        $query->leftJoin('myb_material_subject mn', "mn.subjectid = fav.tid");
        if (!empty($content)) {
            $query->andWhere(["like", "mn.title", $content]);
        }
        $ret = $query->limit($limit)
                ->orderBy("fid desc")
                ->all();
        return $ret;
    }

      /**
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
    public static function getFavInfoByContent($tid,$type){
        $fav_users=[];
        //获取关注用户uid
        $fav_uid_arr=self::getContentFavUsersList($tid,$type);

        //获取关注用户信息
        foreach ($fav_uid_arr as $key => $value) {
            $userinfo=UserDetailService::getByUid($value);
            if($userinfo){
                $fav_users[]=$userinfo;
            }
        }
        $ret['fav_users']=$fav_users;
        //获取关注用户总数
        $ret['fav_count']=self::getFavCountByContent($tid,$type);
        return $ret;
    }
   


    /**
     * 获取收藏用户数量
     */
    public static function getFavCountByContent($tid,$type){
        $rediskey="content_fav_count_".$tid.'_'.$type;
        $redis = Yii::$app->cache;
        // $redis->delete($rediskey);
        $count=$redis->get($rediskey);
        if (empty($count)) {
           $count=self::find()->where(['tid'=>$tid])->andWhere(['type'=>$type])->count();
           if($count){
                $redis->set($rediskey,$count);
                $redis->expire($rediskey,3600*1);
           }
        }
        return $count;
    }
    /**
     * 获取内容最新的收藏用户
     * @return [type]
     */
    public static function getContentFavUsers($tid,$type,$limit='5'){
        $users=self::find()->select("uid")->where(['tid'=>$tid])->andWhere(['type'=>$type])->limit($limit)->orderBy("fid desc")->asArray()->all();
        return $users;
    }
     /**
     * 缓存获取内容最新的收藏用户
     * @return [type]
     */
    public static function getContentFavUsersList($tid,$type){
        $redis = Yii::$app->cache;
        $rediskey="content_fav_users_new_".$tid.'_'.$type;
       // $redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::getContentFavUsers($tid,$type);
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value['uid'].',';
                $ret = $redis->rpush($rediskey, $value['uid'],true);
            }
            $redis->expire($rediskey,3600*1);
            $ids=substr($ids, 0,strlen($ids)-1);
            $list_arr=explode(',', $ids);
        }
        return $list_arr;
    }

    /**
     * 获取用户收藏内容详情 
     * @param  [type] "img" 返回图片数组集合 'all' 返回所有信息
     * @return [type]
     */
    public static function getFavContentInfo($favinfo_arr,$uid=-1,$type="img"){
        $ret = [];
        //返回收藏列表   0/1/2/3/4 帖子/专题/普通文章/活动文章/活动问答
        foreach ($favinfo_arr as $model) {
            $data_t = $model;
            $data_t['tweet_info'] = (object) array();
            $data_t['material_info'] = (object) array();
            $data_t['lecture_info'] = (object) array();
            $data_t['activity_article_info'] = (object) array();
            $data_t['activity_qa_info'] = (object) array();
            $data_t['capacity_material_info'] = (object) array();
            $data_t['live_info'] = (object) array();
            $data_t['course_info'] = (object) array();
            $data_t['lecture_subject_info'] = (object) array();
            $data_t['lesson_subject_info'] = (object) array();
            $data_img=[];
            //区分type 取不同信息
            if ($model['type'] == 0) {
                //帖子
                $tid = $model['tid'];
                $tweet = TweetService::fillExtInfo($tid, $uid, true);
                if (false === $tweet || empty($tweet) || $tweet['is_del'] == 1) {
                    continue;
                }
                //tudo type<3 取平论  批改不带评论
                if ($tweet['type'] < 3) {
                    $tweet['comment_list'] = TweetService::getCmtRedis(0, $tweet['tid'], 2);
                } else {
                    $tweet['comment_list'] = array();
                }
                //判断加精 推荐等状态
                $tweet = TweetService::fillFlag($tweet);
                //添加图片列表
                $tweet['imgs_list'] = $tweet['imgs'];
                //多图时显示第一图
                if ($tweet['picnum'] > 0) {
                    $tweet['imgs'] = $tweet['imgs'][0];
                }
                //跳转跟着画  0为空 不显示
                if (empty($tweet['lessonid'])) {
                    $tweet['lessonid'] = 0;
                }
                $tweet['fid'] = $model['fid'];
                $data_t['tweet_info'] = $tweet;
                $data_t['material_info'] = (object) array();
                $data_t['lecture_info'] = (object) array();
                $data_img = $data_t['tweet_info']['imgs']['l']['url'];
            } else if ($model['type'] == 1) {
                //专题
                $tmp = MaterialSubjectService::getMaterialDetail($model['tid']);
                if ($tmp) {
                    $tmp["picurl"] = json_decode($tmp["picurl"]);
                    $tmp["picurl"]->l = CommonFuncService::getPicByType((array) $tmp["picurl"]->n, "l");
                    $data_t['tweet_info'] = (object) array();
                    $data_t['material_info'] = $tmp;
                    $data_t['lecture_info'] = (object) array();
                    $data_img = $data_t['material_info']['picurl']->l['url'];
                }
            } else if ($model['type'] == 2) {
                //普通文章
                $tmp = LectureService::getLectureInfo($model['tid']);
                if ($tmp) {
                    $data_t['tweet_info'] = (object) array();
                    $data_t['material_info'] = (object) array();
                    $data_t['lecture_info'] = $tmp;
                    $data_img = $data_t['lecture_info']['img'][0]['url'];
                }
            } else if ($model['type'] == 3) {
                //活动文章
                $data_t['activity_article_info'] = ActivityArticleService::getArticleDetail($model['tid'], $uid);
                $data_img = $data_t['activity_article_info']['imgs'][0]['img'];
            } else if ($model['type'] == 4) {
                //活动问答
                $data_t['activity_qa_info'] = ActivityQaService::getQaDetail($model['tid'], $uid);
                $data_img = $data_t['activity_qa_info']['imgs'][0]['img'];
            } else if ($model['type'] == 5) {
                //活动问答
                $data_t['capacity_material_info'] = CapacityModelMaterialService::getMatreialDetail($model['tid'], $uid);
                $data_img =  $data_t['capacity_material_info']['imgs']['l']['url'];
            } else if ($model['type'] == 6) {
                //直播
                $data_t['live_info'] = LiveService::getDetail($model['tid'], $uid);
                $data_img =  $data_t['live_info']['recording_thumb_url'];
             
            } else if ($model['type'] == 7) {
                //课程
                $data_t['course_info'] = CourseService::getDetail($model['tid'], $uid);
                $data_img =  $data_t['course_info']['thumb_url'];
            } else if ($model['type'] == 8) {
                //精讲文章专题
                $data_t['lecture_subject_info'] = LectureService::getLectureInfo($model['tid'], $uid);
                $data_img =  $data_t['lecture_subject_info']['img'][0]['url'];
            } else if ($model['type'] == 9) {
                //跟着画
                $data_t['lesson_info'] = LessonService::getLessonWithFirstPic($model['tid'], $uid);
                $data_img =  $data_t['lesson_info']['imgs']['l']['url'];
            }
            if($type=='img'){
                $ret[]=$data_img;
            }else{
                $ret[]=$data_t;
            }
        }
        return $ret;
    }
    /**
     * 获取画夹内容列表
     * @param  [type]  $folderid [description]
     * @param  integer $limit    [description]
     * @param  [type]  $lastid   [description]
     * @return [type]            [description]
     */
    public static function getFavFolderContentList($folderid,$uid,$limit=10,$lastid=NULL){
        $query=self::find()->where(['folderid'=>$folderid]);
        if($lastid){
            $query->andWhere(['<',"fid",$lastid]);
        }
        //获取收藏列表
        $favs = $query->limit($limit)->orderBy("fid desc")->asArray()->all();
        //处理收藏列表返回每个收藏的详情
        $ret=[];
        if($favs){
            $ret=self::getFavContentInfo($favs,$uid,"all");
        }
        return $ret;
    }
    /**
     * 通过画夹id获取画夹收藏内容
     * @return [type] [description]
     */
    public static function getFavByfolderid($folderid,$limit=NULL){
        $query=FavoriteService::find()->where(['folderid'=>$folderid])->orderBy("fid desc")->asArray();
        if($limit){
             $query->limit(7);
        }
        $fav=$query->all();
        return $fav;
    }
    /**
     * 获取用户收藏数量
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getFavCount($uid){
        $count=self::find()->where(['uid'=>$uid])->count();
        return $count ;
    }
}
