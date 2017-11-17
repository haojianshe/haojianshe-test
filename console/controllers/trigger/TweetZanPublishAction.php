<?php
namespace console\controllers\trigger;

use Yii;
use yii\base\Action;
use api\service\ZanService;
use common\models\myb\Zan;

use common\models\myb\TempTweet;
use api\service\UserDetailService;
/**
 * 启动pushservice守护进程
 */
class TweetZanPublishAction extends Action
{
    public function run()
    {
        ///home/web/backcode/pushservice trigger/tweetzanpublish
        //$tweet=TweetService::find()->where(['is_del'=>0])->andWhere(['>','ctime',time()-5*60*60])->andWhere(['>','ctime',time()-3*24*60*60])
        //随机获取三天内的一篇帖子
        //$temptweet=TempTweet::find()->where(['flag'=>1])->andWhere(['>','ctime',time()-3*24*60*60])->orderBy(['rand()' => SORT_DESC])->one();
        //$temptweet=TempTweet::findOne(['flag'=>1]);
        // >5 分钟 小于三天  优先等于0  否则小于10随机
        $zan=new ZanService();
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select tid,uid,(select count(*) from ci_zan as z where z.tid=c.tid) as count from ci_tweet as c  where ctime > unix_timestamp(now())-3*24*60*60  and ctime < unix_timestamp(now())-5*60');
        $data = $command->queryAll();
        //打乱顺序
        shuffle($data);
        //判断是否存在 >5 分钟 小于三天  优先等于0 
        foreach ($data as $key => $value) {
           if($value['count']==0){
                $hastweet=true;
                $zan->tid=$value['tid'];
                $zan->owneruid = $value['uid'];
                break;
           }
        }
        //不存在 >5 分钟 小于三天  优先等于0   小于10的
        if(!$hastweet){
             foreach ($data as $key => $value) {
              if($value['count']<10){
                    $hastweet=true;
                    $zan->tid=$value['tid'];
                    $zan->owneruid = $value['uid'];
                    break;
               }
            }
        }
         
        //都不存在退出程序
        if(!$hastweet){
            exit;
        }
        
        $zan->uid = rand(500,999);
        $zan_user=ZanService::findOne(['uid'=>$zan->uid]);
        //判断用户是否赞过 是否是当前用户发帖
        if(!empty($zan_user) || $zan_user==$zan->owneruid){
            exit;
        }
        // $zan->tid = $temptweet->tid;
        //获取用户昵称 保存到评论表
        $user_info=UserDetailService::getByUid($zan->uid);
        $zan->username = $user_info['sname'];
       
        $zan->ctime = time();
        $zan->save();
        
    }    
}