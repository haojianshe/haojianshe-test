<?php
namespace console\service;

use Yii;
use common\models\myb\Orderinfo;

/**
 * 订单
 */
class OrderinfoService extends Orderinfo
{        
    /**
        得到参加团购用户uid 
    **/
   public static function getGroupBuyUser($groupbuyid){
        $groupbuyusers=self::find()->select("uid")->where(['groupbuyid'=>$groupbuyid])->andWhere(['status'=>1])->asArray()->all();
        $ret_uids=[];
        if($groupbuyusers){
             foreach ($groupbuyusers as $key => $value) {
                $ret_uids[]=$value['uid'];
            }
        }
        return  $ret_uids;
   }
}
