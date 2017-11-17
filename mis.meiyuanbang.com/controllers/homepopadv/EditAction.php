<?php
namespace mis\controllers\homepopadv;

use Yii;
use mis\components\MBaseAction;
use mis\service\HomePopAdvService;
use common\service\DictdataService;

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
        $province=DictdataService::getProvince();
        $profess=DictdataService::getProfession();
        foreach ($province as $key => $value) {
            if($value['provinceid']==35){
                unset($province[$key]);
            }
        }
        $data['typemodel']=$typemodel;
        $data['province']=$province;
        $data['profess']=$profess;

        $advid = $request->get('advid');  
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $advid = $request->get('advid');   
            if($advid){
                if(!is_numeric($advid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = HomePopAdvService::findOne(['advid' => $advid]);
                $data['model']=$model;
                $data['msg']=$msg;
                return $this->controller->render('edit', $data);
            }
            else{
                $model = new HomePopAdvService();
                $data['model']=$model;
                $data['msg']=$msg;
                return $this->controller->render('edit', $data);
            }
        }else{
            if($request->post('isedit')==1){
                //编辑
                $model =  HomePopAdvService::findOne(['advid' => $request->post('HomePopAdvService')['advid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());

            }else{
                //插入
                $model = new HomePopAdvService();
                $model->load($request->post());
                $model->status = 0;
                //添加创建时间
            }
             $model->ctime = time();
             $model->btime = strtotime($model->btime);
             $model->etime = strtotime($model->etime);
             
             $provice=$request->post('HomePopAdvService')['provice'];
             $profess=$request->post('HomePopAdvService')['profess'];

             $model->provinceid=implode(",", $provice);
             $model->professionid=implode(",", $profess);
            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }else{
                var_dump($model->getErrors());
                $msg ='保存失败';
            }
            $data['model']=$model;
            $data['isclose']=$isclose;
            $data['msg']=$msg;
            return $this->controller->render('edit', $data);
        }
    }
}
