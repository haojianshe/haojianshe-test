<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\MybLkPaper;
use common\models\myb\MybLkPaperPic;
use common\service\DictdataService;
use common\models\myb\MybLk;

/**
 * 联考试卷列表页面
 */
class MybLkPaperService extends MybLkPaper {

    /**
     * 分页获取所有联考文章列表页面
     */
    public static function getByPage($lkid) {
        $connection = Yii::$app->db; //连接
        $query = (new \yii\db\Query())
                ->select(['count(*)'])
                ->from('myb_lk as ml')
                ->innerJoin('myb_lk_paper as mlp', 'ml.lkid=mlp.lkid')
                ->innerJoin('ci_user_detail as cud', 'mlp.uid=cud.uid')
                ->where(['ml.status' => 1])
                ->andWhere(['ml.lkid' => $lkid])
                ->andWhere(['mlp.status' => 1]); //->createCommand()->getRawSql();
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        $models = (new \yii\db\Query())
                ->select(['ml.lkid', 'mlp.user_name', 'cud.sname', 'mlp.studio_name', 'mlp.paperid', 'mlp.professionid', 'mlp.ctime'])
                ->from('myb_lk as ml')
                ->innerJoin('myb_lk_paper as mlp', 'ml.lkid=mlp.lkid')
                ->innerJoin('ci_user_detail as cud', 'mlp.uid=cud.uid')
                ->where(['ml.status' => 1])
                ->andWhere(['ml.lkid' => $lkid])
                ->andWhere(['mlp.status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('mlp.ctime desc')
                ->all();
        foreach ($models as $key => $val) {
            $models[$key]['data'] = MybLkPaperPic::find()->select('zp_type,img_json,score')->where(['paperid' => $val['paperid']])->Asarray()->all();
        }
        foreach ($models as $k => $v) {
            foreach ($v['data'] as $kk => $vv) {
                $models[$k]['count']+=$vv['score'];
            }
        }
        return ['models' => $models, 'pages' => $pages];
    }

    /*
     * 获取已经选中的数据
     */

    public static function getSelectData($lkid, $type) {
        $connection = Yii::$app->db; //连接
        $query = "SELECT mn.newsid,mn.title,mn.username,mn.ctime FROM `myb_news` as mn  "
                . "INNER JOIN myb_activity_article as maa on mn.newsid=maa.newsid "
                . "inner join `myb_lk_material_relation` as mlmr on mlmr.newsid=maa.newsid "
                . "inner join `myb_lk` as ml on ml.lkid=mlmr.lkid"
                . " where mlmr.zp_type in ($type) and  ml.lkid=$lkid and mn.status=0";
        $command = $connection->createCommand($query);
        return $command->queryAll();
    }

    /**
     * 获取联考活动城市列表
     */
    public static function CityList() {
        $models = MybLk::find()->select('provinceid')->where(['status' => 1])->groupBy('provinceid')->Asarray()->all();
        $data = DictdataService::getProvince();
        $cityArray = [];
        foreach ($models as $key => $val) {
            foreach ($data as $k => $v) {
                if ($val['provinceid'] == $v['provinceid']) {
                    $cityArray[$val['provinceid']] = $v['provincename'];
                }
            }
        }
        return $cityArray;
    }

}
