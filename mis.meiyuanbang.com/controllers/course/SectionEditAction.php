<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseSectionService;
/**
 * 编辑
 */
class SectionEditAction extends MBaseAction
{
	public $resource_id = 'operation_course';
	
	
    public function run()
    {
        
        $request = Yii::$app->request;
        $isclose = false;
        $courseid = $request->get('courseid');   
        $msg='';
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $sectionid = $request->get('sectionid');   
            if($sectionid){
                if(!is_numeric($sectionid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = CourseSectionService::findOne(['sectionid' => $sectionid]);
                return $this->controller->render('sectionedit', ['model' => $model,'msg'=>$msg,]);
            }
            else{
                $model = new CourseSectionService();
                return $this->controller->render('sectionedit', ['model' => $model,'msg'=>$msg]);
            }
        }else{
            if($request->post('isedit')==1){
                //编辑
                $model =  CourseSectionService::findOne(['sectionid' => $request->post('CourseSectionService')['sectionid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());

            }else{
                //插入
                $model = new CourseSectionService();
                $model->load($request->post());
                $model->status = 1;
                $model->courseid=$courseid;
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
            return $this->controller->render('sectionedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
        }
    }
}
