<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use mis\service\LessonDescService;
use mis\service\SoundResourceService;
use common\service\DictdataService;

/**
 * 修改跟着画描述
 */
class DescEditAction extends MBaseAction
{
	public $resource_id = 'operation_lesson';
	
    public function run()
    {
        $request = Yii::$app->request;
        $msg='';
        $isclose = false;
        $sound = [];
        $lessonid = $request->get('lessonid');   

        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $lessondescid = $request->get('lessondescid');   
            if($lessondescid){
                //edit
                if(!is_numeric($lessondescid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = LessonDescService::find()->where(['lessondescid' =>$lessondescid])->one();
                $sound=SoundResourceService::find()->where(['soundid'=>$model->soundid])->asArray()->one();
                return $this->controller->render('descedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,"sound"=> $sound]);
            }
            else{
                //add
                $model = new LessonDescService();
                return $this->controller->render('descedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,"sound"=> $sound]);
            } 
        }
        else{
            if($request->post('isedit')==1){
                //update
                $model =LessonDescService::findOne(['lessondescid' => $request->post('LessonDescService')['lessondescid']]);
                $sound=SoundResourceService::find()->where(['soundid'=>$model->soundid])->asArray()->one();

                $model->IsNewRecord = false;
                $model->load($request->post());
            }
            else{
                //insert
                $model = new LessonDescService();
                $model->load($request->post());
                $model->lessonid=$lessonid;
                //todo 
                $model->save();
                $msg ='保存成功';
                $isclose = true;
                $sound=SoundResourceService::find()->where(['soundid'=>$model->soundid])->asArray()->one();

                return $this->controller->render('descedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,"sound"=> $sound]);
            }
            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('descedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,"sound"=> $sound]);
        }
    }
}
