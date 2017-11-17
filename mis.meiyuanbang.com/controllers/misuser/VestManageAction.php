<?php
namespace mis\controllers\misuser;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\MisUserVestService;

/**
 * 设置用户马甲
 * 
 */
class VestManageAction extends MBaseAction
{
    public $resource_id = 'admin';

    public function run()
    {
        $request = Yii::$app->request;
        $mis_userid=$request->get('mis_userid');
        if(!$request->isPost){        	
             $model=MisUserVestService::findOne(['mis_userid'=>$mis_userid]);
             if(!$model){
                $model=new MisUserVestService();
                $model->mis_userid=$mis_userid;
             }
             return $this->controller->render('misuservest', ['model'=>$model,'isclose'=>false,'msg'=>'']);
        }else{
            $model=MisUserVestService::findOne(['mis_userid' => $request->post('MisUserVestService')['mis_userid']]);
            if(!$model){
                //第一次保存
        		$model=new MisUserVestService();
                $model->mis_userid=$mis_userid;
            }
            else{
            	$model->IsNewRecord = false;
            }
            $model->uids=$request->post('MisUserVestService')['uids'];
            $model->save();
            return $this->controller->render('misuservest', ['model'=>$model,'isclose'=>true,'msg'=>'保存成功']);
        }
    }
}
