<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\MybLkPaperPic;

/**
 * 联考试卷列表页面
 */
class LkPaperPicService extends MybLkPaperPic {

    /**
     * 分页获取所有联考文章列表页面
     */
    public static function getByPage($type, $dafentype, $fendang = 0, $dangid, $daf) {

        if ($fendang == 10 || $fendang == 0) {
            $fendang = " and mlpp.level is null";
        } else {
            $fendang = " and mlpp.level=$fendang";
        }

        //统计数据条数
        $sql = "SELECT count(*) as count from myb_lk as ml 
                   INNER JOIN myb_lk_paper as mlp on ml.lkid=mlp.lkid
                   INNER JOIN myb_lk_paper_pic mlpp on mlp.paperid=mlpp.paperid 
                   where ml.provinceid={$_SESSION['cityid']} and  mlpp.zp_type=$type and mlpp.status=1 and mlp.status=1 $fendang";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $count = $command_count->queryAll();
        $pages = new Pagination(['totalCount' => $count[0]['count'], 'pageSize' => 100]);
        //获取数据
        $page_ruls = " limit " . $pages->limit . " offset " . $pages->offset;
        //查找
        $query = "SELECT ml.lkid,mlpp.picid,mlpp.img_json,mlpp.score,mlpp.`level` from myb_lk as ml 
                  INNER JOIN myb_lk_paper as mlp on ml.lkid=mlp.lkid
                   INNER JOIN myb_lk_paper_pic mlpp on mlp.paperid=mlpp.paperid 
                   where ml.provinceid={$_SESSION['cityid']} and mlpp.zp_type=$type and mlpp.status=1 and mlp.status=1   $fendang  $page_ruls";
        $command = $connection->createCommand($query);
        $models['data'] = $command->queryAll();
        # print_R($models);
        if (!empty($models['data'])) {
            foreach ($models['data'] as $key => $val) {
                $models['data'][$key]['img_json'] = json_decode($val['img_json'], 1)['n']['url'];
            }
        }
        $models['type'] = $type;
        $models['select_fendang_val'] = $dafentype;
        if (substr($fendang, -1) > 0) {
            $num = substr($fendang, -1);
        } else {
            $num = 0;
        }
        $models['select_dafen_val'] = $num;
        $models['dangid'] = $dangid;
        $models['daf'] = $daf;
        return ['models' => $models, 'pages' => $pages];
    }

    /**
     * 根据不同的分档来分档
     * @param type $choose
     * @param type $statuc
     */
    static function setPrice($choose, $statuc) {
        # $picdata = MybLkPaperPic::find()->where(['in', 'picid', explode(',', $choose)])->asArray()->createCommand()->getRawSql();
        #$res=DkCorrectService::updateAll(["add_zan_time"=>$model->add_zan_time,"add_zan_count"=>$model->add_zan_count],['in','dkcorrectid',explode(",", $request->get('dkcorrectid'))]);
        return MybLkPaperPic::updateAll(["level" => $statuc, "ctime" => time()], ['in', 'picid', explode(',', $choose)]); //->createCommand()->getRawSql();
    }

    /**
     * 统计各个分档数量
     */
    static function statisticalData($type=1) {
        $query = "select paperid from myb_lk as ml inner join myb_lk_paper as  mlp on ml.lkid=mlp.lkid where mlp.status=1 and  provinceid={$_SESSION['cityid']}";
        $connection = Yii::$app->db; //连接
        $res = $connection->createCommand($query);
        $lkids = $res->queryAll();

        if (!empty($lkids)) {
            foreach ($lkids as $v) {
                $lkidStr[] = implode(',', $v);
            }
            $newLkids = implode(',', $lkidStr);
            $sql = "select count(*) as count,`level` from myb_lk_paper_pic where paperid in ($newLkids) and zp_type=$type GROUP BY `level`";
            $command_count = $connection->createCommand($sql);
            $count = $command_count->queryAll();
            return $count;
        }
    }

}
