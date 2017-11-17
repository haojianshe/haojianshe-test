<?php
namespace mis\controllers\comment;

use Yii;
use mis\components\MBaseAction;
use mis\service\CommentService;


/**
 * 删除评论
 */
class DelAction extends MBaseAction
{	
	public $resource_id = 'operation_cmt';
	
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
    	$cid = $request->post('cid');
        $subjecttype = $request->post('subjecttype');
    	if(!$cid || !is_numeric($cid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = CommentService::findCommentInfo($cid);
      $model->is_del=1;
    	if($model){
    		$ret = $model->save();
    		if($ret){
          //根据评论类型处理缓存
          CommentService::updateCmtCountRedis($subjecttype,$model->subjectid);
         return $this->controller->outputMessage(['errno'=>0]);
        }
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
