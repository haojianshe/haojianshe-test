<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use mis\service\LessonService;
use common\service\DictdataService;
use common\models\myb\Lesson;

/**
 * 精讲添加和修改页面
 */
class EditAction extends MBaseAction
{
	public $resource_id = 'operation_lesson';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	$usermodel = \Yii::$app->user->getIdentity();
    	$isclose = false;
    	 
    	if(!$request->isPost){
    		//get访问，判断是edit还是add,返回不同界面
    		$lessonid = $request->get('lessonid');
    		if($lessonid){
    			//edit
    			if(!is_numeric($lessonid)){
    				die('非法输入');
    			}
    		}
    		else{
    			//add
    			$lessonid = 0;
    		}
    		$ret =$this->getRetModel($lessonid);
    		return $this->controller->render('edit', $ret);
    	}
    	else{
    		if($request->post('isedit')==1){
    			$lessonid = $request->post('LessonService')['lessonid'];
    			$model =  LessonService::findOne(['lessonid' => $lessonid]);
    			$model->load($request->post());
    		}
    		else{
    			//insert
    			$model = new LessonService();
    			$model->load($request->post());
    			$model->ctime = time();
    			$model->username = $usermodel->mis_realname;
    		}
    		//用户提交
    		if($model->save()){
    			$ret = $this->getRetModel($model->lessonid);
    			$ret['msg'] = '保存成功';
    			$ret['lessonid'] = $model->lessonid;
    			$ret['isclose'] = true;
    		}
    		else{
    			$ret = $this->getRetModel($model->lessonid);
    			$ret['msg'] = '保存失败';
    			$ret['model'] = $model;
    		}    		
    		return $this->controller->render('edit', $ret);
    	}
    }

    /**
     * lessonid 为0代表新建，为1是编辑
     * @param unknown $lessonid
     * @return Ambigous <multitype:number , multitype:multitype:number string  >
     */
    private function getRetModel($lessonid){
    	//获取主类型,添加未选择选项
    	$maintypemodel = DictdataService::getLessonMainType(); 
    	array_unshift($maintypemodel,['maintypeid'=>0,'maintypename'=>'选择主类型']);
    	$ret['maintypemodel'] = $maintypemodel;
    	//分类型
    	$subtypemodel = [];
    	if($lessonid == 0){
    		//获取精讲详细信息
    		$ret['model'] = new LessonService();
    	}
    	else{
    		$model = LessonService::findOne(['lessonid'=>$lessonid]);
    		$ret['model'] = $model;
    		//根据主类型选择分类型
    		if($model->subtype){
    			$subtypemodel = DictdataService::getLessonSubType($model->maintype);
    		}
    	}
    	array_unshift($subtypemodel,['subtypeid'=>'','subtypename'=>'选择分类型']);
    	$ret['subtypemodel'] = $subtypemodel;
    	return $ret;
    }
}
