<?php
namespace api\controllers\message;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\MessageService;
use common\service\AliOssService;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use api\service\CointaskService;
use api\service\UserCoinService;

/**
 * 发送私信
 */
class NewMsgAction extends ApiBaseAction
{   
    public function run()
    {              
    	//接收人
        $otheruid = $this->requestParam('to_uid',true);
        //私信类型
        $mtype = $this->requestParam('mtype'); 
        if($mtype!=1 && $mtype!=2 && $mtype!=3){
        	$mtype = 0;
        }
        //检查不能发送给自己
        if($this->_uid == $otheruid){
        	$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        }
        $model = new MessageService();
        $model->ctime = time();
        $model->from_uid = $this->_uid;
        $model->to_uid = $otheruid;
        $model->from_del = 0;
        $model->to_del = 0;
        $model->mtype = $mtype;
        if($mtype==0){
        	//文字类型
        	$model->content = $this->requestParam('content',true);
        }
        elseif($mtype==1){
        	//图片
        	$file = $_FILES['file'];
        	//检查图片大小和类型
        	if($file['size']>5000000){
        		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        	}
        	$fileext = AliOssService::getFileExt($file['name']) ;
        	if(!in_array( $fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp"]))
        	{
        		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        	}
        	//上传图片
        	$filename = AliOssService::getFileName($fileext);
        	$url = AliOssService::picUpload('message', $filename, $file);
        	if ($url == false) {
        		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        	}        	
        	$img_infohw = AliOssService::getFileHW(Yii::$app->params['ossurl'] . $url);
            //判断是否取到宽高
            if(!$img_infohw['height'] || !$img_infohw['width']){
                return $this->controller->outputMessage(['errno'=>1,'msg'=>$file['name'].'上传失败']);
            }
        	$img_info['n']['h']=$img_infohw['height'];
        	$img_info['n']['w']=$img_infohw['width'];
        	$img_info['n']['url']=Yii::$app->params['ossurl'] . $url;
        	//记录图片信息
        	$model->content = json_encode($img_info);
        }
        elseif($mtype==2){ //语音
        	//时长
        	$voice['duration'] = round($this->requestParam('duration',true));
        	//文件检查
        	$file = $_FILES['file'];
        	//检查语音文件大小及类型
        	if($file['size']>10485760){
        		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        	}
        	$fileext = AliOssService::getFileExt($file['name']) ;
        	if(!in_array( $fileext, [".amr",".mp3",".wav","wma"])) {
        		$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        	}
        	//上传
        	$filename = AliOssService::getFileName($fileext);
        	$url = AliOssService::talkUpload('message', $filename, $file);
        	$voice['url'] = Yii::$app->params['ossurl'] . $url;
        	$model->content = json_encode($voice);
        }elseif($mtype==3){
            $model->content = trim($this->requestParam('content',true));
        }
        //保存
        if(!$model->save()){
        	$this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }
        //发推送消息
        MessageService::pushMessage($this->_uid, $otheruid, $model->mid);
        $ret['mid'] = $model->mid;
        //如果是意见反馈，则判断添加积分
        if($otheruid ==4 || $otheruid ==3){
        	$tasktype = CointaskTypeEnum::ADVISE;
        	if(CointaskService::IsAddByDaily($this->_uid, $tasktype)){
        		//需要加金币
        		$coinCount = CointaskDictService::getCoinCount($tasktype);
        		UserCoinService::addCoinNew($this->_uid, $coinCount);
        		$ret['cointask'] = CointaskService::getReturnData($tasktype, $coinCount);
        	}
        }        
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}