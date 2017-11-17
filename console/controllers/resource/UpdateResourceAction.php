<?php

namespace console\controllers\resource;

use Yii;
use api\lib\enumcommon\ReturnCodeEnum;
use yii\base\Action;

/**
 * 
 */
class UpdateResourceAction extends Action {

    public static function run() {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $connection = Yii::$app->db;
        $sql = "select teacher_uid,rid from myb_correct_teacher_pic where f_catalog_id=0"; #  GROUP BY rid
        $command = $connection->createCommand($sql);
        $array = [];
        $data = $command->queryAll();
        foreach ($data as $key => $val) {
            $query = "select f_catalog_id,s_catalog_id from myb_correct where  find_in_set('{$val['rid']}',example_pics)  limit 1;"; #example_pics in ('{$val['rid']}') limit 1";//
            $comm = $connection->createCommand($query);
            $rest = $comm->queryOne();
            if (!empty($rest['f_catalog_id'])) { 
                #修改已经获取到的一级二级分类
                $sqls = 'update myb_correct_teacher_pic set f_catalog_id=' . $rest['f_catalog_id'] . ',s_catalog_id=' . $rest['s_catalog_id'] . ' where   teacher_uid=' . $val['teacher_uid'].' and rid=' . $val['rid'] ;
                $comman = $connection->createCommand($sqls);
                $comman->execute();
            }
        }
    }

//    public static function run() {
//        set_time_limit(0);
//        ini_set('memory_limit', '2024M');
//        #$sql = "select a.rid,a.img from myb_correct_teacher_pic as b left join  ci_resource as a on a.rid=b.rid where  md5_string=''";
//        $sql = "select a.rid,a.img,a.md5_string from myb_correct_teacher_pic as b left join  ci_resource as a on a.rid=b.rid where a.rid not in (10673,10691,10725,10777,10779)  and md5_string is null ";
//        $connection = Yii::$app->db;
//        $command = $connection->createCommand($sql);
//        $data = $command->queryAll();
//        $arr = [];
//        foreach ($data as $key => $val) {
//            $arr[$val['rid']] = json_decode($val['img'], 1)['n']['url'];
//        }
//        foreach ($arr as $key => $val) {# .  .
//            $str = md5_file($val);
//            $sql = "update ci_resource set md5_string='$str' where rid=" . $key;
//            $comman = $connection->createCommand($sql);
//            $comman->execute();
//        }
//    }
}
