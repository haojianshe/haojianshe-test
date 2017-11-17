<?php
namespace mis\controllers\tag;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\TagGroupService;
/**
 * 标签分组编辑
 */
class GroupEditAction extends MBaseAction
{ 
    public $resource_id = 'operation_tag';
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
                $model = TagGroupService::findOne(['taggroupid' => $id]);
                return $this->controller->render('groupedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
            }
            else{
                //add
                $model = new TagGroupService();
                return $this->controller->render('groupedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
            } 
        }
        else{
            if($request->post('isedit')==1){
                //update
                $model =TagGroupService::findOne(['taggroupid' => $request->post('TagGroupService')['taggroupid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            }
            else{
                //insert
                $model = new TagGroupService();
                $model->load($request->post());
                $model->f_catalog_id=$request->get("f_catalog_id");
                $model->s_catalog_id=$request->get("s_catalog_id");
                $model->status=1;
                $model->ctime=time();
            }

            $findquery=TagGroupService::find()->where(['f_catalog_id' => $model->f_catalog_id])->andWhere(["tag_group_name"=> $model->tag_group_name])->andWhere(["s_catalog_id"=> $model->s_catalog_id])->andWhere(["status"=>1]);
            if($model->taggroupid){
                $findquery->andWhere(['<>','taggroupid',$model->taggroupid]);
            }
            $findname=$findquery->one();
            if($findname){
                $msg ='标签组重名！';
                return $this->controller->render('groupedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
             }
            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('groupedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
        }
    }
}
