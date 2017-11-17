<?php

namespace mis\controllers\live;

use Yii;
use mis\components\MBaseAction;
use mis\service\LiveService;

//use common\service\DictdataService;
/**
 * 查看直播地址
 */
class ShowUrlAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_video';

    public function run() {
        //分页获取直播列表
        $request = \Yii::$app->request;
        $liveid = $request->get('liveid');
        $data = LiveService::getLiveUrl($liveid);
       
        $tmp2 = 'com';
        $hostaddress = $_SERVER['HTTP_HOST'];
        if ($hostaddress != 'mis.meiyuanbang.cn') {
            $tmp2 = 'cn';
        }
        //生成rtmp播放地址
        $displayKey = '/myb/live' . $tmp2 . '_' . $data['liveid'] . '-' . $data['end_time'] . '-0-0-' . Yii::$app->params['live_key'];
        //加密key值
        $k = md5($displayKey);
        //返回rtmp播放地址
        $data['rtmp_url'] = 'rtmp://live.meiyuanbang.com/myb/live' . $tmp2 . '_' . $data['liveid'] . '?auth_key=' . $data['end_time'] . '-0-0-' . $k;
   
        #$key = '/myb/live'.$tmp2.'_' . $data['liveid'] . '-' . $data['end_time'] . '-0-0-' . Yii::$app->params['live_key'];
        #$mdwKey = md5($key);
        #$data['live_push_url'] = $data['live_push_url'];//'rtmp://video-center.alivecdn.com/myb/live'.$tmp2.'_' . $data['liveid'] . '?vhost=live.meiyuanbang.com&auth_key=' . $data['end_time'] . '-0-0-' . $mdwKey;

        #$displayKey = '/myb/live'.$tmp2.'_' . $data['liveid'] . '.m3u8-' . $data['end_time'] . '-0-0-' . Yii::$app->params['live_key'];
        #$k = md5($displayKey);
       # $data['live_display_url'] = $data->live_display_url;;//'http://live.meiyuanbang.com/myb/live'.$tmp2.'_' . $data['liveid'] . '.m3u8?auth_key=' . $model->attributes['end_time'] . '-0-0-' . $k;
        return $this->controller->render('showurl', ['models' => $data]);
    }

}
