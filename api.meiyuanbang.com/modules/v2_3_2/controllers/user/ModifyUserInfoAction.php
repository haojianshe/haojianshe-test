<?php
namespace api\modules\v2_3_2\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserDetailService;
use api\service\UserService;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use api\lib\enumcommon\ReturnCodeEnum;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\UserCoinService;
use api\service\CointaskService;
/**
 * 更改信息接口
 */
class ModifyUserInfoAction extends ApiBaseAction
{
	public function run()
    {

    
        $request=Yii::$app->request;
        $sname=$this->requestParam('sname');
        //省id
        $provinceid=$this->requestParam('provinceid');
        $professionid=$this->requestParam('professionid');
        $genderid=$this->requestParam('genderid');
        $city=$this->requestParam('city');
        $avatar=$this->requestParam('avatar');
        $intro=$this->requestParam('intro');
        $school=$this->requestParam('school');
        $type=$this->requestParam('type');
        //市
        $city_id=$this->requestParam('city_id');
        //学校 id
        $school_id=$this->requestParam('school_id');
        //县 、区
        $area_id=$this->requestParam('area_id');
        
        //需区分是否是第一次修改
        $uid=$this->_uid;
        $user=UserService::findOne(["id"=>$uid,"register_status"=>0]);
     
        if(empty($user)){
            //用户不存在
            $this->controller->renderJson(ReturnCodeEnum::USER_NOT_EXIST);  
        }
        $user_detail=UserDetailService::findOne(["uid"=>$uid]);
        $is_user_update=false;
        if($sname){
            $sname_user=UserDetailService::findOne(["sname"=>"$sname"]);
            if($sname_user){
                //用户名已存在
                $this->controller->renderJson(ReturnCodeEnum::USER_SNAME_EXIST); 
            }
            $is_user_update=true;
            $user_detail->sname=$sname;
        }
        
        if($provinceid){
            $is_user_update=true;
            $user_detail->provinceid=$provinceid;
        }
        
        //判断类型值是否存在
        if(isset($professionid)){
            $is_user_update=true;
            $user_detail->professionid=$professionid;
        }
        if($genderid|| $genderid==0){
            $is_user_update=true;
            $user_detail->genderid=$genderid;
        }
        if($city){
            $is_user_update=true;
            $user_detail->city=$city;
        }
        if($avatar){
            $is_user_update=true;
            $user_detail->avatar=$avatar;
        }
        
        //判断参数是否存在
        if(isset($_REQUEST['intro'])){
            $is_user_update=true;
            $user_detail->intro= trim($intro);
       }
     
       //自定义的学校和选择的学校id有一者不为空就可以操作
        if((isset($_REQUEST['school_id']) || isset($_REQUEST['school']))){
               $user_detail->school=$school;
               $user_detail->school_id=$school_id;
        }
        
         if($city_id){
            $is_user_update=true;
            $user_detail->city_id=$city_id;
        }
         if($area_id){
            $is_user_update=true;
            $user_detail->area_id=$area_id;
        }
        $user_detail->save();
        $user_detail_info=UserDetailService::getByUid($uid);
        
        // 金币处理
         if($type==1){
            if($user_detail_info['avatar'] &&( $user_detail_info['genderid'] || $user_detail_info['genderid']==0)&&($user_detail_info['professionid'] || $user_detail_info['professionid']==0) ){
                 //第一次注册完善信息
                $tasktype = CointaskTypeEnum::FINISH_REGIST_INFO;
                $coinCount = CointaskDictService::getCoinCount($tasktype);
                if(CointaskService::IsAddByOneTime($this->_uid, $tasktype)){
                    //加金币
                    UserCoinService::addCoinNew($uid, $coinCount);
                    $data['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
                     $user_detail_info['cointask']= $data['cointask'];
                }
            }
         }
       
        
        
        //完成信息更改
        if($user_detail_info['avatar'] && ($user_detail_info['genderid'] || $user_detail_info['genderid']==0) && ($user_detail_info['provinceid'] ||$user_detail_info['provinceid']==0) && $user_detail_info['professionid'] && $user_detail_info['intro'] && $user_detail_info['school']){
            $tasktype = CointaskTypeEnum::FINISH_USERINFO;
            $coinCount = CointaskDictService::getCoinCount($tasktype);
            if(CointaskService::IsAddByOneTime($this->_uid, $tasktype)){
                //加金币
                UserCoinService::addCoinNew($uid, $coinCount);
                $data['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
                $user_detail_info['cointask']= $data['cointask'];
            }
        }         

        
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$user_detail_info);
    }
   
}
