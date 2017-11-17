<?php
namespace api\modules\v3_0_2\controllers\adv;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\models\myb\AdvResource;
use common\service\DictdataService;
use common\models\myb\AdvRecordPlace;

/**
 * 得到广告
 * @author ihziluoh
 *
 */
class GetAction extends ApiBaseAction{
   public  function run(){
    
        $pos_type=$this->requestParam('pos_type',true);//    1=>banner,2=>素材
        $province=$this->requestParam('province');//    省
        $ret=[];
        //判断无地理位置
        if($province){
            $provinceid=DictdataService::getUserProvinceByName($province);
        }else{
            $provinceid=0;
        }
        switch ($pos_type) {
            case 1:
                $f_catalog_id=$this->requestParam('f_catalog_id',true);//    对应分类一级分类
                $s_catalog_id=$this->requestParam('s_catalog_id',true);//    对应广告分类二级分类
                
                $ret=AdvRecordPlace::find()->select("a.*,b.*,c.*")->alias('a')->innerjoin("myb_adv_record as b",'a.advrecid=b.advrecid')->innerjoin("myb_adv_resource as c",'c.advid=b.advid')->where(['a.status'=>1])->andWhere(['a.provinceid'=>$provinceid])->andWhere(['adv_f_catalog_id'=>$f_catalog_id])->andWhere(['adv_s_catalog_id'=>$s_catalog_id])->andWhere(["<","b.stime",time()])->andWhere([">","b.etime",time()])->orderBy("b.sortid asc")->andWhere(['b.pos_type'=>$pos_type])->asArray()->all();
                //兼容321版本错误
                if($f_catalog_id==5 && $s_catalog_id==110){
                	$com_version=$this->requestParam('com_version');
                	$devicetype=$this->requestParam('devicetype');
                	if($devicetype && $com_version && $com_version=='321' && $devicetype=='ios'){
                		$ret =[];
                	}	
                }                
                break;
            case 2:
                $f_catalog_id=$this->requestParam('f_catalog_id',true);//    对应分类一级分类
                $s_catalog_id=$this->requestParam('s_catalog_id');//    对应广告分类二级分类
                $t_catalog_id=$this->requestParam('t_catalog_id');//    对应广告分类三级分类

                $ret=AdvRecordPlace::find()->select("a.*,b.*,c.*")->alias('a')->innerjoin("myb_adv_record as b",'a.advrecid=b.advrecid')->innerjoin("myb_adv_resource as c",'c.advid=b.advid')->where(['a.status'=>1])->andWhere(['a.provinceid'=>$provinceid])->andWhere(['adv_f_catalog_id'=>$f_catalog_id])->andWhere(['adv_s_catalog_id'=>$s_catalog_id])->andWhere(['adv_t_catalog_id'=>$t_catalog_id])->andWhere(["<","b.stime",time()])->andWhere([">","b.etime",time()])->andWhere(['b.pos_type'=>$pos_type])->asArray()->all();
                break;
            default:
                break;
        }
        
        
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}