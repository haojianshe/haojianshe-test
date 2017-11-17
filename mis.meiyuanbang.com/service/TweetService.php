<?php

namespace mis\service;

use Yii;
use common\models\myb\Tweet;
use yii\data\Pagination;
use common\lib\myb\enumcommon\SysMsgTypeEnum;

/**
 * mis用户相关的业务逻辑层
 * 本方法实现了IdentityInterface，可以做为yii\web\user类的登录验证类使用
 * @author Administrator
 *
 */
class TweetService extends Tweet {

    /**
     * 分页获取所有后台批改信息
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照
     */
    public static function getTweetByPage($where, $subjecttype, $sname) {
        $query = parent::find();
        $countQuery = $query//->select('COUNT(*)') 
                ->from('ci_tweet');
        $countQuery = $query->innerJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')
                ->innerJoin('myb_correct', 'myb_correct.tid = ci_tweet.tid');
        if ($subjecttype == 3) {
            $countQuery = $query->innerJoin('ci_user', 'ci_user.id=ci_user_detail.uid')->andWhere(['ci_user.umobile' => $sname]);
        }
        $countQuery = $query->andWhere($where)->andWhere("(myb_correct.correct_fee=0 or (myb_correct.correct_fee>0 and myb_correct.pay_status=1))");
        $countQuery = $query->count();
        //分页对象计算分页数据           
        $pages = new Pagination(['totalCount' => $countQuery]);
        $query = new \yii\db\Query();
        //获取数据
        $models = $query
                ->select('ci_tweet.*,ci_user_detail.*,myb_correct.*')
                ->from('ci_tweet');
        $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->innerJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')
                ->innerJoin('myb_correct', 'myb_correct.tid = ci_tweet.tid')
                ->orderBy('ci_tweet.tid DESC');
        if ($subjecttype == 3) {
            $countQuery = $query->innerJoin('ci_user', 'ci_user.id=ci_user_detail.uid')->andWhere(['ci_user.umobile' => $sname]);
        }
        $countQuery = $query->andWhere($where)->andWhere("(myb_correct.correct_fee=0 or (myb_correct.correct_fee>0 and myb_correct.pay_status=1))");

        $models = $query
                ->all();
        #->createCommand()->getRawSql();
        #echo $models;
        #exit;
        #print_r($models);
        #exit;
        return ['models' => $models, 'pages' => $pages, 'pageSize' => 1];
    }

    /**
     * 分页获取所有后台帖子 素材信息
     */
    public static function getTweetMaterialByPage($where) {
        $query = parent::find();

        $countQuery = $query//->select('COUNT(*)') 
                ->from('ci_tweet')
                ->where(['ci_tweet.is_del' => 0])
                ->andWhere($where)
                ->innerJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')
                ->count();
        //分页对象计算分页数据           
        $pages = new Pagination(['totalCount' => $countQuery]);
        $query = new \yii\db\Query();
        //获取数据
        $models = $query
                ->select('ci_tweet.*,ci_user_detail.*')
                ->from('ci_tweet')
                ->where(['ci_tweet.is_del' => 0])
                ->andWhere($where)
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->innerJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')
                ->orderBy('ci_tweet.tid DESC')
                ->all();
        # ->createCommand()->getRawSql();
        $count = self::HitCount($where);
        return ['models' => $models, 'pages' => $pages, 'pageSize' => 1,'count'=>$count];
    }

    /**
     * 获取总点击量
     */
    public static function HitCount($where) {
        $query = new \yii\db\Query();
        $data = self::find()->select('sum(ci_tweet.hits) as hits')
                ->from('ci_tweet')
                ->where(['ci_tweet.is_del' => 0])
                ->andWhere($where)
                ->innerJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')
                ->orderBy('ci_tweet.tid DESC')
                ->asArray()->one();
        return $data['hits'];
    }

    /**
     * 分页获取所有后台帖子信息
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照
     */
    public static function getTweetByPageTeacher($where) {
        $query = parent::find();

        $countQuery = $query//->select('COUNT(*)') 
                ->from('ci_user_detail as cudOne')
                //->where(['ci_tweet.is_del' => 0])
                ->andWhere($where)
                ->innerJoin('myb_correct', 'cudOne.uid=myb_correct.teacheruid')
                ->innerJoin('ci_user_detail as cudTwo', 'myb_correct.submituid=cudTwo.uid')
                ->innerJoin('ci_tweet', 'myb_correct.tid=ci_tweet.tid')
                ->andWhere("(myb_correct.correct_fee=0 or (myb_correct.correct_fee>0 and myb_correct.pay_status=1))")
                ->count();
        //分页对象计算分页数据           
        $pages = new Pagination(['totalCount' => $countQuery]);
        $query = new \yii\db\Query();
        //获取数据
        $models = $query
                ->select('cudOne.sname as tearcherName,cudTwo.*,myb_correct.*,ci_tweet.*')
                ->from('ci_user_detail as cudOne')
                //->where(['ci_tweet.is_del' => 0])
                ->andWhere($where)
                ->andWhere("(myb_correct.correct_fee=0 or (myb_correct.correct_fee>0 and myb_correct.pay_status=1))")
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->innerJoin('myb_correct', 'cudOne.uid=myb_correct.teacheruid')
                ->innerJoin('ci_user_detail as cudTwo', 'myb_correct.submituid=cudTwo.uid')
                ->innerJoin('ci_tweet', 'myb_correct.tid=ci_tweet.tid')
                ->orderBy('ci_tweet.tid DESC')
                ->all();
        #->createCommand()->getRawSql();
        #echo $models;
        #exit;
        return ['models' => $models, 'pages' => $pages, 'pageSize' => 1];
    }

