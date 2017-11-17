<?php

namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeService;
use common\service\DictdataService;

/**
 * 首页推荐位列表页
 */
class CorrectAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_posid';

    public function run() {
        $request = Yii::$app->request;
        //分页获取文章评论列表页面
        $channelid = $request->get('channelid');
        if ($channelid==4) {
            $moedls = PosidHomeService::findAll(['status' => 0, "channelid" => 4]);
            $channelid = 4;
        } else {
            //首页
            $moedls = PosidHomeService::findAll(['status' => 0, "channelid" => 3]);
        }
        //处理类型
        foreach ($moedls as $k => $v) {
            $v['typeid'] = DictdataService::getPosidHomeTypeById($v['typeid'])['typename'];
            $moedls[$k] = $v;
        }
        return $this->controller->render('correct', ['models' => $moedls,'channelid'=>$channelid]);
    }

}
