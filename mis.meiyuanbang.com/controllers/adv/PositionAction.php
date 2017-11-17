<?php
namespace mis\controllers\adv;

use Yii;
use mis\components\MBaseAction;
use mis\service\AdvRecordService;
use common\service\DictdataService;

class PositionAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_adv';
  public function run()
    {	
    	$request = Yii::$app->request;
    	$search['adv_f_catalog_id'] = $request->get('adv_f_catalog_id')?$request->get('adv_f_catalog_id'):0; 
    	$search['adv_s_catalog_id'] = $request->get('adv_s_catalog_id')?$request->get('adv_s_catalog_id'):0; 
    	$search['adv_t_catalog_id'] = $request->get('adv_t_catalog_id')?$request->get('adv_t_catalog_id'):0; 
    	$search['pos_type'] = $request->get('pos_type')? $request->get('pos_type'):0; 
    	$search['stime'] = $request->get('stime')? $request->get('stime'):null; 
    	$search['etime'] = $request->get('etime')? $request->get('etime'):null; 
    	$search['provinceid'] = $request->get('provinceid'); 
        if(empty($search['provinceid']) && $search['provinceid']!=0){
            $search['provinceid']=NULL;
        }
    	$search['overtime'] = $request->get('overtime')?$request->get('overtime'):0;
    	$data=AdvRecordService::getByPage($search['pos_type'],$search['adv_f_catalog_id'],$search['adv_s_catalog_id'],$search['adv_t_catalog_id'],strtotime($search['stime']),strtotime($search['etime']),$search['provinceid'],$search['overtime']);
    	//所有分类
    	$data['catalog']=AdvRecordService::getCatalog();
    	//城市列表
    	$data['province']=DictdataService::getProvince();

        array_unshift($data['province'],array('provinceid'=>0,'provincename'=>'无地理位置'));
    	//搜索条件
    	$data['search']=$search;
       	return $this->controller->render('position',$data); 
    }
}
