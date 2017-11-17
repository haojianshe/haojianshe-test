<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserCorrectService;
use api\service\UserDetailService;
use api\service\CorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 批改老师列表
 */
class TeacherListAction extends ApiBaseAction
{   
    public function run()
    {   
        //是否返回已经批改过的老师
        $has_correct=$this->requestParam('has_correct');
        //是否返回系统推荐列表
        $system_recommend=$this->requestParam('system_recommend');
        //筛选条件 /0/1/2/3 全部/素描/色彩/速写
        $type=$this->requestParam('type') ? $this->requestParam('type') :0; 
        $rn=$this->requestParam('rn') ? $this->requestParam('rn') :10;
        $lastid=$this->requestParam('lastid') ? $this->requestParam('lastid'): 0;
        //列表类型 0/1 批改老师列表/选择批改老师列表
        $listtype=$this->requestParam('listtype') ? $this->requestParam('listtype') : 0;
        switch ($type) {
            //0/1/2/3 全部/素描/色彩/速写
            case '1':
                 $key_type='isdrawing';
                break;
            case '2':
                $key_type='iscolor';
                break;
            case '3':
                $key_type='issketch';
                break;
            default:
                # code...
                break;
        }
    	$data_res = [];
        $data_default_all=UserCorrectService::getTeacherListRedis(); //$this->requestParam('lastid'),$rn
        //lastid 大于0 分页获取剩余所有数据
        if($lastid>0){
            $idx=array_search($lastid, $data_default_all);
            $data_default=array_splice($data_default_all, $idx+1,count($data_default_all));
        }else{
            //取所有数据
            $data_default=$data_default_all;
        }
        
       
        if($type>0){
            //v2_4_2 返回筛选对应分类
                foreach($data_default as $key => $value) {
                    if(count($data_res)<$rn){
                        $data_arr=UserCorrectService::getUserCorrectDetail($value);
                        //2.3.5之前listtype=1,不返回休息中的老师 ;2.3.5之后的版本listtype=0所有状态都返回
                        if($listtype==0 or $data_arr['status']==0){
                            //判断是否是对应分类的批改老师
                            if(UserCorrectService::IsCatalogCorrectTeacher($type,$data_arr)){
                                $data_res[]=array_merge(UserDetailService::getByUid($value),$data_arr);
                            }
                        }
                    }
                }
        }else{ 
            //老接口逻辑 返回全部
                foreach($data_default as $key => $value) {
                    if(count($data_res)<$rn){
                        $data_arr=UserCorrectService::getUserCorrectDetail($value);
                        //2.3.5之前listtype=1,不返回休息中的老师 ;2.3.5之后的版本listtype=0所有状态都返回
                        if($listtype==0 or $data_arr['status']==0){
                            $data_res[]=array_merge(UserDetailService::getByUid($value),$data_arr);
                        }
                    }

                }
        }
        
        //曾经批改过的老师列表 v2_4_2 app未取 暂时不用  未增加分类筛选
        if($has_correct && $listtype==1){
        	$data_res_has=[];
        	$data_default_has=CorrectService::getHasCorrectRedis($this->_uid);
            if(count($data_default_has)>0){
                foreach($data_default_has as $key => $value) {
                    $data_arr_has=UserCorrectService::getUserCorrectDetail($value);
                    //批改老师列表显示全部批改老师  选择批改老师列表只显示接受批改的老师
                    if($listtype==0 or $data_arr_has['status']==0 ){
                        //判断是否还是批改老师
                        if($data_arr_has){
                            $data_res_has[]=array_merge(UserDetailService::getByUid($value),$data_arr_has);
                        }
                    }
                } 
            }
            $data['has_correct']=$data_res_has;
        }else{
            $data['has_correct']=[];
        }
        //批改老师列表,暂时不赋值
        $data['system_recommend']=[];
        $data['default_correct']=$data_res;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}      
    