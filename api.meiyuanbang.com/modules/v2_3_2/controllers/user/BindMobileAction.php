<?php
namespace api\modules\v2_3_2\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserSmsService;
use api\service\UserService;
use api\service\UserTokenService;
use api\service\CorrectService;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\UserCoinService;
use api\service\CointaskService;

/**
 *  
 * 实名认证绑定手机号接口
 */
class BindMobileAction extends ApiBaseAction
{
	public function run()
    {
        $request=Yii::$app->request;
        //加入时间
        $umobile=$this->requestParam('umobile',true);
        $password=$this->requestParam('password',true);
        
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


        //查找手机号账号
        $umobile_user=UserService::findOne(['umobile'=>$umobile,"register_status"=>0]);
        if(empty($umobile_user)){
            //手机号未注册
            $this->controller->renderJson(ReturnCodeEnum::USER_NOT_EXIST);
        }else{
            if($umobile_user->oauth_type){
                //手机号已绑定
                $this->controller->renderJson(ReturnCodeEnum::ERR_USER_MOBILE_BIND);
            }
            if($umobile_user->pass_word!=$password){
                //密码错误
                $this->controller->renderJson(ReturnCodeEnum::USER_ERR_PASS);
            }
        }
           

        if($oauth_type=="weixin"){
            $oauth_user=UserService::findOne(["oauth_type"=>$oauth_type,"unionid"=>$unionid,"register_status"=>0]);
        }else{
            $oauth_user=UserService::findOne(["oauth_type"=>$oauth_type,"oauth_key"=>$oauth_key,"register_status"=>0]);
        }
        //是否有第三方登录账号 若没有新建 有则更新手机号 密码
        if($oauth_user){
            //已绑定手机号
            if($oauth_user->umobile){
                $this->controller->renderJson(ReturnCodeEnum::ERR_USER_OAUTH_BIND);
            }
            //获取详情
            $umobile_detail=UserDetailService::findOne(['uid'=>$umobile_user->id]);
            $oauth_detail=UserDetailService::findOne(['uid'=>$oauth_user->id]);
            //判断用户角色并合并数据
            if($umobile_detail->featureflag==1 && $oauth_detail->featureflag!=1){
                $save_oauth_rec=false;
            }else if($umobile_detail->featureflag!=1 && $oauth_detail->featureflag==1){
                $save_oauth_rec=true;
            }else if($umobile_detail->featureflag==1 && $oauth_detail->featureflag==1){
                //合并老师账号  保留求批改数据多的
                $oauth_user_correct=CorrectService::find()->where(["teacheruid"=>$oauth_user->id])->andWhere(["in","status",[0,1]])->count();
                $umobile_user_correct=CorrectService::find()->where(["teacheruid"=>$umobile_user->id])->andWhere(["in","status",[0,1]])->count();
                if($umobile_user_correct>$oauth_user_correct){
                    $save_oauth_rec=false;
                }else{
                    $save_oauth_rec=true;
                }
            }else if($umobile_detail->featureflag!=1 && $oauth_detail->featureflag!=1){
                //合并学生账号  保留求批改数据多的
                $oauth_user_correct=CorrectService::find()->where(["submituid"=>$oauth_user->id])->andWhere(["in","status",[0,1]])->count();
                $umobile_user_correct=CorrectService::find()->where(["submituid"=>$umobile_user->id])->andWhere(["in","status",[0,1]])->count();
                if($umobile_user_correct>$oauth_user_correct){
                    $save_oauth_rec=false;
                }else{
                    $save_oauth_rec=true;
                }
            }
            //判断保留哪一个账号
            if($save_oauth_rec){
                //保留第三方账号数据
                $oauth_user->pass_word=$password;
                $oauth_user->umobile=$umobile;
                $oauth_user->qd = $qd;
                $ret_upmobile=$oauth_user->save();
                if($ret_upmobile){
                    $umobile_user->pass_mark='00';
                    $umobile_user->register_status=2;
                    $umobile_user->save();

                    //修改个人信息
                    //$oauth_detail->avatar=$umobile_detail->avatar;
                    $is_oauth_update=false;
                    if (empty($oauth_detail->avatar)) {
                        $is_oauth_update=true;
                        $oauth_detail->avatar=$umobile_detail->avatar;
                    }
                    if (empty($oauth_detail->professionid) && $oauth_detail->professionid!=0) {
                        $is_oauth_update=true;
                        $oauth_detail->professionid=$umobile_detail->professionid;
                    }
                    if (empty($oauth_detail->genderid) && $oauth_detail->genderid!=0) {
                        $is_oauth_update=true;
                        $oauth_detail->genderid=$umobile_detail->genderid;
                    }
                    if(empty($oauth_detail->provinceid)){
                        $is_oauth_update=true;
                        $oauth_detail->provinceid=$umobile_detail->provinceid;
                    }
                    if(empty($oauth_detail->city)){
                        $is_oauth_update=true;
                        $oauth_detail->city=$umobile_detail->city;
                    }
                    if(empty($oauth_detail->intro)){
                        $is_oauth_update=true;
                        $oauth_detail->intro=$umobile_detail->intro;
                    }
                    if(empty($oauth_detail->school)){
                        $is_oauth_update=true;
                        $oauth_detail->school=$umobile_detail->school;
                    }
                    if(empty($oauth_detail->ukind) && $oauth_detail->ukind!=0){
                        $is_oauth_update=true;
                        $oauth_detail->ukind=$umobile_detail->ukind;
                    }
                    if(empty($oauth_detail->ukind_verify) && $oauth_detail->ukind_verify!=0){
                        $is_oauth_update=true;
                        $oauth_detail->ukind_verify=$umobile_detail->ukind_verify;
                    }
                    if(empty($oauth_detail->featureflag) && $oauth_detail->featureflag!=0){
                        $is_oauth_update=true;
                        $oauth_detail->featureflag=$umobile_detail->featureflag;
                    }
                    if($is_oauth_update){
                        $oauth_detail->save();
                    }                    
                }else{
                    $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
                }
                $uid=$oauth_user->id;
            }else{
                //更新手机号 信息以手机账号为准 
                $umobile_user->oauth_type=$oauth_type;
                $umobile_user->oauth_key=$oauth_key;
                $umobile_user->qd = $qd;
                if($unionid){
                    $umobile_user->unionid=$unionid;
                }
                $umobile_user->pass_mark='00';
                $ret_upmobile=$umobile_user->save();
                if($ret_upmobile){
                    $oauth_user->register_status=2;
                    $oauth_user->save();
                    //修改个人信息
                    //$umobile_user->avatar=$oauth_user->avatar;
                    $umobile_detail->sname=self::randomSname($oauth_detail->sname);
                    if (empty($umobile_detail->avatar)) {
                        $umobile_detail->avatar=$oauth_detail->avatar;
                    }
                    if (empty($umobile_detail->professionid) && $umobile_detail->professionid!=0) {
                        $umobile_detail->professionid=$oauth_detail->professionid;
                    }
                    if (empty($umobile_detail->genderid) && $umobile_detail->genderid!=0) {
                        $umobile_detail->genderid=$oauth_detail->genderid;
                    }
                    if(empty($umobile_detail->provinceid)){
                        $umobile_detail->provinceid=$oauth_detail->provinceid;
                    }
                    if(empty($umobile_detail->city)){
                        $umobile_detail->city=$oauth_detail->city;
                    }
                    if(empty($umobile_detail->intro)){
                        $umobile_detail->intro=$oauth_detail->intro;
                    }
                    if(empty($umobile_detail->school)){
                        $umobile_detail->school=$oauth_detail->school;
                    }
                    if(empty($umobile_detail->ukind) && $umobile_detail->ukind!=0){
                        $umobile_detail->ukind=$oauth_detail->ukind;
                    }
                    if(empty($umobile_detail->ukind_verify) && $umobile_detail->ukind_verify!=0){
                        $umobile_detail->ukind_verify=$oauth_detail->ukind_verify;
                    }
                    if(empty($umobile_detail->featureflag) && $umobile_detail->featureflag!=0){
                        $umobile_detail->featureflag=$oauth_detail->featureflag;
                    }
                    $umobile_detail->save();
                }else{
                    $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
                }
                $uid=$umobile_user->id;
            }
        }else{
            //已有手机号账号 无第三方账号 合并第三方到手机号
            $umobile_user->oauth_type=$oauth_type;
            $umobile_user->oauth_key=$oauth_key;
            $umobile_user->qd = $qd;
            if($oauth_type=="weixin"){
                $umobile_user->unionid=$unionid;
            }
            $umobile_user->save();
            $uid=$umobile_user->id;

            $umobile_detail=UserDetailService::findOne(['uid'=>$umobile_user->id]);
            $umobile_detail->sname=self::randomSname($sname);
            $umobile_detail->save();
            //$uid=self::newUser($oauth_type,$oauth_key,$unionid,$sname,$avatar,$genderid,$umobile,$password);
        }
        
        $user_detail_info=UserDetailService::getByUid($uid);
        $user_detail_info["token"]=UserTokenService::createToken($uid);

        //第三方账号绑定增加金币
        $tasktype = CointaskTypeEnum::USER_UNION;
        //需要加金币
        $coinCount = CointaskDictService::getCoinCount($tasktype);
        UserCoinService::addCoinNew($uid, $coinCount);
        $user_detail_info['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);

        $user_detail_info["addcoincount"]=0;
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
}
