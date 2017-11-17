<?php
namespace mis\controllers\adv;

use Yii;
use mis\components\MBaseAction;
use mis\service\AdvUserService;
class UserEditAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_adv';
  public function run()
    {
        $usermodel = \Yii::$app->user->getIdentity();
        $request = Yii::$app->request;
        $isclose = false;
        $msg='';
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $advuid = $request->get('advuid');   
            if($advuid){
                if(!is_numeric($advuid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = AdvUserService::findOne(['advuid' => $advuid]);
                return $this->controller->render('useredit', ['model' => $model,'msg'=>$msg,]);
            }
            else{
                $model = new AdvUserService();
                return $this->controller->render('useredit', ['model' => $model,'msg'=>$msg]);
            }
        }else{
            if($request->post('isedit')==1){
                //编辑
                $model =  AdvUserService::findOne(['advuid' => $request->post('AdvUserService')['advuid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());

            }else{
                //插入
                $model = new AdvUserService();
                $model->load($request->post());
                $model->status = 2;
                //添加创建时间
                $model->ctime = time();

            }
            $model->username = $usermodel->mis_realname;
            if($model->save()){

                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('useredit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
        }
    }
}
