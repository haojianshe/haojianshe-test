<?php

namespace mis\controllers\turntable;

use Yii;
use yii\base\Action;
use mobile\service\DkActivityService;

class PrizeshowAction extends Action {

    public function run() {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $gameid = $request->get('gameid');
        }
        if (empty($gameid)) {
            die('参数错误');
        }
        //不同活动下面的不同的奖品
        $data['prizelist'] = DkActivityService::getPrizeGameidList($gameid);
        return $this->controller->render('luckpageshow', $data);
    }

}