    public static function findTweetInfo($id) {
        return static::findOne(['tid' => $id]);
    }

    /**
     * 保存前处理缓存
     * 新增需要添加到list列表
     * 如果是删除时，则直接清列表，因为更新和删除频率非常低
     * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        $redis_key = 'tweet_list_plaza';
        if ($this->type == 3 or $this->type == 4) {
            //批改
            $redis_key = $redis_key . '_c';
        }
        if ($this->type == 1 or $this->type == 2) {
            $redis_key = $redis_key . '_t';
        }

        //处理缓存
        if ($isnew) {
            //增加到列表缓存里
            $redis->zadd($redis_key, $this->utime * -1, $this->tid);
        } else {
            //如果是删除则清掉列表缓存
            if ($this->is_del == 1) {
                $redis->delete($redis_key);
                //更新用户帖子数
                $redis_key = 'userext_' . $this->uid;
                $num = $redis->hincrby($redis_key, 'tweet_num', -1);
                if ($num < 0) {
                    $redis->hset($redis_key, array('tweet_num' => 0));
                }
            }
            //为了防止求批改变作品，每次都清空求批改缓存列表
            $redis->delete('tweet_list_plaza_c');
            $redis->delete('tweet_list_plaza_c_80');
            //清除单个帖子的缓存
            $redis->delete('tweet_' . $this->tid);
        }
        return $ret;
    }

    /**
     * 统计帖子数方法(根据时间)
     * @param  [type] $where      [description]
     * @param  [type] $where_time [description]
     * @param  [type] $order_by   [description]
     * @return [type]             [description]
     */
    public static function getTweetStatPage($where, $where_time, $order_by) {
        //获取总数 分页
        $sql = "select count(*) as count from ci_user_detail cut inner join ci_user cu on cu.id=cut.uid  where register_status=0 $where ";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $count_1 = $command_count->queryAll();
        $pages = new Pagination(['totalCount' => $count_1[0]['count'], 'pageSize' => 30]);
        //获取数据
        $page_ruls = " limit " . $pages->limit . " offset " . $pages->offset;
        //查找
        $sql = " select uid,sname,avatar,
                (select count(*) from ci_tweet as ct where ct.uid=cut.uid and ct.is_del=0 $where_time ) as tweet_count,
                (select count(*) from eci_comment as ect where subjecttype=0 and ect.is_del=0 and  subjectid in  (select tid from ci_tweet as ct where ct.uid=cut.uid and ct.is_del=0 $where_time )) as comment_count,
                (select count(*) from ci_resource as cr where cr.rid in (select resource_id from ci_tweet as ct where ct.uid=cut.uid and ct.is_del=0 $where_time)) as img_count,
                (select count(*) from eci_scan_tweet_log as ectl where ectl.tid in (select tid from ci_tweet as ct where ct.uid=cut.uid and ct.is_del=0 $where_time)) as prize_count
                from ci_user_detail as cut inner join ci_user cu on cu.id=cut.uid  where register_status=0   $where order by $order_by desc $page_ruls";
        $command = $connection->createCommand($sql);
        $models = $command->queryAll();
        return ['models' => $models, 'pages' => $pages];
    }

    /**
     * 统计通过用户id 获取帖子图片rid 用于获取图片数 
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    public static function getTweetStatByWhere($where) {
        //获取总数 分页
        $sql = "select resource_id from ci_tweet  where $where";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        return $command_count->queryAll();
    }

    /**
     * 作品转素材消息推送
     * 推送消息发送的缓存服务器地址为cachequeue对应的服务器
     * @param unknown $fromid 
     * @param unknown $touid  
     * @param unknown $tid 帖子id
     */
    static function tweetToMaterialPushMsg($from_uid, $to_uid, $tid) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;

