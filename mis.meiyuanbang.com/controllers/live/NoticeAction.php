<?php

namespace mis\controllers\live;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoResourceService;
use mis\models\UploadForm;
use yii\web\UploadedFile;
#直播service
use mis\service\LiveService;

/**
 * 接收直播转录播工作流转化成功,
 * 代码来自阿里云消息服务订阅消息demo
 * demo里的代码有错误，与阿里云帮助文档不相符，所有需要参加验证的header项都需要把名称改成小写才能通过校验
 */
class NoticeAction extends MBaseAction {

    public function run() {
        //未通过验证时自己记录日志
        $logfile = __DIR__ . '/../../runtime/logs/live2mp4_log.txt';

        //1.获取header中需要参加校验的信息，进行校验
        $tmpHeaders = array();
        $headers = $this->getallheaders();
        foreach ($headers as $key => $value) {
            if (0 === strpos($key, 'x-mns-')) {
                $tmpHeaders[$key] = $value;
            }
        }
        ksort($tmpHeaders);
        $canonicalizedMNSHeaders = implode("\n", array_map(function ($v, $k) {
                    return $k . ":" . $v;
                }, $tmpHeaders, array_keys($tmpHeaders)));

        $method = $_SERVER['REQUEST_METHOD'];
        $canonicalizedResource = $_SERVER['REQUEST_URI'];
        $contentMd5 = '';
        if (array_key_exists('Content-MD5', $headers)) {
            $contentMd5 = $headers['Content-MD5'];
        } else if (array_key_exists('content-md5', $headers)) {
            $contentMd5 = $headers['content-md5'];
        }
        $contentType = '';
        if (array_key_exists('content-type', $headers)) {
            $contentType = $headers['content-type'];
        }
        $date = $headers['date'];
        //由上面获取到的header项组成参加校验的数据
        $stringToSign = strtoupper($method) . "\n" . $contentMd5 . "\n" . $contentType . "\n" . $date . "\n" . $canonicalizedMNSHeaders . "\n" . $canonicalizedResource;

        $publicKeyURL = base64_decode($headers['x-mns-signing-cert-url']);
        $publicKey = $this->get_by_url($publicKeyURL);
        $signature = $headers['authorization'];
        $pass = $this->verify($stringToSign, $signature, $publicKey);
        if (!$pass) {
            http_response_code(400);
            return;
        }
        //2.解析消息
        $content = file_get_contents("php://input");
        if (!empty($contentMd5) && $contentMd5 != base64_encode(md5($content))) {
            http_response_code(401);
            return;
        }
        //3. 处理消息
        file_put_contents($logfile, $content, FILE_APPEND);
        $this->updateDataTable($content);
        http_response_code(200);
        die('');
    }

