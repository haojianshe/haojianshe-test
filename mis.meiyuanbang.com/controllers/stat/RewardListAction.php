<?php

namespace mis\controllers\stat;

use Yii;
use mis\components\MBaseAction;
use mis\service\CorrectRewardService;

/**
 * 打赏列表统计
 */
class RewardListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_stat';

    public function run() {
        $request = Yii::$app->request;
       
        $search['stime'] = $request->get("stime");
        $search['etime'] = $request->get("etime");
        $search['teachername'] = $request->get("teachername");
        $search['subjecttype'] = $request->get("subjecttype");
        
        $data['models'] = [];
        if ($search['stime'] || $search['etime']) {
           $data =  CorrectRewardService::getTeacherGiftList($search);
        }
        if (empty($search['stime']) && empty($search['etime'])) {
            $search['stime'] = date("Y-m-d 00:00:00", strtotime("-30 day"));
            $search['etime'] = date('Y-m-d 00:00:00', strtotime("+1 day"));
        }
        $data['search'] = $search;
      
        return $this->controller->render('rewardlist', $data);
    }

}
