<?php
namespace common\components;
use Yii;
use yii\base\Action;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * action基类
 */
class BaseAction extends Action
{
	/**
	 * 获取客户端传来的参数
	 * 从post和get都获取
	 * post先获取因为api接口post的命中率
	 * @param $name
	 * @param $validate 代表是否验证参数的必须性
	 * @return unknown|NULL
	 */
	public function requestParam($name,$validate=false)
	{
		$request = Yii::$app->request;
		 
		//从post获取参数
		$ret = $request->post($name);
		//post未取到则从get获取
		if(!$ret && $ret!=='0'){
			$ret = $request->get($name);
		}
		//验证参数是否必须
		if($validate){
			if(!$ret  && $ret!=='0'){
				$data['message']='缺少参数 '.$name;
				$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST,$data);
			}
		}
		//返回
		if($ret==='0' || $ret){
			return $ret;
		}else{
			return null;
		}
	}
	
	/**
	 * 验证参数是否存在 有则返回 无则返回错误信息
	 * @param  [type]  $name   [description]
	 * @param  boolean $isMust [description]
	 * @return [type]          [description]
	 */
	public function requestParamValidate($name){
		$ret=$this->requestParam($name);
		if(!isset($ret) && $ret!=0){
			$data['message']='Missing parameters '.$name;
			$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST,$data); 
		}else{
			return $ret;
		}
	}
}