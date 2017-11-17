<?php
namespace api\modules\v3\controllers\course;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CourseService;
use common\service\dict\CourseDictDataService;
/**
 * 课程分类
 * @author ihziluoh
 *
 */
class CatalogAction extends ApiBaseAction{
   public  function run(){
        //获取一二级分类
        $f_catalog=CourseDictDataService::getCourseMainType();
        $s_catalog=CourseDictDataService::getCourseSubType();

        $ret_catalog=[];
        //处理返回数据格式
        foreach ($f_catalog as $key => $value) {
           $f_catalog_item['id']=$key;
           $f_catalog_item['name']=$value;
           foreach ($s_catalog[$key] as $key1 => $value1) {
              $s_catalog_item['id']=$key1;
              $s_catalog_item['name']=$value1;
              $ret_s_catalog[]=$s_catalog_item;
           }
           $f_catalog_item['s_catalog']=$ret_s_catalog;
           unset($ret_s_catalog);
           $ret_catalog[]=$f_catalog_item;
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret_catalog);        
    }
}