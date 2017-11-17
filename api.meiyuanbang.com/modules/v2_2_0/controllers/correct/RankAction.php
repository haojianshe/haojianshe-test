<?php
namespace api\modules\v2_2_0\controllers\correct;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectService;

/**
 * 排行榜首页
 * @author Administrator
 * '1' => "色彩", '4' => "素描",'5' => "速写"
 */
class RankAction extends ApiBaseAction{
    public  function run(){
        $drawing = [];
        $sketch = [];
        $color = [];
        //分别获取详情 
        //素描id为4，1代表日排行
        $data =  CorrectService::getRank(4, 1, 3, 0, 0)['data'];
        foreach ($data as $key => $value) {
        	$drawing[]=CorrectService::getFullCorrectInfo($value,$this->_uid);
        }
        //色彩
        $data =  CorrectService::getRank(1, 1, 3, 0, 0)['data'];
        foreach ($data as $key => $value) {
        	$color[]=CorrectService::getFullCorrectInfo($value,$this->_uid);
        }
        //速写
        $data =  CorrectService::getRank(5, 1, 3, 0, 0)['data'];
        foreach ($data as $key => $value) {
            $sketch[]=CorrectService::getFullCorrectInfo($value,$this->_uid);
        }
        //返回数据
        $ret['drawing']=$drawing;
        $ret['sketch']=$sketch;
        $ret['color']=$color;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}