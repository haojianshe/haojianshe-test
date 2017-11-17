<?php
namespace api\modules\v2_3_7\controllers\publishing;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\dict\BookDictDataService;
/**
 * 美院帮推荐图书分类
 */
class MybRecommendBookClassAction extends ApiBaseAction
{
    public function run()
    {   
      $f_catalog=BookDictDataService::getBookMainType();
      $f_one["f_catalog_id"]=0;
      $f_one["f_catalog_name"]="全部";
      $f_catalogs[]=$f_one;
      foreach ($f_catalog as $key => $value) {
         $f_one["f_catalog_id"]=$key;
         $f_one["f_catalog_name"]=$value;
         $f_catalogs[]=$f_one;
      }
      $data['f_catalog']= $f_catalogs;
      //分享信息
      $share['title']="美院帮书籍推荐";
      $share['desc']="帮叔挑选海量优质书籍，助亲快速提高绘画技巧、有效提升绘画能力";
      $share['img']="";
      $share['url']=Yii::$app->params['sharehost']."/publishing/myb_recommend_books";
      $data['share']=$share;
		return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
