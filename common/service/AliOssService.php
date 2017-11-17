<?php

namespace common\service;

use Yii;
use yii\base\Object;

/**
 * 上传图片或文件到阿里oss服务
 * require引入的类前边必须加\,否则报not found
 */
class AliOssService extends Object {

    static function picUpload($obj, $filename, $file) {
        //引入阿里云oss sdk
        require_once __DIR__ . '/../../vendor/oss/cms_base.php';
        //获取oss对象,require引入的类前边必须加\,否则报not found
        $bucket = \MYBOssUtil::get_bucket_name();
        $oss = \MYBOssUtil::get_oss_client();
        $filepath = $obj . '/' . date("Y-m-d") . '/' . $filename;
        //上传文件到  $file["tmp_name"]为上传文件的临时目录
        $options = ['headers' => ['Content-Type' => 'image/jpeg']];
        $ret = $oss->upload_file_by_file($bucket, $filepath, $file["tmp_name"], $options);
        if (false === $ret || 200 !== $ret->status) {
            return false;
        }
        //上传成功返回图片路径
        return $filepath;
    }

    static function talkUpload($obj, $filename, $file) {
        //引入阿里云oss sdk
        require_once __DIR__ . '/../../vendor/oss/cms_base.php';
        //获取oss对象,require引入的类前边必须加\,否则报not found
        $bucket = \MYBOssUtil::get_bucket_name();
        $oss = \MYBOssUtil::get_oss_client();
        $filepath = $obj . '/' . date("Y-m-d") . '/' . $filename;
        //上传文件到  $file["tmp_name"]为上传文件的临时目录
        $options = ['headers' => ['Content-Type' => 'audio/amr']];
        $ret = $oss->upload_file_by_file($bucket, $filepath, $file["tmp_name"], $options);
        if (false === $ret || 200 !== $ret->status) {
            return false;
        }
        //上传成功返回图片路径
        return $filepath;
    }

    /**
     * 上传MP3声音
     * @param unknown $obj
     * @param unknown $filename
     * @param unknown $uploadfile
     * @return boolean|string
     */
    static function talkMp3Upload($obj, $filename, $uploadfile) {
        //引入阿里云oss sdk
        require_once __DIR__ . '/../../vendor/oss/cms_base.php';
        //获取oss对象,require引入的类前边必须加\,否则报not found
        $bucket = \MYBOssUtil::get_bucket_name();
        $oss = \MYBOssUtil::get_oss_client();
        $filepath = $obj . '/' . date("Y-m-d") . '/' . $filename;
        //上传文件到  $file["tmp_name"]为上传文件的临时目录
        $options = ['headers' => ['Content-Type' => 'audio/mp3']];
        $ret = $oss->upload_file_by_file($bucket, $filepath, $uploadfile, $options);
        if (false === $ret || 200 !== $ret->status) {
            return false;
        }
        //上传成功返回图片路径
        return $filepath;
    }

    /**
     * 上传apk文件
     * @param type $obj
     * @param type $filename
     * @param type $file
     * @return boolean|string
     */
    static function apkUpload($obj, $filename, $file) {
        //引入阿里云oss sdk
        require_once __DIR__ . '/../../vendor/oss/cms_base.php';
        //获取oss对象,require引入的类前边必须加\,否则报not found
        $bucket = \MYBOssUtil::get_bucket_name();
        $oss = \MYBOssUtil::get_oss_client();
        $filepath = $obj . '/' . $filename;
        //上传文件到  $file["tmp_name"]为上传文件的临时目录
        $options = ['headers' => ['Content-Type' => 'audio/amr']];
        $ret = $oss->upload_file_by_file($bucket, $filepath, $file["tmp_name"], $options);
        if (false === $ret || 200 !== $ret->status) {
            return false;
        }
        //上传成功返回路径
        return $filepath;
    }

    /**
     * 获取文件扩展名
     */
    static function getFileExt($filename) {
        return strtolower(strrchr($filename, '.'));
    }

    /**
     * 获取文件名
     */
    static function getFileName($ext) {
        mt_srand((double) microtime() * 10000);
        $uuid = strtoupper(md5(uniqid(rand(), true)));
        return $uuid . $ext;
    }

    /**
     * 根据阿里云图片的url获取宽高信息
     * @param unknown $fileurl
     */
    static function getFileHW($fileurl) {
        $ret = [];

        $output = self::curl($fileurl . '@exif');
        if (false === $output) {
            $output = self::curl($fileurl . '@info');
            if (false === $output) {
                return false;
            }
            $info = json_decode($output, true);
            $ret['height'] = $info['height'];
            $ret['width'] = $info['width'];
        } else {
            $info = json_decode($output, true);
            //$orientation代表横向和竖向
            $orientation = intval($info['Orientation']['value']);
            if ($orientation < 5) {
                $ret['height'] = intval($info['ImageHeight']['value']);
                $ret['width'] = intval($info['ImageWidth']['value']);
            } else {
                $ret['height'] = intval($info['ImageWidth']['value']);
                $ret['width'] = intval($info['ImageHeight']['value']);
            }
        }
        return $ret;
    }

    /**
     * curl操作
     * @param unknown $url
     * @return boolean|mixed
     */
    private static function curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (200 != $http_code) {
            return false;
        } else {
            return $output;
        }
    }

    static public function fileList($maxKey = 100, $delimiter = '', $nextMarker = 's') {
        //引入阿里云oss sdk
        require_once __DIR__ . '/../../vendor/oss/cms_base.php';
        //获取oss对象,require引入的类前边必须加\,否则报not found
        $bucket_name = \MYBOssUtil::get_bucket_name();
        $oss = \MYBOssUtil::get_oss_client();

        $fileList = []; // 获取的文件列表, 数组的一阶表示分页结果
        $dirList = []; // 获取的目录列表, 数组的一阶表示分页结果
        $storageList = [
            'file' => [], // 真正的文件数组
            'dir' => [], // 真正的目录数组
        ];
        while (true) {
            $options = [
                'delimiter' => $delimiter,
                'prefix' => 'download',
                'max-keys' => $maxKey,
                'marker' => $nextMarker,
            ];
            try {
                $fileListInfo = $oss->list_object($bucket_name, $options);
                // 得到nextMarker, 从上一次 listObjects 读到的最后一个文件的下一个文件开始继续获取文件列表, 类似分页
            } catch (OssException $e) {
                return $this->send($this->errorCode, $e->getMessage()); // 发送错误信息
            }
            $xml = simplexml_load_string($fileListInfo->body);
            $xmljson = json_encode($xml);
            $xml = json_decode($xmljson, true);
            $downloadArray = [];
            foreach ($xml['Contents'] as $key => $val) {
                if ($val['Size']) {
                    $downloadArray[$key] = [
                        'Key' => str_replace("download/", " ", $val['Key']),
                        'LastModified' => date('Y-m-d H:i:s', strtotime($val['LastModified'])),
                        'Size' => sprintf("%.1f", $val['Size'] / 1024 / 1024) . 'M'
                    ];
                }
            }
            return $downloadArray;
        }
    }

}
