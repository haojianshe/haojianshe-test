<?php
namespace mis\controllers\znarticle;

use Yii;
use mis\components\MBaseAction;
use mis\service\ZhnArticleService;
use mis\service\NewsService;
use mis\service\NewsDataService;
use mis\service\ResourceService;

/**
 * 正能文章添加和修改页面
 */
class EditAction extends MBaseAction
{
	public $resource_id = 'operation_zhn';
	
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
    		$newsid = $request->post('ZhnArticleService')['newsid'];
    	}
    	else {
    		$newsid = 0;
    	}
    	//从公共方法先获取model并解析用户输入
    	$ret = $this->getRetModel($newsid);
    	$zhnarticlemodel = $ret['zhnarticlemodel'];
    	$newsmodel = $ret['newsmodel'];
    	$newsdatamodel = $ret['newsdatamodel'];
    	//获取用户输入的界面
    	$zhnarticlemodel->load($request->post());
    	//判断是否需要评论
		if(isset($request->post('ZhnArticleService')['allowcmt'])){
			$zhnarticlemodel->allowcmt=1;
		}
		else {
			$zhnarticlemodel->allowcmt=0;
		}
    	$newsmodel->load($request->post());
    	$newsdatamodel->load($request->post());
    	//将类型赋值回model里，出错时能够保存当前输入
    	$ret['zhnarticlemodel'] = $zhnarticlemodel;
    	$ret['newsmodel'] = $newsmodel;
    	$ret['newsdatamodel'] = $newsdatamodel;
    	//操作员
    	$newsmodel->username = $usermodel->mis_realname;
    	//保存
    	if($request->post('isedit')==1){
    		//编辑保存
    		if(!$zhnarticlemodel->validate() || !$newsmodel->validate() || !$newsdatamodel->validate()){
    			$ret['msg'] = '输入错误，请检查输入项';
    		}
    		else{	    		
	    		//保存
	    		$newsmodel->utime = time();
	    		if($zhnarticlemodel->save() && $newsmodel->save() && $newsdatamodel->save()){
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
    		//先保存news表获取id
    		if($newsmodel->validate() && $newsmodel->save()){
    			$zhnarticlemodel->newsid = $newsmodel->newsid;
    			$zhnarticlemodel->status = 2;
    			$newsdatamodel->newsid = $newsmodel->newsid;
    			if($newsdatamodel->save(true) && $zhnarticlemodel->save(true)){
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
     * 根据newsid获取所有到的文章model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回文章编辑页的model
     */
    private function getRetModel($newsid){
    	$ret = [];
    	if($newsid == 0){
    		//获取精讲详细信息
    		$zhnarticlemodel = new ZhnArticleService();
    		$zhnarticlemodel->allowcmt =1;
    		$ret['zhnarticlemodel'] = $zhnarticlemodel;
    		$ret['newsmodel'] = new NewsService();
    		$ret['newsdatamodel'] = new NewsDataService();
    	}
    	else{
    		$zhnarticlemodel = ZhnArticleService::findOne(['newsid'=>$newsid]);
    		$ret['zhnarticlemodel'] = $zhnarticlemodel;
    		$ret['newsmodel'] = NewsService::findOne(['newsid'=>$newsid]);
    		$ret['newsdatamodel'] = NewsDataService::findOne([['newsid'=>$newsid]]);
    	}
    	return $ret;
    }
}
