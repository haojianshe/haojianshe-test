<?php

namespace mis\service;

use Yii;
use common\models\myb\AdvResource;
use common\models\myb\AdvRecordPlace;

use yii\data\Pagination;
use mis\service\AdvRecordService;
use common\service\DictdataService;

class AdvResourceService extends AdvResource {
	 /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage($advuid) {
        $query = parent::find()->where(["status"=>2])->andWhere(['advuid'=>$advuid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据      
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName() )
                ->where(['status' => 2])
                ->andWhere(['advuid'=>$advuid])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('advid DESC')
                ->all();

        foreach ($rows as $key => $value) {
            //查找所有投放记录
            $records=AdvRecordService::getAdvRecByAdvid($value['advid']);

            $rows[$key]['catalog']=[];
            $rows[$key]['provice']='';
            $rows[$key]['etime']=0;
            if($records){
                $rows[$key]['etime']=$records[0]['etime'];
                $ret_catalog=[];
                foreach ($records as $key1 => $value1) {     

                    //格式化分类
                    $catalog=AdvRecordService::getCatalogById($value1['pos_type'].'-'.$value1['adv_f_catalog_id'].'-'.$value1['adv_s_catalog_id'].'-'.$value1['adv_t_catalog_id']);
                    $ret_catalog_item=$catalog['typename'].'-'.$catalog['fcatlog'].'-'.$catalog['scatlog'];
                    if(array_key_exists('tcatlog', $catalog) && $catalog['tcatlog']){
                        $ret_catalog_item.='-'.$catalog['tcatlog'];
                    }else{
                        $ret_catalog_item.='-'.$value1['sortid'];
                    }
                    $ret_catalog[]=$ret_catalog_item;
                }
                $rows[$key]['catalog']=$ret_catalog;
                $place=AdvRecordPlace::find()->where(['advrecid'=>$records[0]['advrecid']])->andWhere(['status'=>1])->all();
                //格式化地理位置
                $ret_place=[];
                foreach ($place as $key2 => $value2) {
                    $ret_place[]=DictdataService::getUserProvinceById($value2['provinceid']);
                }
                $rows[$key]['provice']=implode(",", $ret_place);
            }
            
        }
        return ['models' => $rows, 'pages' => $pages];
    }
}
