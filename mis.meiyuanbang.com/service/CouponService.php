<?php

namespace mis\service;

use Yii;
use common\models\myb\Coupon;
use yii\data\Pagination;

class CouponService extends Coupon {
     
    /**
     * 分页获取所有课程券
     */
    public static function getByPage($coupon_name) {
        $query = parent::find()->where(["status"=>1]);
        if($coupon_name){
            $query ->andWhere(['like','coupon_name',$coupon_name]);
        }
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据      
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName() )
                ->where(['status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('couponid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }
    
}
