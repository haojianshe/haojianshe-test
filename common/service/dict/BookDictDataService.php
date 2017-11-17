<?php

namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 用于美院帮图书推荐
 * 美院帮图书推荐字典数据
 */
class BookDictDataService extends Object {

    //*******************************************************tweet type begin
    /**
     * 获取全部图书主类型
     * @return multitype:number string
     */
    static function getBookMainType() {
        $ret = [
            '1' => "色彩",
            '2' => "设计",
            '3' => "照片",
            '4' => "素描",
            '5' => "速写",
            '6' => "创作"
        ];
        return $ret;
    }

    /**
     * 根据书籍类型获取对应名称
     * @param unknown $maintypeid
     * @return Ambigous <multitype:number , multitype:multitype:number string  >|boolean
     */
    static function getBookMainTypeById($maintypeid) {
        $ret = self::getBookMainType();
        foreach ($ret as $k => $v) {
            if ($k == $maintypeid) {
                return $v;
            }
        }
        return null;
    }

    /**
     * 获取全部图书 子类型
     * @return multitype:number string
     */
    static function getBookSubType() {
        $ret = [
            '1' => [
                '100' => "静物",
                '101' => "静物单体",
                '102' => "头像",
                '103' => "风景",
                '104' => "大师作品",
                '105' => "小色稿",
                '106' => "单色塑造"],
            '2' => [
                '107' => "单体装饰画",
                '108' => "主题装饰画",
                '109' => "单体创意速写",
                '110' => "主题创意速写",
                '111' => "字体设计",
                '112' => "设计素描",
                '113' => "设计色彩",
                '114' => "平面设计",
                '115' => "立体构成"],
            '3' => [
                '116' => "静物",
                '117' => "场景",
                '118' => "人物",
                '119' => "动物",
                '120' => "天气",
                '121' => "时间",
                '122' => "节日"],
            '4' => [
                '123' => "单体几何",
                '124' => "组合几何",
                '125' => "单体静物",
                '126' => "组合静物",
                '127' => "石膏五官",
                '128' => "石膏像",
                '129' => "头像",
                '130' => "半身像",
                '131' => "人体解剖",
                '132' => "大师作品"],
            '5' => [
                '133' => "人物速写",
                '134' => "人物局部速写",
                '135' => "动态快写",
                '136' => "人体结构",
                '137' => "场景速写",
                '138' => "命题速写",
                '139' => "大师作品"],
            '6' => [
                '140' => "材料",
                '141' => "颜色",
                '142' => "场景",
                '143' => "天气",
                '144' => "时间",
                '145' => "节日"
            ]
        ];
        return $ret;
    }

