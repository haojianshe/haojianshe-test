<?php
namespace console\controllers\trigger;

use Yii;
use yii\base\Action;

use common\models\myb\TempComment;
use common\models\myb\TempTweet;

use api\service\TweetService;
use common\models\myb\Tweet;
use common\models\myb\Comment;
use common\service\DictdataService;

/**
 * 启动pushservice守护进程
 */
class TweetPublishAction extends Action
{
    public function run()
    {
        $temptweet = TempTweet::findOne(['flag'=>0]);
        if(empty($temptweet)){
            exit;
        }
        $tweet=new TweetService();
        $tweet->type=2;
        $tweet->uid=rand(500,999);
        $tweet->content=$temptweet->content;
        $tweet->f_catalog=$temptweet->f_catalog;
        $tweet->s_catalog=$temptweet->s_catalog;
        //获取分类id保存
        if($tweet->f_catalog){
           $tweet->f_catalog_id = DictdataService::getTweetMainTypeIdByName($tweet->f_catalog);
            if($tweet->s_catalog){
                $tweet->s_catalog_id = DictdataService::getTweetSubTypeIdByName($tweet->f_catalog_id ,$tweet->s_catalog);
            }
        }
        $tweet->resource_id=$temptweet->resource_id;
        $tweet->tags=$temptweet->tags;
        $tweet->ctime=time();
        $tweet->utime=time();
        $tweet->save();
        
        //更新缓存表
        $temptweet->tid=$tweet->attributes['tid'];
        $temptweet->flag=1;
        $temptweet->uid=$tweet->uid;
        $temptweet->save();
        //插入帖子结束

        //更新评论表中的tid
        $res=TempComment::updateAll(['tid' => $temptweet->tid], ['temptid' => $temptweet->temptid]);
    }
}