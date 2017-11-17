<?php
namespace mis\service;
use Yii;
use common\models\myb\HomePopAdv;
use yii\data\Pagination;
use common\service\DictdataService;

class HomePopAdvService extends HomePopAdv {
	 /**
     * 分页获取
     * */
    public static function getByPage() {
        $query = parent::find()->where(["status"=>0]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据      
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName())
                ->where(['status' => 0])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('advid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    public static function getProfessionameByIds($ids_str){
        $ids_arr=explode(",", $ids_str);
        $ret_name=[];
        foreach ($ids_arr as $key => $value) {
            $ret_name[]=DictdataService::getProfessionById($value);
        }
        return implode(",", $ret_name);
    }

    public static function getProvinceameByIds($ids_str){
        $ids_arr=explode(",", $ids_str);
        $ret_name=[];
        foreach ($ids_arr as $key => $value) {
            $ret_name[]=DictdataService::getUserProvinceById($value);
        }
        return implode(",", $ret_name);
    }
}
