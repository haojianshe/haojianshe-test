<?php
namespace  mis\controllers\video;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\VideoResourceService;
use common\service\DictdataService;

class EditAction extends MBaseAction
{ 
    public $resource_id = 'operation_video';
    public function run(){
        $request = Yii::$app->request;
         // 图片分类
        $config['imgmgr_level_1'] = DictdataService::getTweetMainType();
        $config['imgmgr_level_2'] =  DictdataService::getTweetSubType();
     
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
                $model = VideoResourceService::findOne(['videoid' => $id]);
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'catalog'=>$config]);
            }
            else{
                //add
                $model = new VideoResourceService();
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'catalog'=>$config]);
            } 
        }
        else{
            if($request->post('isedit')==1){
                //update
                $model =VideoResourceService::findOne(['videoid' => $request->post('VideoResourceService')['videoid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            }
            else{
                //insert
                $model = new VideoResourceService();
                $model->load($request->post());
                //todo 
                $model->status=1;
                $model->ctime=time();
                $model->save();
                $msg ='请继续上传视频';
                $isclose = false;
                return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'catalog'=>$config]);
            }

            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'catalog'=>$config]);
        }
    }
}