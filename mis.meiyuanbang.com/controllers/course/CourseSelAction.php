<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseService;
use mis\service\CourseRecommendCatalogService;
use mis\service\CourseRecommendService;


/**
 * 选择视频
 */
class CourseSelAction extends MBaseAction
{
    public $resource_id = 'operation_course';
    
    public function run()
    {
        $request = Yii::$app->request;
        $courseid=$request->get("courseid");
        $recommendid=$request->get("recommendid");
        $reccatalog=CourseRecommendCatalogService::findOne(['recommendid'=>$recommendid]);
        //视频列表
        $courseids=CourseRecommendService::getRecommendCourseIds($recommendid);

        $data =  CourseService::getByPage($reccatalog->f_catalog_id,$reccatalog->s_catalog_id,2);
        $data['courseid']=$courseid;
        $data['courseids']=$courseids;
        return $this->controller->render('coursesel',$data);
    }
}
