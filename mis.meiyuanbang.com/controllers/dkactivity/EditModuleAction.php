<?php
namespace mis\controllers\dkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkModulesService;
use mis\service\ResourceService;
use common\service\CommonFuncService;

/**
 * 编辑大咖改画模块
 */
class EditModuleAction extends MBaseAction
{
	public $resource_id = 'operation_activity';
	
	
    public function run()
    {
        $request = Yii::$app->request;
        $isclose = false;
        $msg='';
        $activityid = $request->get('activityid');
        $modulesid = $request->get('modulesid');  
        $type = $request->get('type')?$request->get('type'):1;  
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            
            if($modulesid){
                //edit
                if(!is_numeric($modulesid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = DkModulesService::findOne(['modulesid' => $modulesid]);
                //图片信息
                if($model->type==2){
                    $resources = ResourceService::findAll(['rid' => explode(',', $model->content)]);
                    //为批改增加不同格式图片大小
                    foreach ($resources as $k1=>$v1){ 
                         $arrtmp['rid']= $v1["rid"];                
                        //为批改增加不同格式图片大小
                        $arrtmp = json_decode($v1['img'], true);
                        if(empty($arrtmp['l'])){
                            $arrtmp['l'] = CommonFuncService::getPicByType($arrtmp['n'], 'l');
                        }
                        if(empty($arrtmp['s'])){
                            $arrtmp['s'] = CommonFuncService::getPicByType($arrtmp['n'], 's');
                        }
                        if(empty($arrtmp['t'])){
                            $arrtmp['t'] = CommonFuncService::getPicByType($arrtmp['n'], 't');
                        }
                        $resources[$k1]['img'] =  json_encode($arrtmp);
                    }
                }else{
                    $resources =[];
                }
                

                return $this->controller->render('editmodule', ['model' => $model,'msg'=>$msg,'imglist'=>$resources]);
            }
            else{
                //add
                $model = new DkModulesService();
                $model->type=$type;
                return $this->controller->render('editmodule', ['model' => $model,'msg'=>$msg,'imglist'=>[]]);
            }
        }
        else{

            if($request->post('isedit')==1){

                //update
                $model =  DkModulesService::findOne(['modulesid' => $request->post('DkModulesService')['modulesid']]);
                $model->IsNewRecord = false; 
                //用于判断转素材推送
                $model->load($request->post());
            }else{
                //insert
                $model = new DkModulesService();
                $model->load($request->post());
                $model->status=1;
            }
            if($type==3){
                    $arr['uu'] = $request->post('uu');
                    $arr['vu'] = $request->post('vu');
                    $model->content=json_encode($arr);
            }
           $model->activityid=$activityid;
           $model->type=$type;
            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }else{

                $msg ='保存失败';
            }
            return $this->controller->render('editmodule', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'imglist'=>[]]);
        }
    	
    }
    
}