    /**
     * 获取书籍二级分类
     * @param unknown $mainTypeid
     * @param unknown $subTypeid
     */
    static function getBookSubTypeById($mainTypeid, $subTypeid) {
        $ret = self::getBookSubType($mainTypeid);
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
     * 功能：菜单和列表按钮生成函数
     * 参数：
     * @param $Name		按钮的名称，如果是列表使用数组型名称
     * @param $ArrVal		按钮对应的数据来原数组
     * @param $selected=0	指定勾选项，默认是第一个元素
     * @param $menu=0		为0时是菜单按钮，是数字时是列表按钮，同这个数字控制多少行
     * @param example：
     * @param $jiguang=array("北京市","上海市","天津市","重庆市","山东省","广东省","河北省","河南省","安徽省");
     * 菜单：
     * echo select("jiguang",$jiguang,3);
     * 列表：
     * echo select("jiguang[]",$jiguang,"0|1|5|6",5);
     */
    static public function createMenuList($Name, $ArrVal, $selected = 0, $Id = 0, $menu = 0, $ajax = 0, $class = "", $disabled = "", $insert = "") {
        if (!is_array($ArrVal)) #
            return false;
        $selStr = "<select id=\"$Id\"  name=\"$Name\" ";
        if ($menu)
            $selStr.=" size=\"$menu\" multiple=\"multiple\"";
        if ($ajax)
            $selStr.=$ajax;
        if ($class)
            $selStr.=" class=$class ";
        if ($disabled)
            $selStr .=" disabled='disabled' ";
        $selStr.=" > $insert \n\r ";
        foreach ($ArrVal as $key => $value) {
            $selStr.="<option value=\"$key\" ";
            //允许多选的操作
            if (!$menu) {
                if ($selected == $key)
                    $selStr.='selected="selected" '; //控制勾选
            } else {
                if (eregi($selected, $key))
                    $selStr.='selected="selected" '; //控制勾选
            }
            $selStr.=">$value</option> \n\r";
        }
        $selStr.="</select>";
        return $selStr;
    }

    /**
     * 功能：单选按钮或复选按钮生成函数
     * 参数：
     * @param $Name		按钮的名称，如果复选按钮得使用数组型的名称：name[]
     * @param $ArrVal		按钮对应的数据来原数组
     * @param $type		指是单选按钮（radio）还是复选按钮（checkbox）
     * @param $checked=0	指定勾选项，默认是第一个元素 如果是复选：0|1|2
     * 例子：example
     * $ArrVal=array("美国","英国","法国","德国");
     * 单选：
     * echo "你喜欢西方哪个国家：<br>";
     * echo radChe("love",$ArrVal,"radio",2);
     * 复选：
     * echo "你喜欢西方哪个国家：<br>";
     * echo radChe("like[]",$ArrVal,"checkbox","0|2|3");
     */

    static public function radioChechbox($Name, $ArrVal, $type = "radio", $checked = "N", $num = 0, $id = "") {
        $chec = preg_split("/\||,/", $checked);
        $tmparr = array();
        //取数组中的数据项内容	
        foreach ($ArrVal as $key => $value) {
            $formStr.='<input type="' . $type . '" ';
            $formStr.='name="' . $Name . '" ';
            if ($id)
                $formStr.='id="' . $id . '" ';
            if ($checked != "N") {
                switch ($type) {
                    case "radio"://单选按钮的勾选
                        if ($checked == $key)
                            $formStr.='checked="checked" '; //控制勾选
                        break;
                    case "checkbox"://复选按钮的勾选
                        if (in_array($key, $chec))
                            $formStr.='checked="checked" ';
                        break;
                }
            }
            $formStr.='value="' . $key . '" />';
            $formStr.=$value . "\n\r"; //"\n\r"表示换行
            $tmparr[] = $formStr;
            unset($formStr);
            //if($i%$num==0) $formStr.="<br />";
        }
        //$num是控制换行，并用表布局，$num是列数
        if ($num != 0) {
            $tmpS = "<table><tr>";
            foreach ($tmparr as $tmpStr) {
                $i++;
                $tmpSt.="<td>" . $tmpStr . "</td>";
                if ($i % $num == 0)
                    $tmpSt.="</tr><tr>";
            }
            $tmpS.=$tmpSt . "</tr></table>";
        }
        else {
            $tmpS = join(" ", $tmparr);
        }
        return $tmpS;
    }
    
    
        /**
     * 功能：单选按钮或复选按钮生成函数
     * 参数：
     * @param $Name		按钮的名称，如果复选按钮得使用数组型的名称：name[]
     * @param $ArrVal		按钮对应的数据来原数组
     * @param $type		指是单选按钮（radio）还是复选按钮（checkbox）
     * @param $checked=0	指定勾选项，默认是第一个元素 如果是复选：0|1|2
     * 例子：example
     * $ArrVal=array("美国","英国","法国","德国");
     * 单选：
     * echo "你喜欢西方哪个国家：<br>";
     * echo radChe("love",$ArrVal,"radio",2);
     * 复选：
     * echo "你喜欢西方哪个国家：<br>";
     * echo radChe("like[]",$ArrVal,"checkbox","0|2|3");
     */
    static public function radioChechboxTwo($Name, $ArrVal, $type = "radio", $checked = "N", $num = 0, $id = "") {
        $chec = preg_split("/\||,/", $checked);
        $tmparr = array();
        //取数组中的数据项内容	
        foreach ($ArrVal as $key => $value) {
            $formStr.='<input type="' . $type . '" ';
            $formStr.=' class=checkClass name="' . $Name . '" ';
            if ($id)
                $formStr.='id="' . $id . '" ';
            if ($checked != "N") {
                switch ($type) {
                    case "radio"://单选按钮的勾选 标签项目中适应标签选择，方法其实是单选，后期用到可以把注释打开，关掉复选
                        if (in_array($key, $chec))
                            $formStr.='checked="checked" ';
                        break;
                    case "checkbox"://复选按钮的勾选
                        if (in_array($key, $chec))
                            $formStr.='checked="checked" ';
                        break;
                }
            }
            $formStr.='value="' . $key . '" />';
            $formStr.=$value . "\n\r"; //"\n\r"表示换行
            $tmparr[] = $formStr;
            unset($formStr);
            //if($i%$num==0) $formStr.="<br />";
        }
        //$num是控制换行，并用表布局，$num是列数
        if ($num != 0) {
            $tmpS = "<table id='ttt'><tr>";
            foreach ($tmparr as $tmpStr) {
                $i++;
                $tmpSt.="<td>" . $tmpStr . "</td>";
                if ($i % $num == 0)
                    $tmpSt.="</tr><tr>";
            }
            $tmpS.=$tmpSt . "</tr></table>";
        }
        else {
            $tmpS = join(" ", $tmparr);
        }
        return $tmpS;
    }

}
