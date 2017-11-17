<?php
namespace  mis\controllers\tag;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\TagGroupService;
use common\service\DictdataService;

/**
*  标签分组列表
*/
class GroupListAction extends MBaseAction
{
    public $resource_id = 'operation_tag';

    public function run()
    { 
    	$request = Yii::$app->request;
    	//一二 级分类
        $f_catalog_id=$request->get("f_catalog_id");
        $s_catalog_id=$request->get("s_catalog_id");
        $data = TagGroupService::getTagGrounpByPage($f_catalog_id,$s_catalog_id);
        //处理一二级分类
        foreach ($data['models'] as $key => $value) {
        	$data['models'][$key]['f_catalog']=DictdataService::getTweetMainTypeById($data['models'][$key]['f_catalog_id']);
        	$data['models'][$key]['s_catalog']=DictdataService::getTweetSubTypeById($data['models'][$key]['f_catalog_id'],$data['models'][$key]['s_catalog_id']);
        }
         //返回搜索参数
        $data['search_arr']['f_catalog_id']=$f_catalog_id;
        $data['search_arr']['s_catalog_id']=$s_catalog_id;
        //获取类型数组
        $maintype=DictdataService::getTweetMainType();
        $subtype=DictdataService::getTweetSubType();
 		//返回所有分类
        $classtype_arr['maintype']=$maintype;
        $classtype_arr['subtype']=$subtype;
        
        $data['classtype']=json_encode($classtype_arr);
        return $this->controller->render('grouplist', $data);
    }
}