<?php

namespace mis\controllers\reward;

use Yii;
use yii\base\Action;
use mobile\service\DkActivityService;
use api\service\UserDetailService;
use api\service\TeamInfoService;
use mobile\service\DkModulesService;
use mobile\service\DkCorrectService;

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
