<?php
namespace api\modules\v2_3_2\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserService;
use api\service\UserDetailService;
use api\service\UserTokenService;
use api\service\UserSmsService;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use api\lib\enumcommon\ReturnCodeEnum;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\UserCoinService;
use api\service\CointaskService;
/**
 * 注册接口
 */
class RegisterAction extends ApiBaseAction
{
	public function run()
    {

        $request=Yii::$app->request;
        //加入时间
        $umobile=$this->requestParam('umobile',true);
        $password=$this->requestParam('password',true);
        $captcha=$this->requestParam('captcha',true);

        $oauth_type=$this->requestParam('oauth_type');
        $oauth_key=$this->requestParam('oauth_key');
        $unionid=$this->requestParam('unionid');
        $sname=$this->requestParam('sname');
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
        //没有昵称
        if(empty($sname)){
             $sname=substr($umobile, 0,4)."***".substr($umobile, -4);
        }
        $mobile_user=UserService::findOne(['umobile'=>$umobile,"register_status"=>0]);
        if($mobile_user){
           $this->controller->renderJson(ReturnCodeEnum::USER_EXIST);  
        }
        // 验证验证码
        $sms_info=UserSmsService::find()->where(["mobile"=>$umobile,"verifycode"=>$captcha,"operate"=>1,"valid"=>1])->orderBy("ctime desc")->one();
        //验证是否存在
        if(empty($sms_info)){
            $this->controller->renderJson(ReturnCodeEnum::ERR_SMS_VERIFYCODE_ILLEGAL);
        }
        //验证超时
        $ctime_keep = $sms_info->ctime_keep;
        if(time() > $ctime_keep) {
            $this->controller->renderJson(ReturnCodeEnum::ERR_SMS_VERIFYCODE_TIMEOUT); 
        }
        if($oauth_type){
            if($oauth_type=="weixin"){
                $oauth_user=UserService::find()->where(["oauth_type"=>$oauth_type,"unionid"=>$unionid,"register_status"=>0])->one();
            }else{
                $oauth_user=UserService::find()->where(["oauth_type"=>$oauth_type,"oauth_key"=>$oauth_key,"register_status"=>0])->one();
            }
            //判断是否绑定
            if($oauth_user->umobile){
                 $this->controller->renderJson(ReturnCodeEnum::ERR_USER_OAUTH_BIND);
            }
            //判断是否已有第三方账号 若已存在绑定手机号
            if($oauth_user){
                $oauth_user->umobile=$umobile;
                $oauth_user->pass_word=$password;
                $oauth_user->pass_mark="00";
                $oauth_user->save();
                $uid=$oauth_user->id;

                //第三方账号绑定增加金币
                $tasktype = CointaskTypeEnum::USER_UNION;
                //需要加金币
                $coinCount = CointaskDictService::getCoinCount($tasktype);
                UserCoinService::addCoinNew($uid, $coinCount);
                $data['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
            }else{
                $uid=self::newUser($umobile,$password,$sname,$avatar,$oauth_type,$oauth_key,$unionid,$genderid,$qd);
                
                //新用户注册（手机）添加金币
                $tasktype = CointaskTypeEnum::FIRST_REGISTER;
                //需要加金币
                $coinCount = CointaskDictService::getCoinCount($tasktype);
                UserCoinService::addCoinNew($uid, $coinCount);

                //第三方账号绑定增加金币
                $tasktype = CointaskTypeEnum::USER_UNION;
                //需要加金币
                $coinCount = CointaskDictService::getCoinCount($tasktype);
                UserCoinService::addCoinNew($uid, $coinCount);
                $data['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
                    
            }
        }else{
            $uid=self::newUser($umobile,$password,$sname,$avatar,null,null,null,null,$qd);

            //新用户注册（手机）添加金币
            $tasktype = CointaskTypeEnum::FIRST_REGISTER;
            //需要加金币
            $coinCount = CointaskDictService::getCoinCount($tasktype);
            UserCoinService::addCoinNew($uid, $coinCount);
            $data['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
            
        }

        
        //更改验证码失效
        $sms_info->valid=0;
        $sms_info->save();
        //获取返回信息
        $user_detail_info=UserDetailService::getByUid($uid);
        $muser=UserService::findOne(['id'=>$uid]);
        $user_detail_info["token"]=UserTokenService::createToken($uid);
        $user_detail_info["addcoincount"]=0;
        $user_detail_info['cointask']=$data['cointask'];
        //登陆增加积分 每天一次
        //$user_detail_info["addcoincount"]=UserCoinService::addCoinsByUid($uid,SysMsgTypeEnum::ADDCOIN_LOGIN_TYPE,1,SysMsgTypeEnum::LOGIN_GET_COINS);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$user_detail_info);
    }
    static function newUser($umobile,$password,$sname=NULL,$avatar=NULL,$oauth_type=NULL,$oauth_key=NULL,$unionid=NULL,$genderid=NULL,$qd=null){
        //插入user表
        $model= new UserService();
        if($oauth_type){
            $model->oauth_type=$oauth_type;
            $model->oauth_key=$oauth_key;
            if($oauth_type=="weixin"){
                $model->unionid=$unionid;
            }   
            $model->login_type=1; 
        }else{
            $model->login_type=0;
        }   
        $model->umobile=$umobile;
        $model->pass_word=$password;
        $model->pass_mark="00"; 
        
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
}
