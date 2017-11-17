<?php
namespace  mis\controllers\holidayicons;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\HolidayIconsService;
use common\service\DictdataService;

class EditAction extends MBaseAction
{ 
    public $resource_id = 'operation_icons';
    public function run(){
        $request = Yii::$app->request;
        $msg='';
        $isclose = false;
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $id = $request->get('id');   
            if($id){
                //edit
                if(!is_numeric($id)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = HolidayIconsService::findOne(['iconsid' => $id]);
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
            }
            else{
                //add
                $model = new HolidayIconsService();
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
            } 
        }
        else{
            if($request->post('isedit')==1){
                //update
                $model =HolidayIconsService::findOne(['iconsid' => $request->post('HolidayIconsService')['iconsid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            }
            else{
                //insert
                $model = new HolidayIconsService();
                $model->load($request->post());
                //todo 
                $model->status=1;
                $model->ctime=time();
                $model->save();
                $msg ='保存成功';
                $isclose = true;
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
            }
           
            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
        }
    }
}