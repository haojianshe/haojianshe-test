<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use common\service\AliOssService;
use common\service\CommonFuncService;
use common\models\myb\Resource;


/**
 * 精讲添加和修改页面
 */
class ThumbUploadAction extends MBaseAction {

    public $resource_id = 'operation_publish';
    private $ossobject = 'user';

    public function run() {
        $request = Yii::$app->request;

        if (!$request->isPost) {
            //get访问
            $url = $request->get('url');
            if ($url != '') {
                $url = urldecode($url);
            }
            $model['url'] = $url;
            return $this->controller->render('thumbupload', ['model' => $model]);
        } else {
//            $url = $request->post('url');
//            $model['url'] = $url;
//            //获取上传的图片
//            if (!isset($_FILES['file_thumb'])) {
//                die('未选择图片!');
//            }
//            $file = $_FILES['file_thumb'];
//            //检查图片大小和类型
//            if ($file['size'] > 512000) {
//                return $this->controller->render('thumbupload', ['model' => $model, 'msg' => '缩略图不能大于500k']);
//            }
//            $fileext = AliOssService::getFileExt($file['name']);
//            if (!in_array($fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp"])) {
//                return $this->controller->render('thumbupload', ['model' => $model, 'msg' => '图片格式错误']);
//            }
//            //上传图片
//            $filename = AliOssService::getFileName($fileext);
//            $ret = AliOssService::picUpload($this->ossobject, $filename, $file);
//            if ($ret == false) {
//                return $this->controller->render('thumbupload', ['model' => $model, 'msg' => '上传图片失败']);
//            }
//            //上传成功
//            $model['url'] = Yii::$app->params['ossurl'] . $ret;
//            return $this->controller->render('thumbupload', ['model' => $model, 'msg' => '上传成功', 'isclose' => true]);
            //处理上传的图片
            if (!isset($_FILES['file_thumb'])) {
                die('未选择图片!');
            }
            $file = $_FILES['file_thumb'];
            //检查图片大小和类型
            if ($file['size'] > 5120000) {
                die('图片太大');
            }
            $fileext = AliOssService::getFileExt($file['name']);
            if (!in_array($fileext, [".png", ".jpg", ".jpeg", ".gif", ".bmp"])) {
                die('图片格式错误');
            }
            //开始处理图片
            $filename = AliOssService::getFileName($fileext);
            $ret = AliOssService::picUpload($this->ossobject, $filename, $file);
            if ($ret == false) {
                return $this->controller->outputMessage(['errno' => 1, 'msg' => $file['name'] . '上传失败']);
            }
            $data['url'] = Yii::$app->params['ossurl'] . $ret;
            //图片宽高
            $ret = AliOssService::getFileHW($data['url']);
            //判断是否取到宽高
            if (!$ret['height'] || !$ret['width']) {
                return $this->controller->outputMessage(['errno' => 1, 'msg' => $file['name'] . '上传失败']);
            }
            if ($ret != false) {
                $data['w'] = $ret['width'];
                $data['h'] = $ret['height'];
            } else {
                $data['w'] = 1000;
                $data['h'] = 1000;
            }
            #获取小图
            $smallImg = CommonFuncService::getPicByType($data, 't');
            $img['img'] = [
                'n' => $data,
                's' => $smallImg
            ];
            $model = new Resource();
            $model->img = json_encode($img);
            $model->save();
            $array['rcid'] = $model->attributes['rid'];
            $array['url'] = $data['url'];
			$_SESSION['img_json'] = json_encode($img);
            if ($ret) {
                return $this->controller->render('thumbupload', ['model'=>$array, 'msg' => '上传成功', 'isclose' => true]);
            } else {
                return $this->controller->render('thumbupload', ['msg' => $file['name'] . '保存数据库失败']);
            }
        }
    }

}
