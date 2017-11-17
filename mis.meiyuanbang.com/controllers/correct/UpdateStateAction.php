<?php
namespace mis\controllers\correct;

use Yii;
use mis\components\MBaseAction;
use mis\service\TweetService;
use api\service\CorrectService;
use api\service\UserCorrectService;
use mis\service\CapacityModelService;
/**
 *删除批改类型帖子
 */
class UpdateStateAction extends MBaseAction
{ 
    /**
     * 只支持post删除
     */
    public function run()
    { 
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	//检查参数是否非法
    	$tid = $request->post('tid');
    	if(!$tid || !is_numeric($tid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = TweetService::findOne(['tid' => $tid]);
        if($model){
            //更改批改状态
            $correct=CorrectService::findOne(['correctid' => $model->correctid]);
            $correct->status=2;
            $correct->save();
            //更改批改老师待批改数
            if($model->type==4){
                $user_correct=UserCorrectService::findOne(['uid'=>$correct->teacheruid]);
                if($user_correct->correctnum>0){
                    //减已批改数
                    $user_correct->correctnum=$user_correct->correctnum-1;
                }else{
                    $user_correct->correctnum=0;
                }
                $user_correct_res=$user_correct->save(); 
            }else if($model->type==3){
                $user_correct = UserCorrectService::findOne(['uid'=>$correct->teacheruid]);
                 if($user_correct->queuenum>0){
                    //减待批改数
                    $user_correct->queuenum=$user_correct->queuenum-1;
                }else{
                    $user_correct->queuenum=0;
                }
                $user_correct->save();
            }
            //更改帖子状态 删除或改为普通作品
            if($request->post('type')){
                $model->type=$request->post('type'); 
                $model->is_del=0;     
            }else{
                $model->is_del=1;  
            }
            $ret = $model->save();
            if($ret){
            	//删除或者改作品成功后，判断此类型是否是最后一个求批改，如果是的话要删除对应的能力模型
            	$correctCount=TweetService::getCorrectedCount($model->uid, $model->f_catalog_id);
            	if($correctCount==0){
            		//一条批改也没有则删除对应的能力模型
            		CapacityModelService::deleteUserCapacityModel($model->uid, $model->f_catalog_id);
            	}
            	return $this->controller->outputMessage(['errno'=>0]);
            }
        }else{
            die("未找到当前帖子");
        }      
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'更改失败']);

    }
}
