<?php

namespace mis\controllers\material;

use Yii;
use mis\components\MBaseAction;
use mis\service\MatreialSubjectService;

/**
 * 
 */
class InsertAction extends MBaseAction {

    public $resource_id = 'operation_material';

    public function run() {
        //排序
        $request = Yii::$app->request;
        $subjectArray = $this->sort_with_keyName($_POST['name'], 'desc');
        $str = '';
        foreach ($subjectArray as $key => $val) {
            $str .=$key . ',';
        }
        $str = substr($str, 0, strlen($str) - 1);

        $sql = "UPDATE `myb_material_subject` SET `rids` = '" . $str . "' WHERE `subjectid` =  " . $_POST['id'];
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $command_count->query();
        #去除缓存
        #去掉排序的缓存
        $redis = Yii::$app->cache;
        $redis->delete("material_subject_detail" . $_POST['id']);
        $ret['isclose'] = true;
        $ret['msg'] = '保存成功';
        return $this->controller->render('sort', $ret);
    }

    //排序功能
    private function sort_with_keyName($arr, $orderby = 'desc') {
        $new_array = array();
        $new_sort = array();
        foreach ($arr as $key => $value) {
            $new_array[] = $value;
        }
        if ($orderby == 'asc') {
            asort($new_array);
        } else {
            arsort($new_array);
        }
        foreach ($new_array as $k => $v) {
            foreach ($arr as $key => $value) {
                if ($v == $value) {
                    $new_sort[$key] = $value;
                    unset($arr[$key]);
                    break;
                }
            }
        }
        return $new_sort;
    }

}
