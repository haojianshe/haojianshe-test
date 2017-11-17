<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoResourceService;
use mis\service\UserService;
use common\models\myb\UserCorrect;

/**
 * 
 */
class VideoSelAction extends MBaseAction
{
    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_course';
    
    public function run()
    {
        $request = Yii::$app->request;
        $videoid = $request->get('videoid');
        $video_type=$request->get("video_type");
        $desc=$request->get("desc");
        $data =  VideoResourceService::getDataByPage($video_type,$desc,"sel");
        $data['videoid']= $videoid;  
        $data['desc']=$desc;
        $data['video_type']=$video_type;
        return $this->controller->render('videosel',$data);
    }
}
