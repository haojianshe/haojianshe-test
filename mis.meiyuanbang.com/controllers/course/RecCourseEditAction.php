<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseRecommendService;
/**
 * 编辑
 */
class RecCourseEditAction extends MBaseAction
{
	public $resource_id = 'operation_course';
	
	
    public function run()
    {
        $request = Yii::$app->request;
        $isclose = false;
        $msg='';
        $recommendid=$request->get("recommendid");
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $courserecid = $request->get('courserecid');   
            if($courserecid){
                if(!is_numeric($courserecid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = CourseRecommendService::findOne(['courserecid' => $courserecid]);
                return $this->controller->render('reccourseedit', ['model' => $model,'msg'=>$msg,'recommendid'=>$recommendid]);
            }
            else{
                $model = new CourseRecommendService();
                return $this->controller->render('reccourseedit', ['model' => $model,'msg'=>$msg,'recommendid'=>$recommendid]);
            }
        }else{
            if($request->post('isedit')==1){
                //编辑
                $model =  CourseRecommendService::findOne(['courserecid' => $request->post('CourseRecommendService')['courserecid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            }else{
                //插入
                $model = new CourseRecommendService();
                $model->load($request->post());
                $model->recommendid=$request->get("recommendid");
                //$model->status = 3;
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
            return $this->controller->render('reccourseedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'recommendid'=>$recommendid]);
        }
    }
}
