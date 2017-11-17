<?php
namespace console\controllers\trigger;

use Yii;
use yii\base\Action;

use common\models\myb\TempComment;
use common\models\myb\TempTweet;
use api\service\CommentService;

use common\models\myb\Tweet;
use common\models\myb\Comment;

/**
 * 启动pushservice守护进程
 */
class TweetCommentPublishAction extends Action
{
    public function run()
    {
        //查找三天前还有评论的帖子 如果有则发送这些帖子的评论
        $havcommenttweet=TempTweet::find()->where(['flag'=>1])->andWhere(['<','ctime',time()-3*24*60*60])->andWhere(['>','have_comment_num',0])->orderBy(['rand()' => SORT_DESC])->one();
        if(!empty($havcommenttweet)){
            $tempcomment=TempComment::find()->where(['flag'=>0])->andWhere(['tid'=>$havcommenttweet->tid])->orderBy(['rand()' => SORT_DESC])->one();
        }else{
            //获取临时评论
            $tempcomment=TempComment::find()->where(['flag'=>0])->andWhere(['is not','tid',null])->orderBy(['rand()' => SORT_DESC])->one();
        }
        //加入评论表
        $comment=new CommentService();
        $comment->content=$tempcomment->content;
        $comment->subjectid=$tempcomment->tid;
        $comment->subjecttype=0;
        $comment->ctime=time();
        $comment->uid=rand(500,999);
        $comment->ctype=0;
        $comment->is_del=0;
        $res=$comment->save();
        CommentService::incCmtCountRedis($comment->subjecttype,$comment->subjectid);
        //更新临时表
        $tempcomment->flag=1;
        $tempcomment->save();
        //更新表中剩余评论数
        if(!empty($havcommenttweet) && $res){
            $havcommenttweet->have_comment_num=$havcommenttweet->have_comment_num-1;
            $havcommenttweet->save();
        }else{
            $tweet=TempTweet::findOne(['tid'=>$tempcomment->tid]);
            $tweet->have_comment_num=$tweet->have_comment_num-1;
            $tweet->save();
        }
    }
}