<?php

namespace mis\controllers\tool;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use common\service\AliOssService;

/**
 * 增加能力素材接口 
 */
class AddApiAction extends MBaseAction {

    public $resource_id = 'operation_package';
    private $ossobject = 'download';

    /**
     * 增加apk文件上传
     */
    public function run() {

        $request = Yii::$app->request;

        //只能post访问
        if (!$request->isPost) {
            die('访问错误');
        }

        //处理上传的图片
        if (!isset($_FILES['uploadify'])) {
            die('未选择文件!');
        }
        $file = $_FILES['uploadify'];

        $fileext = AliOssService::getFileExt($file['name']);
        if (!in_array($fileext, [".apk"])) {
            die('图片格式错误');
        }
        //开始处理apk文件
        $ret = AliOssService::ApkUpload($this->ossobject, $file['name'], $file);
        if ($ret) {
            return $this->controller->outputMessage(['errno' => 0, 'msg' => $file['name'] . '上传成功']);
        }
    }

}
