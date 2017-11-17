<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\LkPaperPicService;
use common\models\myb\LkPaper;

/**
 * 修改考试成绩
 */
class UpdatescoreAction extends MBaseAction {

    public $resource_id = 'operation_simulation';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $id = $request->post('id');
        $val = $request->post('val');
        $select_val = $request->post('select_val');

        if (!$id || !is_numeric($id)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = LkPaperPicService::findOne(['picid' => $id, 'level' => $select_val]);



        if ($model) {
            $model->score = $val;
            $ret = $model->save();

            
            #保存成功后修改总分表
            $connection = Yii::$app->db; //连接
            $sql = "select sum(score) as score from myb_lk_paper_pic where paperid={$model->paperid}";
            $command_count = $connection->createCommand($sql);
            $sum = $command_count->queryOne();
            if ($sum['score']) {
                self::getSum($sum['score'], $model->paperid);
            }
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1]);
    }

    static function getSum($sum, $id) {
       
        $model = new LkPaper();
        $model = LkPaper::findOne(['paperid' => $id]);
        $model->total_score = $sum;
        $ret = $model->save();
//        $connection = \Yii::$app->db;
//        $command_hits = $connection->createCommand("UPDATE `myb_lk_paper` SET `total_score` = $sum WHERE `paperid` = $id");
//        return $command_hits->execute();
    }

}