        $params['action_type'] = SysMsgTypeEnum::TWEET_TO_MATERIAL;
        $params['from_uid'] = $from_uid;
        $params['to_uid'] = $to_uid;
        $params['content_id'] = $tid;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $value = json_encode($params);
        $redis->lpush($rediskey, $value);
    }

    /**
     * 推荐步骤图推送
     * 推送消息发送的缓存服务器地址为cachequeue对应的服务器
     * @param unknown $fromid 
     * @param unknown $touid  
     * @param unknown $tid 帖子id
     */
    static function tweetRecLessonPushMsg($from_uid, $to_uid, $tid) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;

        $params['action_type'] = SysMsgTypeEnum::TWEET_REC_LESSON;
        $params['from_uid'] = $from_uid;
        $params['to_uid'] = $to_uid;
        $params['content_id'] = $tid;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $value = json_encode($params);

        $redis->lpush($rediskey, $value);
    }

    /**
     * 分页获取所有后台帖子信息（根据更新时间utime排序）
     */
    public static function getMisTweetPageByUtime($where) {
        $query = parent::find();

        $countQuery = $query//->select('COUNT(*)') 
                ->from('ci_tweet')
                ->where(['ci_tweet.is_del' => 0])
                ->andWhere($where)
                ->leftJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')
                ->count();
        //var_dump($query->createCommand()->getRawSql());
        //分页对象计算分页数据           
        $pages = new Pagination(['totalCount' => $countQuery]);
        $query = new \yii\db\Query();
        //获取数据
        $models = $query
                ->select('ci_tweet.*,ci_user_detail.*,(select count(*) from eci_comment where subjectid=tid and subjecttype=0 and is_del=0 and uid>0) as cmtcount')
                ->from('ci_tweet')
                ->where(['ci_tweet.is_del' => 0])
                ->andWhere($where)
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->leftJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')
                ->orderBy('ci_tweet.utime DESC')
                ->all();

        return ['models' => $models, 'pages' => $pages, 'pageSize' => 1];
    }

    public static function findMaterialRid($f_catalog_id = NULL, $s_catalog_id = NULL, $tags = NULL, $content = NULL, $tags1 = '') {
        //获取数据
        $where = [];
        if ($f_catalog_id) {
            $where['f_catalog_id'] = $f_catalog_id;
        }
        if ($s_catalog_id) {
            $where['s_catalog_id'] = $s_catalog_id;
        }
        if ($tags) {
            $where['tags'] = $tags;
        }
        $where_content = [];
        if ($content) {
            $where_content = ['like', 'content', $content];
        }

        $tablename = parent::tableName();
        $query = parent::find();

        $countQuery = $query//->select('COUNT(*)') 
                ->from($tablename)
                ->where(["$tablename.is_del" => 0, "$tablename.type" => 1])
                ->andWhere($where);
        if ($tags1) {
            $connection = Yii::$app->db; //连接
            if ($content) {
                $wheres = " and content like  '%$content%'";
            }
            foreach ($tags1 as $key => $val) {
                $sql = "select $tablename.tid  from $tablename left join ci_user_detail on ci_user_detail.uid = ci_tweet.uid where $tablename.is_del=0 and $tablename.type=1 and f_catalog_id=$f_catalog_id and s_catalog_id=$s_catalog_id " .
                        " and find_in_set('$val',tags)$wheres;";
                $command = $connection->createCommand($sql);
                $modes[$key] = $command->queryAll();
            }
        }
        $arr = [];
        if ($modes) {
            foreach ($modes as $k => $v) {
                foreach ($v as $kk => $vv) {
                    $arr [] = $vv['tid'];
                }
            }
        }
        $newArray = array_unique($arr);
        if ($newArray) {
            # $countQuery->andWhere(['in', 'tags', $tags1]);
            $countQuery->andWhere(['in', $tablename . '.tid', $newArray]);
        }
        $countQuery = $countQuery->andWhere($where_content)->count();
        //分页对象计算分页数据           
        $pages = new Pagination(['totalCount' => $countQuery]);

        if (empty($newArray)) {
            $models = (new \yii\db\Query())
                    ->select("$tablename.*,ci_user_detail.*")
                    ->from($tablename)
                    ->where(["$tablename.is_del" => 0, "$tablename.type" => 1])
                    ->andWhere($where)
                    ->andWhere($where_content)
                    ->offset($pages->offset)
                    ->limit($pages->limit)
                    ->leftJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')
                    ->orderBy("$tablename.tid desc")
                    ->all();
        } else {
            $models = (new \yii\db\Query())
                    ->select("$tablename.*,ci_user_detail.*")
                    ->from($tablename)
                    ->where(["$tablename.is_del" => 0, "$tablename.type" => 1])
                    ->andWhere($where)
                    ->andWhere(['in', $tablename . '.tid', $newArray])
                    ->andWhere($where_content)
                    ->offset($pages->offset)
                    ->limit($pages->limit)
                    ->leftJoin('ci_user_detail', 'ci_user_detail.uid = ci_tweet.uid')
                    ->orderBy("$tablename.tid desc")
                    #  ->createCommand()->getRawSql();
                    # echo $models;
                    # exit;
                    ->all();
        }
        return ['models' => $models, 'pages' => $pages, 'pageSize' => 1];
    }

    /**
     * 获取用户某一个类型的求批改数量
     * @param unknown $uid
     * @param unknown $fcatalog_id
     */
    static function getCorrectedCount($uid, $fcatalog_id) {
        $models = (new \yii\db\Query())
                ->select(['tid'])
                ->from(parent::tableName())
                ->where(['uid' => $uid])
                ->andWhere(['is_del' => 0])
                ->andWhere(['type' => 4])
                ->andWhere(['f_catalog_id' => $fcatalog_id])
                ->all();
        if ($models) {
            return count($models);
        }
        return 0;
    }

}
