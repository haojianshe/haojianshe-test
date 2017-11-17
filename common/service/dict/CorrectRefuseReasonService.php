<?php

namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 拒批原因字典数据
 */
class CorrectRefuseReasonService extends Object {

    /**
     * 获取全部拒批原因
     * @return multitype:multitype:number string
     */
    static function getReasonList() {
        $ret = [
        	['reasonid' => 1, 'reasondesc' => '分类有误'],
            ['reasonid' => 2, 'reasondesc' => '图像不清'],
            ['reasonid' => 3, 'reasondesc' => '内容违规'],
            ['reasonid' => 4, 'reasondesc' => '重复提交'],
       		['reasonid' => 5, 'reasondesc' => '不是原创'],
        	/*
       		['reasonid' => 6, 'reasondesc' => '朝向错误'],
       		['reasonid' => 7, 'reasondesc' => '照片透视太大'],
       		['reasonid' => 8, 'reasondesc' => '画面不全'], 
       		['reasonid' => 9, 'reasondesc' => '照片画面太小'],
       		['reasonid' => 10, 'reasondesc' => '一张照片里有多张画']
       		*/
        ];
        return $ret;
    }

    /**
     * 根据id获取实例
     * @return multitype:string
     */
    static function getModelById($resonId) {
        $resonlist = static::getReasonList();
        foreach ($resonlist as $k => $v) {
            if ($v['reasonid'] == $resonId) {
                return $v;
            }
        }
        return null;
    }

    /**
     * 获取老师未批改的分数
     * @param $correct_time 批改时间
     * @param $time         申请批改的时间
     * @return int     不同时间段得分情况不同
     */
    public static function getCorrectScore($correct_time, $time) {
        $correct_time = time();
        //依照现在时间
        if ($time > strtotime(date("Y-m-d 08:30:00", $time)) && $time < strtotime(date("Y-m-d 23:00:00", $time))) {
            //工作时间计算时间差
            $time_diff = $correct_time - $time;
        } else {
            //非工作时间计算时间差
            if ($time >= strtotime(date("Y-m-d 23:00:00", $time))) {
                $time_diff = $correct_time - strtotime(date("Y-m-d 08:30:00", ($time + 24 * 60 * 60)));
            } else {
                $time_diff = $correct_time - strtotime(date("Y-m-d 08:30:00", $time));
            }
        }
        //根据时间差算每个时间段批改数
        //已秒为单位
        if ($time_diff < 30 * 60) {
            return 5;
        } else if ($time_diff >= 30 * 60 && $time_diff < 60 * 60) {
            return 4;
        } else if ($time_diff >= 60 * 60 && $time_diff < 3 * 60 * 60) {
            return 3;
        } else if ($time_diff >= 3 * 60 * 60 && $time_diff < 6 * 60 * 60) {
            return 2;
        } else if ($time_diff >= 6 * 60 * 60) {
            return -1;
        }
    }

}
