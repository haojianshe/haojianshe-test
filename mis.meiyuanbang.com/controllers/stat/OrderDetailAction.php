<?php
namespace mis\controllers\stat;

use Yii;
use mis\components\MBaseAction;
use mis\service\OrdergoodsService;
use common\models\myb\CourseSectionVideo;
use common\models\myb\VideoResource;
/**
 * 订单详情
 */
class OrderDetailAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_stat';
  public function run()
    {
		$request = Yii::$app->request;
		$orderid=$request->get("orderid");
	   	$data=OrdergoodsService::getByPage($orderid);

	   	$models=$data['models'];
	   	foreach ($models as $key => $value) {
	   		// /订单类型 :1直播  2点播 3画室班型报名方式
	   		switch ($value['subjecttype']) {
	   			case 2:
	   				$models[$key]['course_section']=self::getCourseSectionVideoInfo($value['subjectid']);
	   				break;
	   			
	   			default:
	   				$models[$key]['course']=[];
	   				break;
	   		}
	   	}	

	   	$data['models']=$models;
       	return $this->controller->render('orderdetail',$data); 
    }

    private function getCourseSectionVideoInfo($coursevideoid){
    	$secvideoinfo=CourseSectionVideo::find()->where(['coursevideoid'=>$coursevideoid])->asArray()->one();
	   	$secvideoinfo['video']=VideoResource::find()->where(['videoid'=>$secvideoinfo['videoid']])->asArray()->one();
	   	return $secvideoinfo;
    }
}
