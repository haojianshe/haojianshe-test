<?php
namespace api\service;
use Yii;
use common\models\myb\Ordergoods;
use common\models\myb\Orderinfo;

/**
 * 
 * @author ihziluoh
 * 
 * 订单商品
 */
class OrdergoodsService extends Ordergoods {
    /**
     * 得到订单商品列表
     * @param  [type] $orderid   [description]
     * @param  [type] $uid       [description]
     * @param  [type] $subjectid [description]
     * @param  [type] $subjectid [description]
     * @return [type]            [description]
     */
    public static function getOrderGoodsDetail($orderid,$uid,$subjecttype){
        $goods=self::find()->where(['uid'=>$uid])->andWhere(['subjecttype'=>$subjecttype])->andWhere(['orderid'=>$orderid])->all();
        return $goods;
    }
    /**
     * 增加订单商品记录 1/已购买 2/保存成功 3/保存失败
     */
    public static function addOrderGood($orderid,$uid,$subjecttype,$subjectid,$fee,$remark){
        $find=self::find()->alias("a")->where(['a.uid'=>$uid])->andWhere(['a.subjecttype'=>$subjecttype])->andWhere(['subjectid'=>$subjectid])->innerJoin(Orderinfo::tableName()." as b","a.orderid=b.orderid")->andWhere(['b.status'=>1])->one();
        if($find){  
            return 1;
        }
        $model=new OrderGoods;
        $model->orderid=$orderid;// '订单号' ,
        $model->uid=$uid;// '购买用户，冗余字段' ,
        $model->subjecttype=$subjecttype;// '购买实体类型' ,
        $model->subjectid=$subjectid;// '购买实体id' ,
        $model->fee=$fee;// '费用' ,
        $model->remark=$remark;// '备注' ,
        $ret=$model->save();
        if($ret){
            return 2; 
        }else{
            return 3;
        }
    }
    /**
     * 得到商品购买状态 订单类型 :1直播  2点播 1/2 未购买/已购买
     * @param  [type] $uid         [description]
     * @param  [type] $subjecttype [description]
     * @param  [type] $subjectid   [description]
     * @return [type]              [description]
     */
    public static function getByGoodStatus($uid,$subjecttype,$subjectid){
        $find=self::find()->alias("a")->where(['a.uid'=>$uid])->andWhere(['a.subjecttype'=>$subjecttype])->andWhere(['subjectid'=>$subjectid])->innerJoin(Orderinfo::tableName()." as b","a.orderid=b.orderid")->andWhere(['b.status'=>1])->one();
        if($find){  
            //已购买
            return 2;
        }else{
            //未购买
            return 1;
        }
    }
    /**
     * 得到订单商品列表通过价格排序
     * @param  [type] $orderid   [description]
     * @param  [type] $uid       [description]
     * @param  [type] $subjectid [description]
     * @param  [type] $subjectid [description]
     * @return [type]            [description]
     */
    public static function getOrderGoodsByPrice($orderid,$uid,$subjecttype){
        $goods=self::find()->where(['uid'=>$uid])->andWhere(['subjecttype'=>$subjecttype])->andWhere(['orderid'=>$orderid])->orderBy("fee desc")->all();
        return $goods;
    }
}
