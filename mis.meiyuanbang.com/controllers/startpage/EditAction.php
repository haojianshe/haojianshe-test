<?php
namespace mis\controllers\startpage;

use Yii;
use mis\components\MBaseAction;
use mis\service\StartpageService;

/**
 * 启动页添加和修改页面
 */
class EditAction extends MBaseAction
{
	public $resource_id = 'operation_startpage';
	
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
		$pageid = $request->get('pageid');
		if($pageid){
			//编辑
			if(!is_numeric($pageid)){
				die('非法输入');
			}			
			$startpagemodel = StartpageService::findOne(['pageid'=>$pageid]);
			$ret['startpagemodel'] = $startpagemodel;
			$imginfo = json_decode($startpagemodel->imginfo);			
			//缩略图
			$ret['thumb_url'] = $imginfo->url;			
		}
		else{
			//新添加
			$pageid = 0;
			$ret['startpagemodel'] = new StartpageService();
			$ret['thumb_url'] = '';
		}
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
			$pageid = $request->post('StartpageService')['pageid'];
			$startpagemodel = StartpageService::findOne(['pageid'=>$pageid]);
		}
		else {
			$pageid = 0;
			$startpagemodel = new StartpageService();
			$startpagemodel->ctime =time();
			$startpagemodel->status = 0;			
		}
		//获取用户输入的界面
		$startpagemodel->load($request->post());
		//检查启动页图片
		$thumb = $request->post('thumb');
		if($thumb==''){
			die('必须上传图片');
		}
		$startpagemodel->imginfo = $thumb;
		//返回时，必须带启动图地址
		$imginfo = json_decode($startpagemodel->imginfo);
		$ret['thumb_url'] = $imginfo->url;
		//处理开始和截止时间
		$startpagemodel->startdate = strtotime($startpagemodel->startdate);
		$startpagemodel->expiredate = strtotime($startpagemodel->expiredate);
		//校验
		if(!$startpagemodel->validate()){
			$ret['startpagemodel'] = $startpagemodel;
			$ret['msg'] = '输入错误，请检查输入项';
			return $ret;
		}
		if($request->post('isedit')!=1){
			//新增
			$startpagemodel->ctime = time();
		}
		//保存
		if($startpagemodel->save()){
			$ret['isclose']  = true;
			$ret['msg'] = '保存成功';
		}
		else{
			$ret['msg'] = '保存失败';
		}	
		$ret['startpagemodel'] = $startpagemodel;
		return $ret;
	}
}
