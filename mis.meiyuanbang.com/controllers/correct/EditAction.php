<?php
namespace mis\controllers\tweet;
use Yii;
use yii\base\Action;
use mis\service\TweetService;
use mis\components\MBaseAction;
use common\service\DictdataService;
use mis\service\LessonService;
/**
 * 编辑帖子 
 */
class EditAction extends MBaseAction
{
    /**
    *帖子编辑
     */
    
    public function run()
    {
    	$request = Yii::$app->request;
        // 图片分类
        $config['imgmgr_level_1'] = DictdataService::getTweetMainType();
        $config['imgmgr_level_2'] =  DictdataService::getTweetSubType();
    	$msg='';
    	$isclose = false;
    	
    	if(!$request->isPost){
    		//get访问，判断是edit还是add,返回不同界面
    		$tid = $request->get('tid');   
    		if($tid){
    			//edit
    			if(!is_numeric($tid)){
    				die('非法输入');
    			}
    			//根据id取出数据
    			$model = TweetService::findOne(['tid' => $tid]);
    			return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'catalog'=>$config]);
    		}
    		else{
    			/*//add
    			$model = new TweetService();
    			return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'catalog'=>$config]);*/
    		}
    	}
    	else{
    		if($request->post('isedit')==1){
    			//update
    			$model =  TweetService::findOne(['tid' => $request->post('TweetService')['tid']]);
    			$model->IsNewRecord = false;
                //用于判断转素材推送
                if($model->type!=1){
                    $mtype=true;
                }else{
                    $mtype=false;
                } 
                //用于判断推荐推送
                if(empty($model->lessonid)){
                    $lessontype=true;
                }else{
                    $lessontype=false;
                }
    			$model->load($request->post());
               
                if($model->type==1 && $mtype){
                    $mtype=true;
                    TweetService::tweetToMaterialPushMsg(1,$model->uid,$request->post('TweetService')['tid']);
                }

    			//添加一级和二级分类id
    			$model->f_catalog_id = null;
    			$model->s_catalog_id = null;
    			if($model->f_catalog){
    				$model->f_catalog_id = DictdataService::getTweetMainTypeIdByName($model->f_catalog);
    				if($model->s_catalog){
    					$model->s_catalog_id = DictdataService::getTweetSubTypeIdByName($model->f_catalog_id, $model->s_catalog);
    				}    				
    			}    			
    		}
    		else{
    			/*//insert
    			$model = new TweetService();
    			$model->load($request->post());
    			//添加新用户,密码md5加密
    			$model->title = $model->title;*/
    		}
    		//用户提交
            $model->lessonid=$request->post('TweetService')['lessonid'];

            if(!empty($model->lessonid)){
                 $lessoninfo=LessonService::findOne(['lessonid'=>$model->lessonid,'status'=>0]);
                 if(empty($lessoninfo)){
                    die('推荐步骤图不存在 或已被删除');
                 }
                 if ($lessontype) {
                     TweetService::tweetRecLessonPushMsg(1,$model->uid,$request->post('TweetService')['tid']);
                 }
                
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
