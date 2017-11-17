<?php

namespace mis\controllers\tool;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use common\service\AliOssService;

/**
 * 渠道包列表页
 * 
 */
class IndexAction extends MBaseAction {

    public $resource_id = 'operation_package';

    public function run() {

        $fileList = AliOssService::fileList($maxKey = 1000, $delimiter = '', $nextMarker = '');
        return $this->controller->render('index', ['model' => $fileList]);
    }

//    function scan($path, $r = false) {
//        $dirs = scandir($path);
//        foreach ($dirs as $file) {
//            if (!is_dir($path . '/' . $file)) {
//                echo "/$file " . '上次访问时间：' . date('Y-m-d H:i:s', fileatime($path . '/' . $file)) . ' 文件大小：' . filesize("$path/$file") . '<br/>';
//            } else if (is_dir($path . '/' . $file) && $file != '.' && $file != '..' && $r == true) {
//                scan($path . '/' . $file, $r);
//            }
//        }
//    }
}
