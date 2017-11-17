<?php
namespace mis\controllers\tag;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\TagsService;
/**
 * 标签编辑
 */
class TagEditAction extends MBaseAction
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
                $model = TagsService::findOne(['tagid' => $id]);
                return $this->controller->render('tagedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
            }
            else{
                //add
                $model = new TagsService();
                return $this->controller->render('tagedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
            }
        }
        else{
            if($request->post('isedit')==1){
                //update
                $model =  TagsService::findOne(['tagid' => $request->post('TagsService')['tagid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            }
            else{
                //insert
                $model = new TagsService();
                $model->load($request->post());
                $model->taggroupid=$request->get("taggroupid");
                $model->ctime=time();
                $model->status=1;
            }
            $findquery=TagsService::find()->where(['tag_name' => $model->tag_name])->andWhere(["status"=>1])->andWhere(["taggroupid"=> $model->taggroupid]);
            if($model->tagid){
                $findquery->andWhere(['<>','tagid',$model->tagid]);
            }
            $findname=$findquery->one();
            if($findname){
                $msg ='标签重名！';
                return $this->controller->render('tagedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
            }
            if($model->save()){
                $isclose = true;
                $msg ='保存成功';
            }
            else{
                $msg ='保存失败';
            }
            return $this->controller->render('tagedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
        }
    }
}
