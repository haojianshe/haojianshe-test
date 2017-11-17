<?php
namespace mis\controllers\capacity;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\CapacityModelMaterialService;
use common\service\dict\CapacityModelDictDataService;

/**
 * 后台帖子列表页
 * 
 */
class IndexAction extends MBaseAction
{
    public $resource_id = 'operation_capacity_material';

	public function run()
    {
        $request = Yii::$app->request;
        $f_catalog_id=$request->get("f_catalog_id");
        $item_id=$request->get("item_id");
        $s_catalog_id=$request->get("s_catalog_id");
        //分页获取
        $model=CapacityModelMaterialService::getCapacityMaterialByPage($f_catalog_id,$s_catalog_id,$item_id);
        //返回搜索参数
        $model['search_arr']['f_catalog_id']=$request->get("f_catalog_id");
        $model['search_arr']['item_id']=$request->get("item_id");
        $model['search_arr']['s_catalog_id']=$request->get("s_catalog_id");
        //获取类型数组
        $maintype=CapacityModelDictDataService::getCorrectMainType();
        $subtype=CapacityModelDictDataService::getCorrectSubType();
        $captype=CapacityModelDictDataService::getCorrectScoreItem();
        foreach ($model['models'] as $key => $value) {
            $mainid=$model['models'][$key]['f_catalog_id'];
            $subid=$model['models'][$key]['s_catalog_id'];
            $itemid=$model['models'][$key]['item_id'];
            //增加转换成文字字段
            $model['models'][$key]['f_catalog']=$maintype[$mainid];
            $model['models'][$key]['s_catalog']=$subtype[$mainid][$subid];
            //循环查找能力分类
            foreach ($captype[$mainid] as $key1 => $value1) {
                if($value1["itemid"]== $itemid){
                    $model['models'][$key]['itemname']=$value1['itemname'];
                }
            }            
        }
        //返回所有分类
        $classtype_arr['maintype']=$maintype;
        $classtype_arr['subtype']=$subtype;
        $classtype_arr['captype']=$captype;
        $model['classtype']=json_encode($classtype_arr);
        
        return $this->controller->render("index",$model);

    }
}
