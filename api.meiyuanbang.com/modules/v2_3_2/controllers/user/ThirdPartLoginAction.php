<?php
namespace api\modules\v2_3_2\controllers\user;

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
 * 第三方登陆接口
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
                    //$uid=self::newUser($oauth_type,$oauth_key,$unionid,$sname,$avatar,$genderid);
                    $this->controller->renderJson(ReturnCodeEnum::ERR_USER_NO_BIND);
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
                        $this->controller->renderJson(ReturnCodeEnum::ERR_USER_NO_BIND);
                        //$uid=self::newUser($oauth_type,$oauth_key,$unionid,$sname,$avatar,$genderid);
                    }
                }
            }
        }else{
            //qq 微博登录
            $user=UserService::findOne(['oauth_key'=>$oauth_key,"register_status"=>0]);
            if($user){
                $uid=$user->id;
            }else{
                //$uid=self::newUser($oauth_type,$oauth_key,$unionid,$sname,$avatar,$genderid);
                $this->controller->renderJson(ReturnCodeEnum::ERR_USER_NO_BIND);
            }
        }
        $user_detail_info=UserDetailService::getByUid($uid);
        $muser=UserService::findOne(['id'=>$uid]);
        if(empty($muser->umobile)){
            $this->controller->renderJson(ReturnCodeEnum::ERR_USER_NO_BIND);
        }
        $user_detail_info["token"]=UserTokenService::createToken($uid);
        //登陆增加积分 每天一次
        $user_detail_info["addcoincount"]=0;
        //$user_detail_info["addcoincount"]=UserCoinService::addCoinsByUid($uid,SysMsgTypeEnum::ADDCOIN_LOGIN_TYPE,1,SysMsgTypeEnum::LOGIN_GET_COINS);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$user_detail_info);
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
