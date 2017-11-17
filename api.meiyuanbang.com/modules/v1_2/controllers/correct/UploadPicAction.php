<?php

namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use common\service\AliOssService;
use common\models\myb\Resource;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 上传图片
 */
class UploadPicAction extends ApiBaseAction {

    private $ossobject = 'correct/img';

    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        $model = new Resource();
        $model->description = $request->post('description');
        $model->resource_type = 0;
        //处理上传的图片
        if (!isset($_FILES['img_file'])) {
            die('未选择图片!');
        }
        $file = $_FILES['img_file'];
        //检查图片大小和类型
        if ($file['size'] > 10485760) {
            die('图片太大');
        }
        $fileext = AliOssService::getFileExt($file['name']);
        if (!in_array($fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp", ".webp"])) {
            die('图片格式错误');
        }
        
        #首先获取指纹对数据库做比较，如果存在返回图片信息，如果不存在则写入
        $md5_string = md5_file($file['tmp_name']);
        if (isset($md5_string)) {
            $resource = Resource::find()->select(['rid', 'description', 'img'])->where(['md5_string' => $md5_string])->orderBy('rid desc')->asArray()->one();
            if (!empty($resource)) {
                $data['img'] = json_decode($resource['img']);
                $data['description'] = $resource['description'];
                $data['rid'] = $resource['rid'];
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
            }
        }

        //开始处理图片
        $filename = AliOssService::getFileName($fileext);
        $ret = AliOssService::picUpload($this->ossobject, $filename, $file);
        if ($ret == false) {
            return $this->controller->outputMessage(['errno' => 1, 'msg' => $file['name'] . '上传失败']);
        }

        $img_infohw = AliOssService::getFileHW(Yii::$app->params['ossurl'] . $ret);
        //判断是否取到宽高
        if (!$img_infohw['height'] || !$img_infohw['width']) {
            return $this->controller->outputMessage(['errno' => 1, 'msg' => $file['name'] . '上传失败']);
        }
        $img_info['n']['h'] = $img_infohw['height'];
        $img_info['n']['w'] = $img_infohw['width'];
        $img_info['n']['url'] = Yii::$app->params['ossurl'] . $ret;
        //上传成功,写库
        $model->img = json_encode($img_info);
        $model->md5_string = $md5_string;
        $model->save();
        $data['img'] = json_decode($model->img);
        $data['description'] = $model->description;
        $data['rid'] = $model->attributes['rid'];
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
