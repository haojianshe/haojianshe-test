<?php
namespace api\modules\v3_0_2\controllers\home;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\models\myb\UserHomeProfession;
use api\service\UserDetailService;
/**
 * 设置首页角色
 * @author ihziluoh
 *
 */
class SetHomeProfessionAction extends ApiBaseAction{

   public  function run(){
        $professionid=$this->requestParam('professionid');
        $provinceid=$this->requestParam('provinceid');
        $uid=$this->_uid;
        //professionid 用戶身份可能為0
        if($professionid || $provinceid ||$professionid==0){
            //查找用户记录
            $model=UserHomeProfession::find()->where(['uid'=>$uid])->one();
            $save_res=false;
            if($model){
                //更新
                if($professionid ||$professionid==0){
                    $model->professionid=$professionid;
                } 
                if($provinceid){
                    $model->provinceid=$provinceid;
                }
                $model->utime=time();
                $save_res=$model->save();
            }else{
                //新建
                $model=new UserHomeProfession();
                $model->uid=$uid;
                if($professionid ||$professionid==0){
                    $model->professionid=$professionid;
                } 
                if($provinceid){
                    $model->provinceid=$provinceid;
                }
                $model->ctime=time();
                $model->utime=time();
                $save_res=$model->save();
            }
        }
        
        //更新用户角色
        //UserDetailService::updateProfessionProvince($uid,$professionid,$provinceid);
        if($save_res){
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        }else{
            $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }
    }
}