<?php
namespace api\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserService;
use api\service\UserDetailService;
use api\service\UserTokenService;
use api\service\TweetService;
use api\service\CommentService;
use api\service\CorrectService;
use api\service\UserCoinService;
use api\service\TeamMemberService;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 活动列表页
 */
class ThirdPartLoginAction extends ApiBaseAction
{
	public function run()
    {

        $request=Yii::$app->request;
        //加入时间
        $oauth_type=$this->requestParam('oauth_type',true);
        $oauth_key=$this->requestParam('oauth_key',true);
        $unionid=$this->requestParam('unionid');
        $sname=$this->requestParam('sname',true);
        $avatar=$this->requestParam('avatar');
        $genderid=$this->requestParam('genderid');
        $qd=$this->requestParam('qd');
        //设置默认头像
        if(empty($avatar)){
            $avatar="http://img.meiyuanbang.com/user/default/app_default.png";
        }
        //用户头像
        if ("" !== $avatar) {
            // process avatar to json
            $avatar = array(
                'img'   => array(
                    'n' => array(
                        'url'   => $avatar,
                    ),
                    's' => array(
                        'url'   => $avatar,
                    ),
                ),
            );
            $avatar = json_encode($avatar);
        }
       
        if($oauth_type=="weixin"){
            if(empty($unionid)){
                $user=UserService::findOne(['oauth_key'=>$oauth_key,"register_status"=>0]);
                if($user){
                    //存在用户取token
                    $uid=$user->id;
                }else{
                    //增加新用户 new
                    $uid=self::newUser($oauth_type,$oauth_key,$unionid,$sname,$avatar,$genderid,$qd);
                }
            }else{
                $wx_user=UserService::findOne(['oauth_type'=>$oauth_type,'oauth_key'=>$oauth_key,"register_status"=>0]);

                if($wx_user){
                    if(empty($wx_user->unionid)){
                        $wx_user->unionid=$unionid;
                        $wx_user->pass_word='00';
                        $wx_user->pass_mark='00';
                        $wx_user->save();
                        $uid=$wx_user->id;
                    }
                    $users=UserService::findAll(['oauth_type'=>$oauth_type,'unionid'=>$unionid,"register_status"=>0]);
                    if(count($users)>=2){
                        foreach ($users as $key => $value) {
                            $user_uid[]=$value['id'];
                        }
                        $uid=self::mergedData($user_uid);
                        //合并用户 更改数据
                    }else{
                        $uid=$users[0]->id;
                        //获取token 返回
                    }
                }else{
                    $users=UserService::findOne(['oauth_type'=>$oauth_type,'unionid'=>$unionid,"register_status"=>0]);

                    if($users){
                        $uid=$users->id;
                        //登录获取token'
                    }else{
                        $uid=self::newUser($oauth_type,$oauth_key,$unionid,$sname,$avatar,$genderid,$qd);
                    }
                }
            }
        }else{
            //qq 微博登录
            $user=UserService::findOne(['oauth_key'=>$oauth_key,"register_status"=>0]);
            if($user){
                $uid=$user->id;
            }else{
                $uid=self::newUser($oauth_type,$oauth_key,$unionid,$sname,$avatar,$genderid,$qd);
            }
        }
        $user_detail_info=UserDetailService::getByUid($uid);
        $user_detail_info["token"]=self::createToken($uid);
        //登陆增加积分 每天一次
        $user_detail_info["addcoincount"]=0;//UserCoinService::addCoinsByUid($uid,SysMsgTypeEnum::ADDCOIN_LOGIN_TYPE,1,SysMsgTypeEnum::LOGIN_GET_COINS);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$user_detail_info);
    }
    /**
     * 新用户写入数据库
     * @param  [type] $oauth_type [description]
     * @param  [type] $oauth_key  [description]
     * @param  [type] $unionid    [description]
     * @param  [type] $sname      [description]
     * @param  [type] $avatar     [description]
     * @param  [type] $genderid   [description]
     * @return [type]             [description]
     */
    static function newUser($oauth_type,$oauth_key,$unionid,$sname,$avatar,$genderid,$qd=null){
        //插入user表
        $model= new UserService();
        $model->oauth_type=$oauth_type;
        $model->oauth_key=$oauth_key;
        if($oauth_type=="weixin"){
            $model->unionid=$unionid;
        }        
        $model->pass_word="00";
        $model->pass_mark="00"; 
        $model->login_type=1;
        $model->register_status=2;
        $model->create_time=time();
        if($qd){
        	$model->qd = $qd;
        }
        $user_ret=$model->save();
        if($user_ret){
            //插入userdetail 表
            $user_detail=new UserDetailService();
            $user_detail->uid=$model->attributes['id'];
            $user_detail->sname=self::randomSname($sname);
            $user_detail->genderid=$genderid;
            $user_detail->avatar=$avatar;
            $userdetail_ret=$user_detail->save();
            if($userdetail_ret){
                //新用户第一次登陆，在金币表添加一条记录
                $usercoin = new UserCoinService();
                $usercoin->uid=$user_detail->uid;
                $usercoin->gradeid=1;
                $usercoin->total_coin=0;
                $usercoin->remain_coin=0;
                $usercoin_ret=$usercoin->save();
                if($usercoin_ret){
                    $model->register_status=0;
                    $user_ret=$model->save();
                }else{
                     $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
                }
            }else{
                 $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
            }
        }else{
             $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }
        return $user_detail->uid;
    }
    /**
     * 判断姓名是否重复若重复生成一个带数字后缀的用户名
     * @param  [type] $sname [description]
     * @return [type]        [description]
     */
    static function randomSname($sname) {
        $new_name = $sname;
        $max_seed = 10000;
        $min_seed = 0;
        $counter = 0;
        while (NULL !== UserDetailService::findOne(["sname"=>$new_name])) {
            if ($counter >= 10) {
                $min_seed = $max_seed;
                $max_seed *= 10;
                $counter = 0;
            }
            $new_name = $sname . '_' . strval(rand($min_seed, $max_seed - 1));
            $counter++;
        }

        return $new_name;
    }
    /**
     * 生成返回token
     * @param  [type] $uid [description]
     * @param  string $ip  [description]
     * @return [type]      [description]
     */
    static function createToken($uid, $ip = '') {
        //生成token
        $create_time = time();
        $invalid_time = $create_time + 157680000; // 5*365*24*60*60 5年token  A week: 1week * 7day * 24hour * 60minute * 60second
        $hash_str = strval($create_time).'-'.strval($uid).'-'.strval($ip).'-'.strval(rand());
        $hash_key = hash('md5', $hash_str);
        $model= new UserTokenService();
        $model->uid=$uid;
        $model->hash_key=$hash_key;
        $model->create_time =$create_time;
        $model->invalid_time=$invalid_time;
        $model->ip=$ip;
        $model->is_valid= 1;
        $model->save();
        return $hash_key;
    }
    /**
     * 合并数据 更新用户状态 返回uid 
     * @param  [type] $uids [description]
     * @return [type]       [description]
     */
    static function mergedData($uids){
        //定义存储当前帖子量最大的用户uid  
        $befor_tweet_num=0;
        $befor_uid=0;
        foreach ($uids as $key => $value) {
            //获取当前用户帖子数量
            $tweet_num=TweetService::getTweetNum($value);
            //判断是否获取的第一个用户
            if($befor_tweet_num!=0){
                //判断当前用户与存储的用户帖子数量对比 若大于则合并数据并更改用户
                if($tweet_num>$befor_tweet_num){
                  self::updateUidMargedData($befor_uid,$value);
                  $befor_tweet_num=$tweet_num;
                  $befor_uid=$value;
                  $uid=$value;
                }else{
                    //若小于则更新数据 不更改存储用户
                  self::updateUidMargedData($value,$befor_uid);
                  $uid=$befor_uid;
                }
            }else{
                //循环第一次赋值
                $befor_tweet_num=$tweet_num;
                $befor_uid=$value;
                $uid=$value;
            }
        }
        return $uid;
            
    }
    /**
     * 更新合并数据 并更新用户状态
     * @param  [type] $brfor_uid  [description]
     * @param  [type] $updata_uid [description]
     * @return [type]             [description]
     */
    static function updateUidMargedData($befor_uid,$updata_uid){
        //更改数据库中 用户id为befor_uid更新成updata_uid
        $tweets=TweetService::updateAll(['uid'=>$updata_uid],['uid'=>$befor_uid]);
        $comment=CommentService::updateAll(['uid'=>$updata_uid],['uid'=>$befor_uid]);
        $correct=CorrectService::updateAll(['submituid'=>$updata_uid],['submituid'=>$befor_uid]);
        $TeamMember=TeamMemberService::updateAll(['uid'=>$updata_uid],['uid'=>$befor_uid]);
        //更改用户状态
        $user=UserService::findOne(['id'=>$befor_uid]);
        $user->pass_word="00";
        $user->pass_mark="00"; 
        $user->register_status=2;
        $user->save();
        //清除所有缓存
        $redis = Yii::$app->cache;
        $ret = $redis->flushdb();
    }
}
