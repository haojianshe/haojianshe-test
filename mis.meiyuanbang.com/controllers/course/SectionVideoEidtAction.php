<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseSectionVideoService;
use mis\service\CourseService;
/**
 * 编辑
 */
class SectionVideoEidtAction extends MBaseAction
{
	public $resource_id = 'operation_course';
	
	
    public function run()
    {
        $request = Yii::$app->request;
        $isclose = false;
        $msg='';
        $courseid = $request->get('courseid'); 
        $course=CourseService::findOne(['courseid' => $courseid]);  
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $coursevideoid = $request->get('coursevideoid');   
            if($coursevideoid){
                if(!is_numeric($coursevideoid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = CourseSectionVideoService::findOne(['coursevideoid' => $coursevideoid]);
                return $this->controller->render('sectionvideoedit', ['model' => $model,'msg'=>$msg,'course'=>$course]);
            }
            else{
                $model = new CourseSectionVideoService();
                return $this->controller->render('sectionvideoedit', ['model' => $model,'msg'=>$msg,'course'=>$course]);
            }
        }else{
            if($request->post('isedit')==1){
                //编辑
                $model =  CourseSectionVideoService::findOne(['coursevideoid' => $request->post('CourseSectionVideoService')['coursevideoid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
                $model->courseid = $request->get('courseid');
            }else{
                //插入
                $model = new CourseSectionVideoService();
                $model->load($request->post());
                $model->sectionid = $request->get('sectionid');
                $model->courseid = $request->get('courseid');
                $model->status=1;
                //添加创建时间
                $model->ctime = time();
            }
            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('sectionvideoedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'course'=>$course]);
        }
    }
}
