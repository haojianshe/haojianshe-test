<?php
namespace mis\controllers\dkactivity;

use Yii;
use mis\components\MBaseAction;

use mis\service\DkCorrectService;


/**
 * 增加点赞数
 */
class EditZanAction extends MBaseAction
{
	public $resource_id = 'operation_activity';
	
	
    public function run()
    {
        $request = Yii::$app->request;
        $isclose = false;
        $msg='';

        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $dkcorrectid = $request->get('dkcorrectid');   
            if($dkcorrectid){
                //edit
                
                //根据id取出数据
                $model = DkCorrectService::findOne(['dkcorrectid' => $dkcorrectid]);
                if(!is_numeric($dkcorrectid)){
                    $model = new DkCorrectService();
                }
                return $this->controller->render('editzan', ['model' => $model,'msg'=>$msg]);
            }
            else{
              /*  //add
                $model = new DkCorrectService();
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);*/
            }
        }
        else{

            if($request->post('isedit')==1){
                $model =  DkCorrectService::findOne(['dkcorrectid' => $request->post('DkCorrectService')['dkcorrectid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
               
            }else{
                $model =  new DkCorrectService();
                $model->load($request->post());
                $model->add_zan_time = strtotime($model->add_zan_time); 
                $res=DkCorrectService::updateAll(["add_zan_time"=>$model->add_zan_time,"add_zan_count"=>$model->add_zan_count],['in','dkcorrectid',explode(",", $request->get('dkcorrectid'))]);
                return $this->controller->render('editzan', ['model' => $model,'msg'=>'保存成功','isclose'=>true]);
            }
            $model->add_zan_time = strtotime($model->add_zan_time); 
            if($model->save()){              
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('editzan', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
        }
    
    	
    }
    
}
