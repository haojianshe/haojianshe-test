<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseService;
use mis\service\CourseSectionService;
use mis\service\CourseSectionVideoService;
/**
 * 删除 审核 课程
 */
class DelAction extends MBaseAction
{	
	public $resource_id = 'operation_course';
	
    /**
     * 只支持post删除
     */
    public function run()
    {
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	//检查参数是否非法
    	$courseid = $request->post('courseid');
        $status = $request->post('status');
        if(!($status==2 || $status==3)){
            die('参数不正确');
        }
    	if(!$courseid || !is_numeric($courseid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = CourseService::findOne(['courseid' => $courseid]);
    	if($model){
    		$model->status =$status;
            //审核判断章节视频是否为空
            if(intval($status)==2){
                //获取章节
                $sections=CourseSectionService::find()->where(['courseid'=>$courseid])->andWhere(['status'=>1])->all();
                if(empty($sections)){
                        return $this->controller->outputMessage(['errno'=>1,'msg'=>'章节为空！']);
                    }
                foreach ($sections as $key => $value) {
                    //获取对应章节下视频
                    $sectionvideo=CourseSectionVideoService::find()->where(['sectionid'=>$value->sectionid])->andWhere(['status'=>1])->all();
                    //若视频为空返回提示
                    if(empty($sectionvideo)){
                        return $this->controller->outputMessage(['errno'=>1,'msg'=>$value->title.'章节视频为空！']);
                    }
                }
            }
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
