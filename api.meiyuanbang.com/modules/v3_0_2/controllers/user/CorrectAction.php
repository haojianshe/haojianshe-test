<?php
namespace api\modules\v3_0_2\controllers\user;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;

use api\service\CorrectService;
use api\service\CapacityModelService;
use api\service\UserDetailService;

/**
 * 批改列表
 * @author ihziluoh
 *
 */
class CorrectAction extends ApiBaseAction{

   public  function run(){
        $maintypeid=$this->requestParam('maintypeid',true);
        $lastid = $this->requestParam('lastid'); 
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'): 10;
        $uid=$this->_uid;
        //第一页返回用户能力模型
        if($lastid){
            $data['capacitymodels'] = [];
        }else{
            $data['capacitymodels']=$this->getCapacityModel($maintypeid,$uid);
        }
        $correct_list=[];
        //获取批改列表
        $correctids=CorrectService::getCorrectListByCatalogRedis($uid,$maintypeid,0,$lastid,$rn);
        foreach ($correctids as $key => $value) {
           $correct_list[]= CorrectService::getFullCorrectInfo($value,$uid);
        }
        //返回批改列表
        $data['correct_list']=$correct_list;
        //清除小红点
        CorrectService::clearRedCorrectNum($uid,0,$maintypeid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
    /**
     * 得到用户能力模型
     * @param  [type] $maintypeid [description]
     * @param  [type] $uid        [description]
     * @return [type]             [description]
     */
    private function getCapacityModel($maintypeid,$uid){
        $capacityModels = [];
        //登陆用户判断用户角色 未登录用户按照无能力模型处理
        if($uid>0){
            $userinfo=UserDetailService::getByUid($uid);
            //只有普通用户有能力模型数据
            if($userinfo['ukind']==0 && $userinfo['featureflag']!=1){
                $tmp = CapacityModelService::getUserCapacityModel($uid,$maintypeid);
                if($tmp){
                    $tmp = CapacityModelService::addInfoToModel($tmp);
                    $capacityModels[] = $tmp;
                }
            }
        }
        return $capacityModels;
    }
}