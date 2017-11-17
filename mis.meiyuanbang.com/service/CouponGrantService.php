<?php

namespace mis\service;

use Yii;
use common\models\myb\CouponGrant;
use yii\data\Pagination;
use mis\service\UserService;
class CouponGrantService extends CouponGrant {
     
    /**
     * 分页获取
     */
    public static function getByPage($couponid) {
        $query = parent::find()->alias("a")->where(["<>","a.status",3])->andWhere(['a.couponid'=>$couponid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据      
        $rows = $query->select("a.*,b.mis_username as grantuser,c.mis_username as audituser,")
                ->where(["<>","a.status",3])
                ->andWhere(['a.couponid'=>$couponid])
                ->leftJoin("myb_mis_user b","b.mis_userid=a.mis_userid_grant")
                ->leftJoin("myb_mis_user c","c.mis_userid=a.mis_userid_audit")
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('coupongrantid DESC')
                ->asArray()
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }
    
    public static function getUserList($coupongrantid){
        $model=self::find()->where(['coupongrantid'=>$coupongrantid])->asArray()->one();
        //如果是预发放则根据手机号查找
        if($model['granttype']==0){
        	$ret=UserService::getInfoByUids($model['uids'], 1000);
        }
        else {
        	$ret=UserService::getByMobile(trim($model['mobiles'], ',') , 1000);
        }        
        return $ret;
    }    
}
