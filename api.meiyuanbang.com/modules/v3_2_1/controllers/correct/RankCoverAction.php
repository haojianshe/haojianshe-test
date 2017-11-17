<?php

namespace api\modules\v3_2_1\controllers\correct;

use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectService;

/**
 * 获取榜单如何色彩 素描 速写 排名
 *
 */
class RankCoverAction extends ApiBaseAction {

    public function run() {

        //获取当天时间戳
        $timestamp = strtotime(date('Y-m-d'));
        $ret['content'] = (array) [];
        //传递素描 色彩 速写
        $array = [1, 4, 5];
        foreach ($array as $key => $val) {
            $data[$key] = CorrectService::getNewRank($val, 1, 1, 0, 0, false, $timestamp, date('Y'), '');  //获取数据
        }
        if ($data) {
            foreach ($data as $k => $v) {
                foreach ($v['data'] as $kk => $vv) {
                    $ret['content'][$k] = CorrectService::getFullCorrectInfo($vv, $this->_uid);
                }
            }
        }
        if ($ret) {
            $newArray = [];
            foreach ($ret['content'] as $kv => $vk) {
                $newArray[] = [
                    'f_catalog_id' => $vk['f_catalog_id'],
                    'sname' => $vk['submit_info']['sname'],
                    'avatar' => $vk['submit_info']['avatar']
                ];
            }
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $newArray);
    }

}
