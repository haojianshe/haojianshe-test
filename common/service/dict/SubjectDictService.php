<?php

namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 专题
 */
class SubjectDictService extends Object {

    /**
     * 获取全部专题主类型
     * @return multitype:number string
     */
    static function getSubjectMainType() {
        $ret['s_catalog'] = [
          //  ['id' => '0', "name" => "全部"],
            ['id' => "1", "name" => "最热"],
            ['id' => '2', "name" => "名师"],
            [ 'id' => '3', "name" => "大师"],
            [ 'id' => '4', "name" => "联考"],
            ['id' => '5', "name" => "校考"],
            ['id' => '6', "name" => "名家"]
        ];
        return $ret;
    }

    /**
     * 根据帖子一级分类的名称获取id
     * @return multitype:string
     */
    static function getCorrectMainTypeIdByName($name) {
        $mainmodels = static::getCorrectMainType();
        foreach ($mainmodels as $k => $v) {
            if ($v == $name) {
                return $k;
            }
        }
        return null;
    }

    /**
     * 根据id取name
     * @param unknown $id
     * @return unknown|NULL
     */
    static function getCorrectMainTypeNameById($id) {
        $mainmodels = static::getCorrectMainType();
        foreach ($mainmodels as $k => $v) {
            if ($k == $id) {
                return $v;
            }
        }
        return null;
    }

    /**
     * 获取全部直播子类型
     * @return multitype:number string
     */
    static function getCorrectSubType() {
        $ret = ['1' => [
                '101' => "静物单体",
                '100' => "组合静物", //静物
                '1003' => '场景',
                '102' => "头像",
                '1001' => '半身像',
                '1002' => '全身像',
                '103' => "风景"
            //删除
            //'104' => "大师作品",
            //'105' => "小色稿",
            //'106' => "单色塑造"
            ],
            '2' => [
                '2001' => "设计基础",
                '107' => "单体装饰画",
                '2003' => "黑白装饰画",
                '2002' => "彩色装饰画",
                //'108' => "命题装饰画",//主题装饰画
                '109' => "单体创意速写",
                '110' => "命题创意速写", //主题创意速写
                '111' => "字体设计",
                '112' => "设计素描",
                '113' => "设计色彩",
                '114' => "平面设计",
                '115' => "立体构成"
            ],
            '4' => [
                '123' => "单体几何",
                '124' => "组合几何",
                '125' => "单体静物",
                '126' => "组合静物",
                '127' => "石膏五官",
                '4001' => '石膏解剖',
                '128' => "石膏像",
                '4002' => '人物局部',
                '129' => "头像",
                '130' => "半身像",
                '4003' => '全身像',
                '131' => "人体解剖",
                '4004' => '场景',
                '4005' => '风景建筑',
                '4006' => '动物'
            //删除
            //'132' => "大师作品"
            ],
            '5' => [
                '133' => "人物速写",
                '5001' => '人物半身速写',
                '134' => "人物局部速写",
                '135' => "动态快写",
                '136' => "人体结构",
                '137' => "场景速写",
                '138' => "命题速写",
                '5002' => '风景速写',
                '5003' => '动物',
                '5004' => '道具'
            //删除
            //'139' => "大师作品"
            ]
        ];
        return $ret;
    }

    /**
     * 获取直播二级分类
     * @param unknown $mainTypeid
     * @param unknown $subTypeid
     */
    static function getCorrectSubTypeById($mainTypeid, $subTypeid) {
        $ret = self::getCorrectSubType($mainTypeid);
        $submodels = $ret[$mainTypeid];
        if (!$submodels) {
            return null;
        }
        foreach ($submodels as $k => $v) {
            if ($k == $subTypeid) {
                return $v;
            }
        }
        return null;
    }

    /**
     * 根据二级分类的名称取id
     * @param unknown $maintypeid
     * @param unknown $name
     * @return Ambigous <multitype:number , multitype:string >|NULL
     */
    static function getCorrectSubTypeIdByName($maintypeid, $name) {
        $allmodels = static::getTweetSubType();
        $submodels = $allmodels[$maintypeid];
        if (!$submodels) {
            return null;
        }
        foreach ($submodels as $k => $v) {
            if ($v == $name) {
                return $k;
            }
        }
        return null;
    }

    /**
     * 获取主类型对应的能力模型打分项，id 名称 权重
     * @return multitype:multitype:string
     */
    static function getCorrectScoreItem() {
        $ret = ['1' => [['itemid' => 1, 'itemname' => '构图', 'weight' => 15],
                ['itemid' => 2, 'itemname' => '造型', 'weight' => 15],
                ['itemid' => 3, 'itemname' => '色调', 'weight' => 30],
                ['itemid' => 4, 'itemname' => '明度', 'weight' => 10],
                ['itemid' => 5, 'itemname' => '纯度', 'weight' => 10],
                ['itemid' => 6, 'itemname' => '塑造', 'weight' => 10],
                ['itemid' => 7, 'itemname' => '空间', 'weight' => 10]],
            '2' => [['itemid' => 1, 'itemname' => '创意表达', 'weight' => 20],
                ['itemid' => 2, 'itemname' => '设计构图', 'weight' => 20],
                ['itemid' => 3, 'itemname' => '设计造型', 'weight' => 25],
                ['itemid' => 4, 'itemname' => '画面层次', 'weight' => 20],
                ['itemid' => 5, 'itemname' => '技法', 'weight' => 5],],
            '4' => [['itemid' => 1, 'itemname' => '构图', 'weight' => 10],
                ['itemid' => 2, 'itemname' => '造型', 'weight' => 30],
                ['itemid' => 3, 'itemname' => '结构', 'weight' => 15],
                ['itemid' => 4, 'itemname' => '体积', 'weight' => 15],
                ['itemid' => 5, 'itemname' => '色调', 'weight' => 10],
                ['itemid' => 6, 'itemname' => '质感', 'weight' => 10],
                ['itemid' => 7, 'itemname' => '空间', 'weight' => 10]],
            '5' => [['itemid' => 1, 'itemname' => '构图', 'weight' => 20],
                ['itemid' => 2, 'itemname' => '造型', 'weight' => 30],
                ['itemid' => 3, 'itemname' => '结构', 'weight' => 20],
                ['itemid' => 4, 'itemname' => '头手鞋', 'weight' => 20],
                ['itemid' => 5, 'itemname' => '空间', 'weight' => 10]],
        ];
        return $ret;
    }

    /**
     * 根据主类型获取对应的所有打分项
     * @param unknown $mainId
     */
    static function getCorrectScoreItemByMainId($mainId) {
        $allitem = static::getCorrectScoreItem();
        foreach ($allitem as $k => $v) {
            if ($k == $mainId) {
                return $allitem[$k];
            }
        }
        return null;
    }

    /**
     * 根据打分项的id从列表里取得实体
     * @param unknown $itemid
     */
    static function getCorrectScoreItemByItemid($itemid, $items) {
        foreach ($items as $k => $v) {
            if ($v['itemid'] == $itemid) {
                return $v;
            }
        }
        return null;
    }

    /**
     * 
     * @param unknown $itemid
     * @param unknown $items
     * @return unknown|NULL
     */
    static function getCorrectScoreItemWeightByItemid($itemid, $items) {
        foreach ($items as $k => $v) {
            if ($v['itemid'] == $itemid) {
                return $v['weight'];
            }
        }
        return null;
    }

}
