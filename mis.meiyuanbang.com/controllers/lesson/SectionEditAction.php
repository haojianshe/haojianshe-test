<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use mis\service\LessonService;
use common\service\DictdataService;
use mis\service\LessonSectionService;
use common\models\myb\SoundResource;
/**
 * 精讲添加和修改页面
 */
class SectionEditAction extends MBaseAction
{
	public $resource_id = 'operation_lesson';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	$usermodel = \Yii::$app->user->getIdentity();
    	 $sound=[];
    	if(!$request->isPost){
    		//判断lessonid
    		$lessonid =  $request->get('lessonid');
    		if(($lessonid && !is_numeric($lessonid)) || !$lessonid) {
    			die('参数错误!');
    		}
    		//get访问，判断是edit还是add,返回不同界面
    		$sectionid = $request->get('sectionid');
    		if($sectionid){
    			//edit
    			if(!is_numeric($sectionid)){
    				die('非法输入');
    			}
    			$model = LessonSectionService::findOne(['sectionid'=>$sectionid]);

    		}
    		else{
    			//add
    			$model = new LessonSectionService();
    			$model->lessonid = $lessonid;
    			$model->listorder = LessonSectionService::getMaxListorder($lessonid)+1;
    		}
    		return $this->controller->render('sectionedit', ['model'=>$model,'sound'=>$sound]);
    	}
    	else{
    		if($request->post('isedit')==1){
    			$sectionid = $request->post('LessonSectionService')['sectionid'];
    			$model =  LessonSectionService::findOne(['sectionid' => $sectionid]);
    			$model->load($request->post());
    		}
    		else{
    			//insert
    			$model = new LessonSectionService();
    			$model->load($request->post());
    			$model->ctime = time();
    		}
    		//用户提交
    		if($model->save()){
    			$ret['msg'] = '保存成功';
    			$ret['lessonid'] = $model->lessonid;
    			$ret['model'] = $model;
    			$ret['isclose'] = true;
    		}
    		else{
    			$ret['msg'] = '保存失败';
    			$ret['model'] = $model;
          
            }           
    		return $this->controller->render('sectionedit', $ret);
    	}
    }
    
    /**
     * 检查参数
     */
    private function checkParam(){
    	if(!$request->isPost){
    		//判断lessonid
    		$lessonid =  $request->get('sectionid');
    		
    		$sectionid = $request->get('sectionid');
    		if($sectionid){
    			//edit
    			if(!is_numeric($sectionid)){
    				die('非法输入');
    			}
    			$model = LessonSectionService::findOne(['sectionid'=>$sectionid]);
    		}
    		else{
    			//add
    			$model = new LessonSectionService();
    		}
    	}
    	
    }
}
