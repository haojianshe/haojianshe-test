<?php

namespace mis\controllers\turntable;

use Yii;
use mis\components\MBaseAction;
use mis\service\TurntableGameService;
use mis\service\TurntablePrizeService;
use mis\service\TurntableGamePrizesService;

/**
 * 活动添加和修改页面
 */
class PrizeeditAction extends MBaseAction {

    public $resource_id = 'operation_activity';
    //活动在news表中的catid值
    private $activitycatid = 2;

    public function run() {
        $request = Yii::$app->request;

        if (!$request->isPost) {
            //处理get请求
            $ret = $this->getHandle();
        } else {
            //处理post请求
            $ret = $this->postHandle();
        }
        return $this->controller->render('prizeedit', $ret);
    }

    /**
     * 处理get访问的情况
     */
    private function getHandle() {
        $request = Yii::$app->request;
        //判断参数
        $newsid = $request->get('newsid');
        if ($newsid) {
            //编辑
            if (!is_numeric($newsid)) {
                die('非法输入');
            }
        } else {
            //新添加
            $newsid = 0;
        }
        $ret = $this->getRetModel($newsid, $request->get('i'));

        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $request = Yii::$app->request;
        $msg = '';
        $usermodel = \Yii::$app->user->getIdentity();
        //先获取model
        $model = new TurntableGameService();
        //修改
        if ($request->post('isedit') == 1) {
            $gameid = $request->post('gameid');
            $model = TurntableGameService::findOne(['gameid' => $gameid]);
            $model->title = $request->post('title');
            $model->ctime = time();
            $model->status = 1;
            $model->save();
            $connection = Yii::$app->db;
            $str = '';
            foreach ($request->post('rewardone') as $k => $v) {
                $str .="UPDATE myb_turntable_game_prizes set probability_start = '{$v['probability_start']}',probability_end='{$v['probability_end']}',num='{$v['num']}',sort='{$v['sort']}' where prizesid='{$v['prizesid']}' and gameid=$gameid ;";
            }
            $commandSql = $connection->createCommand($str);
            $commandSql->execute();
            $ret['msg'] = '保存成功';
            $ret['isclose'] = true;
        } else {
            //添加
            $model->load($request->post());
            $model->title = $request->post('title');
            $model->ctime = time();
            $model->status = 1;
            if ($model->save() && $model->attributes['gameid']) {
                foreach ($request->post('rewardone') as $key => $val) {
                    $PrizesModel = new TurntableGamePrizesService();
                    $PrizesModel->gameid = $model->attributes['gameid'];
                    $PrizesModel->prizesid = $val['prizesid'];
                    $PrizesModel->num = $val['num'];
                    $PrizesModel->prize_num = $val['num'];
                    $PrizesModel->probability_start = $val['probability_start'];
                    $PrizesModel->probability_end = $val['probability_end'];
                    $PrizesModel->sort = $val['sort'];
                    $PrizesModel->status = 1;
                    $PrizesModel->save();
                }
                $ret['msg'] = '保存成功';
                $ret['isclose'] = true;
            } else {
                $ret['msg'] = '保存失败';
            }
        }

        return $ret;
    }

    /**
     * 根据newsid获取活动model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回活动编辑页的model
     */
    private function getRetModel($newsid, $viewId = '') {
        $ret['rewardSelect'] = $this->GetReward();
        if ($newsid == 0) {
            $ret['activitymodel'] = '';
        } else {
            $ret = TurntableGamePrizesService::getRewardList($newsid);
            $ret['models']['viewId'] = $viewId;
        }
        return $ret;
    }

    /**
     * 获取奖品表中所有的奖品，来匹配单个活动列表
     * @param 
     * @return array 奖品表中所有奖品
     * 
     */
    private function GetReward() {
        return TurntablePrizeService::find()->select('prizesid,title,img')->asArray()->all();
    }

}
