<?php
namespace mis\controllers\adv;

use Yii;
use mis\components\MBaseAction;
use mis\service\AdvResourceService;
use common\service\DictdataService;
use mis\service\AdvUserService;

class EditAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_adv';
  public function run()
    {
        
        $request = Yii::$app->request;
        $isclose = false;
        $msg='';

        //获取推荐类型数组
        $typemodel = DictdataService::getPosidHomeType();
        array_unshift($typemodel,['typeid'=>'','typename'=>'选择推荐类型']);

        $advuid = $request->get('advuid');  
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $advid = $request->get('advid');   
            if($advid){
                if(!is_numeric($advid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = AdvResourceService::findOne(['advid' => $advid]);
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'typemodel'=>$typemodel]);
            }
            else{
                $model = new AdvResourceService();
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'typemodel'=>$typemodel]);
            }
        }else{
            if($request->post('isedit')==1){
                //编辑
                $model =  AdvResourceService::findOne(['advid' => $request->post('AdvResourceService')['advid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());

            }else{
                //插入
                $model = new AdvResourceService();
                $model->load($request->post());
                $model->status = 2;
                $model->advuid=$advuid;
                //添加创建时间
                $model->ctime = time();
            }
            
            if($model->save()){
                if($request->post('isedit')!=1){
                    $advuser=AdvUserService::findOne(['advuid' => $advuid]);
                    $advuser->advcount=$advuser->advcount+1;
                    $advuser->save();
                }
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                var_dump($model->getErrors());
                $msg ='保存失败';
            }
            return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'typemodel'=>$typemodel]);
        }
    }
}
