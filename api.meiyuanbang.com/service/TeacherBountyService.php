<?php
namespace api\service;
use Yii;
use common\models\myb\TeacherBounty;
/**
* 
*/
class TeacherBountyService extends TeacherBounty
{   
    /**
     * 增加佣金记录
     * @param [type] $teacheruid  [description]
     * @param [type] $bounty_type [description]
     * @param [type] $orderid     [description]
     * @param [type] $submituid   [description]
     * @param [type] $subjecttype [description]
     * @param [type] $subjectid   [description]
     * @param [type] $bounty_fee  [description]
     */
    public static function add($teacheruid,$bounty_type,$orderid,$submituid,$subjecttype,$subjectid,$bounty_fee){
            $model=new TeacherBounty();
            $model->teacheruid =$teacheruid;
            $model->bounty_type =$bounty_type;
            $model->orderid =$orderid;
            $model->submituid =$submituid;
            $model->subjecttype =$subjecttype;
            $model->subjectid =$subjectid;
            $model->bounty_fee =$bounty_fee;
            $model->ctime =time();
            $model->save();
    }
    /**
     * 佣金记录列表
     * @param  [type]  $uid    [description]
     * @param  [type]  $stime  [description]
     * @param  [type]  $etime  [description]
     * @param  [type]  $lastid [description]
     * @param  integer $rn     [description]
     * @return [type]          [description]
     */
    public static function getList($uid,$stime=NULL,$etime=NULL,$lastid=NULL,$rn=10){
        $query=self::find()->select("a.*,b.*")->where(['a.teacheruid'=>$uid])->alias("a")->innerJoin("myb_orderinfo b","a.orderid=b.orderid");
        if($stime){
            $query->andWhere([">","a.ctime",$stime]);
        }
        if($etime){

            $query->andWhere(["<","a.ctime",$etime]);
        }
        if($lastid){
            $query->andWhere(["<","a.teacherbountyid",$lastid]);
        }
        return $query->limit($rn)->orderBy("teacherbountyid desc")->asArray()->all();
    }
    /**
     * 获取佣金总额
     * @param  [type] $uid   [description]
     * @param  [type] $stime [description]
     * @param  [type] $etime [description]
     * @return [type]        [description]
     */
    public static function getTotalBounty($uid,$stime=NULL,$etime=NULL){
        $query=self::find()->select("sum(bounty_fee) as bounty")->where(['teacheruid'=>$uid]);
        if($stime){
            $query->andWhere([">","ctime",$stime]);
        }
        if($etime){
            $query->andWhere(["<","ctime",$etime]);
        }
        return $query->asArray()->one()['bounty'];
    }
}

