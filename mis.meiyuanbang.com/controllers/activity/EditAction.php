<?php
namespace mis\controllers\activity;

use Yii;
use mis\components\MBaseAction;
use mis\service\ActivityService;
use mis\service\NewsService;
use mis\service\NewsDataService;
use mis\service\ResourceService;

/**
 * 活动添加和修改页面
 */
class EditAction extends MBaseAction
{
	public $resource_id = 'operation_activity';
	//活动在news表中的catid值
	private $activitycatid = 2;
	
	
    public function run()
    {
    	$request = Yii::$app->request;
    	
    	if(!$request->isPost){
    		//处理get请求
    		$ret = $this->getHandle();
    	}
    	else{
    		//处理post请求
    		$ret = $this->postHandle();
    	}
    	return $this->controller->render('edit', $ret);
    }
    
    /**
     * 处理get访问的情况
     */
    private function getHandle(){
    	$request = Yii::$app->request;
    	
    	//判断参数
    	$newsid = $request->get('newsid');
    	if($newsid){
    		//编辑
    		if(!is_numeric($newsid)){
    			die('非法输入');
    		}
    	}
    	else{
    		//新添加
    		$newsid = 0;
    	}
    	$ret = $this->getRetModel($newsid);
    	return  $ret;
    }
    
    /**
     * 处理post访问的情况
     */
    private function postHandle(){
    	$request = Yii::$app->request;
    	$msg='';
    	$usermodel = \Yii::$app->user->getIdentity();
    	
    	//先获取model
    	if($request->post('isedit')==1){
    		$newsid = $request->post('ActivityService')['newsid'];
    	}
    	else {
    		$newsid = 0;
    	}
    	//从公共方法先获取model并解析用户输入
    	$ret = $this->getRetModel($newsid);
    	$activitymodel = $ret['activitymodel'];
    	$newsmodel = $ret['newsmodel'];
    	$newsdatamodel = $ret['newsdatamodel'];
    	//获取用户输入的界面
    	$activitymodel->load($request->post());
    	$newsmodel->load($request->post());
    	$newsdatamodel->load($request->post());
    	//检查缩略图
    	$thumb = $request->post('thumb');
    	if($thumb==''){
    		die('必须上传缩略图');
    	}
    	//保存缩略图
    	if($newsmodel->thumb==''){
    		$rmodel = new ResourceService();
    		$rmodel->img = $thumb;
    		$rmodel->save();
    	}
    	else{
    		$rmodel = ResourceService::findOne(['rid'=>$newsmodel->thumb]);
    		if($rmodel->img != $thumb){
    			$rmodel->img = $thumb;
    			$rmodel->save();
    		}
    	}    	
    	$newsmodel->thumb = (string)$rmodel->rid;
    	//处理开始和截止时间
    	if($activitymodel->btime){
    		$activitymodel->btime = strtotime($activitymodel->btime);
    	}
    	if($activitymodel->etime){
    		$activitymodel->etime = strtotime($activitymodel->etime);
    	}
    	//操作员
    	$newsmodel->username = $usermodel->mis_realname;
       	//保存
    	if($request->post('isedit')==1){    		
    		//编辑保存
    		if(!$activitymodel->validate() || !$newsmodel->validate() || !$newsdatamodel->validate()){
    			$ret['msg'] = '输入错误，请检查输入项';
    		}
    		else{	    		
	    		//保存
	    		$newsmodel->utime = time();
	    		if($activitymodel->save() && $newsmodel->save() && $newsdatamodel->save()){
	    			$ret['isclose']  = true;
	    			$ret['msg'] = '保存成功';
	    		}
	    		else{
	    			$ret['msg'] = '保存失败';
	    		}
    		}
    	}
    	else{
    		//新增
    		$newsmodel->ctime = time();
    		$newsmodel->utime = $newsmodel->ctime;
    		$newsmodel->catid = $this->activitycatid;
    		//先保存news表获取id
    		if($newsmodel->validate() && $newsmodel->save()){
    			$activitymodel->newsid = $newsmodel->newsid;
    			$newsdatamodel->newsid = $newsmodel->newsid;
    			if($newsdatamodel->save(true) && $activitymodel->save(true)){
    				$ret['isclose'] = true;
    				$ret['msg'] = '保存成功';
    			}    		
    			else{
    				$ret['msg'] = '保存失败';
    			}    			
    		}
    		else{
    			$ret['msg'] = '保存失败';
    		}
    	}    	
    	return $ret;
    }
   
    /**
     * 根据newsid获取活动model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回活动编辑页的model
     */
    private function getRetModel($newsid){
    	if($newsid == 0){
    		//获取精讲详细信息
    		$ret['activitymodel'] = new ActivityService();
    		$ret['newsmodel'] = new NewsService();
    		$ret['newsdatamodel'] = new NewsDataService();
    		$ret['thumb_url'] = '';
    	}
    	else{
    		$activitymodel = ActivityService::findOne(['newsid'=>$newsid]);
    		$ret['activitymodel'] = $activitymodel;
    		$newmodel = NewsService::findOne(['newsid'=>$newsid]);
    		$ret['newsmodel'] = $newmodel;
    		$ret['newsdatamodel'] = NewsDataService::findOne([['newsid'=>$newsid]]);
    		//缩略图
    		$ret['thumb_url'] = ResourceService::findOne(['rid'=>$newmodel->thumb])->img;
    	}
    	return $ret;
    }
}
