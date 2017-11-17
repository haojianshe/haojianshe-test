<?php
namespace mis\controllers\material;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\MatreialSubjectService;
use common\service\AliOssService;
use mis\service\ResourceService;
use common\service\CommonFuncService;
/**
 * 专题编辑
 */
class EditAction extends MBaseAction
{ public function run()
    {
        $request = Yii::$app->request;
        $msg='';
        $isclose = false;
       
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $subjectid = $request->get('subjectid');   
            if($subjectid){
                //edit
                if(!is_numeric($subjectid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = MatreialSubjectService::findOne(['subjectid' => $subjectid]);
                //图片信息
                $resources = ResourceService::findAll(['rid' => explode(',', $model->rids)]);
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
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'usersinfo'=>"",'imglist'=>$resources]);
            }
            else{
                //add
                $model = new MatreialSubjectService();
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'usersinfo'=>"",'imglist'=>'']);
            } 
        }
        else{
            if($request->post('isedit')==1){
                //update
                $model =  MatreialSubjectService::findOne(['subjectid' => $request->post('MatreialSubjectService')['subjectid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
                $model->ctime = strtotime($request->post('MatreialSubjectService')['ctime']);
            }
            else{
                //insert
                $model = new MatreialSubjectService();
                $model->load($request->post());
                $model->ctime=time();
                 $model->status=2;
                
            }
            //格式化图片
            $img_infohw=AliOssService::getFileHW($model->picurl);
            $img_info['n']['h']=$img_infohw['height'];
            $img_info['n']['w']=$img_infohw['width'];
            $img_info['n']['url']=$model->picurl;
            $model->picurl=json_encode($img_info);
           
            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'usersinfo'=>"",'imglist'=>""]);
        }
    }
}
