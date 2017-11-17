<?php

namespace console\service;

use Yii;
use common\models\myb\CouponGrant;

class CouponGrantService extends CouponGrant {    
    /**
     * 获取所有未分配完的预分配课程卷发放记录
     */
    public static function getPreGrant() {
        $ret = parent::find()
        		->where(['status'=>2])
        		->andWhere('waiting_grant_mobiles is not null')
        		->andWhere(["<>","waiting_grant_mobiles",''])
                ->all();
        return $ret;
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