    /**
     * 处理直播转录播完成消息，
     * 根据m3u8文件名能够获得直播id和MP4和m3u8文件分别对应的url地址
     * m3u8文件格式为live_[liveid]_XXX
     * @param unknown $content
     */
    private function updateDataTable($content) {
        $message_str = json_decode($content)->Message;
        $Message_obj = json_decode($message_str);
        switch ($Message_obj->Name) {
            case 'activityStart':
                break;
            case 'Act-Report':
                if ($Message_obj->State == "Success") {
                    //(1)取得文件名称
                    $filename_full = $Message_obj->MediaWorkflowExecution->Input->InputFile->Object;
                    $filename_fomat = substr(strrchr($Message_obj->MediaWorkflowExecution->Input->InputFile->Object, "/"), 1);
                    $filename = strstr($filename_fomat, '.', true);
                    $runid = $Message_obj->MediaWorkflowExecution->RunId;
                    $this->checkComCn($filename);
                    //(2)获取直播liveid和m3u8 MP4文件的地址
                    $tmp = explode('_', $filename);
                    if (count($tmp) < 2) {
                        die('filename error');
                    }
                    $liveid = $tmp[1];
                    $m3u8url = Yii::$app->params['livevideourl'] . 'myb/' . $filename . '.m3u8';
                    $mp4url = Yii::$app->params['livevideosourceurl'] . 'myb_livemp4/' . $runid . '/' . $filename . '.mp4';
                    //直播实录转入录播
                    $result = VideoResourceService::setVideoLive($liveid, $m3u8url, $mp4url, $filename, $runid);
                    if ($result) {
                        //test
                        $logfile = __DIR__ . '/../../runtime/logs/live2mp4_log.txt';
                        $content = $filename . '---' . $liveid . '---' . $m3u8url . '---' . $mp4url;
                        file_put_contents($logfile, $content, FILE_APPEND);
                        //test     
                    }
                }
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * 检查cn和com环境收到的消息不互相干扰
     * 名称包含livecn的为测试环境直播，包含livecom的为线上环境
     * 当前域名包含.meiyuanbang.cn的为测试环境，包含.meiyuanbang.com的为线上环境 
     */
    private function checkComCn($filename) {
        //消息所属环境
        $tmp1 = 'com';
        //当前环境
        $tmp2 = 'com';

        //判断消息所属环境
        if (strpos($filename, 'livecom') === false) {
            $tmp1 = 'cn';
        }
        //判断当前环境
        $hostaddress = $_SERVER['HTTP_HOST'];
        if (strpos($hostaddress, '.meiyuanbang.com') === false) {
            $tmp2 = 'cn';
        }
        //消息和环境不同时，不处理消息
        if ($tmp1 != $tmp2) {
            die('');
        }
        return $tmp2;
    }

    /**
     * 设置视频直播转录播
     * @param int    $liveid   直播变化
     * @param string $m3u8url  m3u8url地址
     * @param string $mp4url  mp4url 地址
     * @param string $filename 文件名
     */
    private function setVideoLive($liveid, $m3u8url = null, $mp4url = null, $filename = null, $runid = null) {
        if (is_numeric($liveid)) {

            /**
              CREATE TABLE `myb_video_resource` (
              `videoid` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增',
              `video_type` tinyint(1) DEFAULT NULL COMMENT '1/2 直播/课程',
              `video_length` int(11) DEFAULT '0' COMMENT '视频时长',
              `video_size` bigint(16) DEFAULT '0' COMMENT '视频大小',
              `maintype` tinyint(4) DEFAULT NULL COMMENT '主类型id',
              `subtype` int(11) DEFAULT NULL COMMENT '分类型id',
              `filename` varchar(50) DEFAULT NULL COMMENT '文件名，用于接收阿里云通知时找到对应数据,文件名不能重复',
              `coverpic` varchar(255) DEFAULT NULL COMMENT '封面图',
              `sourceurl` varchar(255) DEFAULT NULL COMMENT '源视频url地址',
              `m3u8url` varchar(255) DEFAULT NULL COMMENT 'm3u8url地址',
              `runid` varchar(255) DEFAULT NULL COMMENT '阿里云工作流id',
              `desc` varchar(100) DEFAULT NULL COMMENT '视频备注',
              `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:正常 2删除',
              `ctime` int(11) DEFAULT NULL,
              PRIMARY KEY (`videoid`),
              KEY `idx_filename` (`filename`) USING BTREE,
              KEY `idx_maintype_subtype` (`maintype`,`subtype`) USING BTREE
              ) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4;
             */
            //根据liveid获取直播的封面
            $liveObj = LiveService::findOne(['liveid' => $liveid]);
            if (!empty($liveObj)) {

                $m3u8url = 'http://video2.meiyuanbang.com/myb/11.m3u8';
                $mp4url = 'http://live-media-out.oss-cn-shanghai.aliyuncs.com/myb_livemp4/123/11.mp4';
                //(3)video插入记录
                try {
                    $innerTransaction = Yii::$app->db->beginTransaction();
                    $videoRes = new VideoResourceService();
                    $videoRes->video_type = 1;
                    $videoRes->maintype = $liveObj->f_catalog_id;
                    $videoRes->subtype = $liveObj->s_catalog_id;
                    $videoRes->filename = $filename;
                    $videoRes->coverpic = $liveObj->live_thumb_url;
                    $videoRes->sourceurl = $mp4url;
                    $videoRes->m3u8url = $m3u8url;
                    $videoRes->runid = $runid;
                    $videoRes->status = 1;
                    $videoRes->ctime = time();
                    $videoRes->save();
                    $innerTransaction->commit();
                } catch (Exception $ex) {
                    
                }

                //(4)更新live表中videoid字段并清除缓存
            }
        }
    }

    private function get_by_url($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }

    private function verify($data, $signature, $pubKey) {
        $res = openssl_get_publickey($pubKey);
        $result = (bool) openssl_verify($data, base64_decode($signature), $res);
        openssl_free_key($res);
        return $result;
    }

    private function getallheaders() {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
            }
        }
        return $headers;
    }

}
