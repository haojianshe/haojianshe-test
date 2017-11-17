<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use common\service\dict\CourseDictDataService;

use mis\service\CourseRecommendCatalogService;
/**
 * 编辑
 */
class RecCatalogEditAction extends MBaseAction
{
	public $resource_id = 'operation_course';
	
	
    public function run()
    {
        $request = Yii::$app->request;
        $isclose = false;
         // 图片分类
        $config['imgmgr_level_1'] = CourseDictDataService::getCourseMainType();
        $config['imgmgr_level_2'] = CourseDictDataService::getCourseSubType();
        $msg='';
        if(!$request->isPost){
            //get访问，判断是edit还是add,返回不同界面
            $recommendid = $request->get('recommendid');   
            if($recommendid){
                if(!is_numeric($recommendid)){
                    die('非法输入');
                }
                //根据id取出数据
                $model = CourseRecommendCatalogService::findOne(['recommendid' => $recommendid]);
                return $this->controller->render('reccatalogedit', ['model' => $model,'msg'=>$msg, 'catalog' => $config]);
            }
            else{
                $model = new CourseRecommendCatalogService();
                return $this->controller->render('reccatalogedit', ['model' => $model,'msg'=>$msg, 'catalog' => $config]);
            }
        }else{
            if($request->post('isedit')==1){
                //编辑
                $model =  CourseRecommendCatalogService::findOne(['recommendid' => $request->post('CourseRecommendCatalogService')['recommendid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
                $find=CourseRecommendCatalogService::find()->where(['f_catalog_id'=>$model->f_catalog_id])->andWhere(['s_catalog_id'=>$model->s_catalog_id])->andWhere(['<>','recommendid',$model->recommendid])->one();
            }else{
                //插入
                $model = new CourseRecommendCatalogService();
                $model->load($request->post());
                //$model->status = 3;
                //添加创建时间
                $model->ctime = time();
                $find=CourseRecommendCatalogService::find()->where(['f_catalog_id'=>$model->f_catalog_id])->andWhere(['s_catalog_id'=>$model->s_catalog_id])->one();
            }
            if($find){
                $isclose = false;
                $msg ='分类已推荐，不能重复';
            }else{
               if($model->save()){
                    $isclose = true;
                    $msg ='保存成功';
                }
                else{
                    $msg ='保存失败';
                } 
            }
            return $this->controller->render('reccatalogedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose, 'catalog' => $config]);
        }
    }
}
