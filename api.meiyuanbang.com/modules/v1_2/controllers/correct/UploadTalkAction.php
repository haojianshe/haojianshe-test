<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use common\service\AliOssService;
use common\models\myb\CorrectTalk;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 上传语音
 */
class UploadTalkAction extends ApiBaseAction
{   
    //oss 保存目录
    private $ossobject = 'correct/sound';
    public function run()
    {
        $request = Yii::$app->request;
        if(!$request->isPost){
            die('非法请求!');
        }
        $model=new CorrectTalk();
        //时长duration
        $model->duration=round($this->requestParam('duration',true));
        //坐标
        $model->location=$this->requestParam('location');
        //处理上传的图片
        if (!isset($_FILES['talk_file'])) {
            die('未选择语音!');
        }       
        $file = $_FILES['talk_file'];
        //检查图片大小和类型
        if($file['size']>10485760){
            die('语音太大');
        }
        $fileext = AliOssService::getFileExt($file['name']) ;
        if(!in_array( $fileext, [".amr"]))
        {
            die('语音格式错误');
        } 
        //开始处理语音
        $filename = AliOssService::getFileName($fileext);
        $ret = AliOssService::talkUpload($this->ossobject, $filename, $file);
        if ($ret == false) {
            return $this->controller->outputMessage(['errno'=>ReturnCodeEnum::STATUS_ERR_REQUEST,'msg'=>$file['name'].'上传失败']);
        }
        //上传成功,写库
        $model->url=Yii::$app->params['ossurl'] . $ret;
        $model->save();     
        if($model->location){
             $model->location=json_decode($model->location);
        }       
        $data=$model->attributes;   
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
