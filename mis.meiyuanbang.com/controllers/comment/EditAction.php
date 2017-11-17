<?php
namespace mis\controllers\comment;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\CommentService;
use mis\service\UserService;
/**
 * 增加评论 
 */
class EditAction extends MBaseAction
{
    
    public function run()
    {
    	$request = Yii::$app->request;
    	$msg='';
    	$isclose = false;
    	if(!$request->isPost){
            $model= new CommentService;
            $model->ctime=time();
            $model->subjecttype=$request->get('subjecttype');
            $model->subjectid=$request->get('subjectid');
    		return $this->controller->render('aedit', ['model' => $model,'data'=>$data]);
    	}else{
            $model= new CommentService;
            $model->load($request->post());
            $model->ctime=strtotime($model->ctime);
            $model->ctype=0;
            $model->reply_uid=0;
            $model->reply_cid=0;
            //判断评论用户是否存在
            $user=UserService::findOne(['uid'=>$model->uid]);
            if(!$user){
                $msg ='评论用户不存在！';
                $isclose = false;
                return $this->controller->render('aedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'data'=>$data]);
            }
    		if($model->save()){
                //根据评论类型处理缓存
                CommentService::incCmtCountRedis($model->subjecttype,$model->subjectid);
    			$isclose = true;
    			$msg ='保存成功';
    		}else{
                //var_dump($model->getErrors());exit;
    			$msg ='保存失败';
    		}
    		return $this->controller->render('aedit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose,'data'=>$data]);
    	}
    }
}
