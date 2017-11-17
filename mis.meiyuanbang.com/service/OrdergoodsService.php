<?php
namespace mis\service;
use Yii;
use yii\data\Pagination;

use common\models\myb\Ordergoods;
use common\models\myb\Orderinfo;

/**
 * 
 * @author ihziluoh
 * 
 * 订单商品
 */
class OrdergoodsService extends Ordergoods {

	public static function getByPage($orderid) {
        $query = (new \yii\db\Query())->from(parent::tableName(). ' as a')->select("a.*,b.*")->innerJoin("ci_user_detail as b",'a.uid=b.uid')->where(['orderid'=>$orderid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据     
        $rows = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('recid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }
}