<?php

namespace mis\controllers\vesttweet;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\TweetService;
use mis\service\ResourceService;
use common\service\CommonFuncService;
use common\service\DictdataService;

/**
 * 帖子列表（按utime倒序）
 * 
 */
class IndexAction extends MBaseAction {

    public $resource_id = 'operation_vesttweet';

    public function run() {
        $request = Yii::$app->request;
        $sname = trim($request->get('sname'));
        //开始时间
        $start_time = $request->get('start_time');
        //结束时间
        $end_time = $request->get('end_time');
        if (isset($sname) && !empty($sname)) {
            $where = "sname like '%$sname%' and";
        } else {
            $where = '';
        }

        //创建时间默认赋值
        if (empty($start_time)) {
            $start_time = date("Y-m-d 00:00:00", strtotime("-7 day"));
        }
        if (empty($end_time)) {
            $end_time = date('Y-m-d 00:00:00', strtotime("+1 day"));
        }

        $where .=' ci_tweet.ctime>=' . strtotime($start_time) . ' and ci_tweet.ctime <=' . strtotime($end_time);

        $data = TweetService::getMisTweetPageByUtime($where);
        foreach ($data['models'] as $key => $value) {
            $data['models'][$key]['is_vest'] = in_array($value['uid'], DictdataService::getVestUser());
            $resources = ResourceService::findAll(['rid' => explode(',', $value['resource_id'])]);
            //为批改增加不同格式图片大小
            foreach ($resources as $k1 => $v1) {
                //为批改增加不同格式图片大小
                $arrtmp = json_decode($v1['img'], true);
                if (empty($arrtmp['l'])) {
                    $arrtmp['l'] = CommonFuncService::getPicByType($arrtmp['n'], 'l');
                }
                if (empty($arrtmp['s'])) {
                    $arrtmp['s'] = CommonFuncService::getPicByType($arrtmp['n'], 's');
                }
                if (empty($arrtmp['t'])) {
                    $arrtmp['t'] = CommonFuncService::getPicByType($arrtmp['n'], 't');
                }
                $resources[$k1]['img'] = json_encode($arrtmp);
            }
            //获取各种尺寸的图片，l s t,n是必须有的            
            $data['models'][$key]['resources'] = $resources;
        }
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['sname'] = $sname;
        return $this->controller->render('index', $data);
    }

}
