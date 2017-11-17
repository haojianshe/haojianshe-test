<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\Orderinfo;

/**
 * @author ihziluoh
 * 
 * 订单信息表
 */
class OrderinfoService extends Orderinfo {

    /**
     * [订单查询]
     * @param  [type] $subjecttype [订单类型]
     * @param  [type] $status      [订单状态]
     * @param  [type] $ordertitle       [订单标题]
     * @param  [type] $username    [用户名]
     * @param  [type] $orderid     [订单编号]
     * @param  [type] $stime       [开始时间]
     * @param  [type] $etime       [结束时间]
     * @return [type]              [description]
     */
    public static function getByPage($subjecttype = NULL, $status = NULL, $ordertitle = NULL, $username = NULL, $orderid = NULL, $stime = NULL, $etime = NULL, $qd = NULL, $coupon_name = NULL, $paytype = NULL, $orderby = NULL, $uid = NULL, $mark = NULL, $provinceid = NULL, $professionid = NULL, $groupbuyid = null) {
        $query = (new \yii\db\Query())->from(parent::tableName() . ' as a')
                ->select("a.orderid,a.ordertitle,a.subjecttype,a.status,a.paytype,a.order_from,a.ctime,a.fee,a.coupon_price,b.sname,c.umobile")
                ->innerJoin("ci_user_detail as b", 'a.uid=b.uid')
                ->innerJoin("ci_user as c", 'c.id=b.uid')
                ->leftJoin("myb_user_coupon as d", "a.usercouponid=d.usercouponid")
                ->leftJoin("myb_coupon as e", "e.couponid=d.couponid");
        if ($paytype != NULL) {
            $query->andWhere(['paytype' => $paytype]);
        }
        if ($uid != NULL) {
            $query->andWhere(['a.uid' => $uid]);
        }
        if ($mark != NULL) {
            $query->andWhere(['a.mark' => $mark]);
        }
        if ($provinceid != NULL) {
            $query->andWhere(['b.provinceid' => $provinceid]);
        }
        if ($professionid != NULL) {
            $query->andWhere(['b.professionid' => $professionid]);
        }
        //不为空
        if ($groupbuyid==1) {
            $query->andWhere(['>', 'a.groupbuyid', 0]);
        }else if($groupbuyid==2){
              $query->andWhere(['is', 'a.groupbuyid', null]);
        }
    
        if ($subjecttype != NULL) {
            $query->andWhere(['subjecttype' => $subjecttype]);
        }
        if ($status != NULL) {
            $query->andWhere(['a.status' => $status]);
        }
        if ($ordertitle != NULL) {
            $query->andWhere(['like', 'a.ordertitle', $ordertitle]);
        }

        if ($coupon_name != NULL) {
            $query->andWhere(['like', 'e.coupon_name', $coupon_name]);
        }
        if ($username != NULL) {
            $query->andWhere(['like', 'b.sname', $username]);
        }
        if ($orderid != NULL) {
            $query->andWhere(['a.orderid' => $orderid]);
        }
        if ($stime != NULL) {
            $query->andWhere(['>', 'a.ctime', $stime]);
        }

        if ($etime != NULL) {
            $query->andWhere(['<', 'a.ctime', $etime]);
        }
        if ($qd == 1) {
            //android
            $query->andWhere("a.order_from='android'");
            /*            $query->andWhere(['=', 'c.qd', "ios"]);
             */
        } else if ($qd == 2) {
            //Ios
            $query->andWhere("a.order_from='ios'");
            //$query->andWhere("(c.qd is null or c.qd <> 'ios')");
        } else if ($qd == 3) {
            //浏览器 微信公众号
            $query->andWhere(" a.order_from in ('android-h5','ios-h5','android-wx','ios-wx')");
        }
        if ($orderby == NULL) {
            $orderby = 'orderid DESC';
        }
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
         $countFee = self::getOrderCountFee($query, $orderby);
        $rows['countFee'] = $countFee;
        //获取数据     
        $rows['models'] = $countQuery->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy($orderby)
              #  ->createCommand()->getRawSql();
          ->all();
    
        return ['models' => $rows, 'pages' => $pages];
    }

    //获取订单总价格
    public static function getOrderCountFee($query, $orderby) {
        return $query->select('sum(a.fee) as totlefee')->orderBy($orderby)->one();
    }

    public static function getUserByPage($orderby, $stime, $etime, $paytype, $username, $umobile, $subjecttype, $status, $ordertitle, $qd, $provinceid = NULL, $professionid = NULL) {
        $query = (new \yii\db\Query())->from(parent::tableName() . ' as a')
                ->select("a.uid,b.sname,b.professionid,b.provinceid,c.umobile,c.create_time,sum(fee) as totalfee,count(a.uid) as totalcount,moa.mark as action_mark")
                ->leftJoin("ci_user as c", "c.id=a.uid")
                ->leftJoin("ci_user_detail as b", "b.uid=a.uid")
                ->leftJoin("myb_orderaction as moa", "a.orderid=moa.orderid");
        if (empty($orderby)) {
            $orderby = "totalcount desc";
        }

        if ($subjecttype != NULL) {
            $query->andWhere(['subjecttype' => $subjecttype]);
        }
        if ($provinceid != NULL) {
            $query->andWhere(['b.provinceid' => $provinceid]);
        }
        if ($professionid != NULL) {
            $query->andWhere(['b.professionid' => $professionid]);
        }
        if ($paytype != NULL) {
            $query->andWhere(['=', 'a.ctime', $paytype]);
        }
        if ($stime != NULL) {
            $query->andWhere(['>', 'a.ctime', $stime]);
        }

        if ($etime != NULL) {
            $query->andWhere(['<', 'a.ctime', $etime]);
        }
        if ($status != NULL) {
            $query->andWhere(['a.status' => $status]);
        }
        if ($ordertitle != NULL) {
            $query->andWhere(['like', 'a.ordertitle', $ordertitle]);
        }
        if ($username != NULL) {
            $query->andWhere(['like', 'b.sname', $username]);
        }

        if ($umobile != NULL) {
            $query->andWhere(['like', 'c.umobile', $umobile]);
        }


        if ($qd == 1) {
            //android
            $query->andWhere("a.order_from='android'");
            /*            $query->andWhere(['=', 'c.qd', "ios"]);
             */
        } else if ($qd == 2) {
            //Ios
            $query->andWhere("a.order_from='ios'");
            //$query->andWhere("(c.qd is null or c.qd <> 'ios')");
        } else if ($qd == 3) {
            //浏览器 微信公众号
            $query->andWhere(" a.order_from in ('android-h5','ios-h5','android-wx','ios-wx')");
        }

        $query->groupBy("a.uid")
                ->orderBy($orderby);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据     
        $rows = $query->offset($pages->offset)
                ->limit($pages->limit)
                //->orderBy($orderby)
                ->all();
        //echo $query->createCommand()->getRawSql();exit;
        return ['models' => $rows, 'pages' => $pages];


        /* select mo.uid,sum(fee),count(mo.uid) as totlecount,cu.umobile,cud.sname from myb_orderinfo as mo 
          left join ci_user as cu on cu.id=mo.uid
          left join ci_user_detail as cud on cud.uid=mo.uid
          group by mo.uid
          order by totlecount desc limit 10 offset 2 */
    }

    public static function getContentByPage($orderby, $stime, $etime, $paytype, $qd, $subjecttype, $status, $ordertitle) {

        $query = (new \yii\db\Query())->from(parent::tableName() . ' as a')
                ->select("ordertitle,a.subjecttype,a.mark,count(a.mark) as totalcount,sum(fee) as totalfee,moa.mark as action_mark")
                //->leftJoin("ci_user as cu","cu.id=mo.uid")
                //->leftJoin("ci_user_detail as cud","cud.uid=mo.uid")
                ->innerJoin("ci_user_detail as b", 'a.uid=b.uid')
                ->innerJoin("ci_user as c", 'c.id=b.uid')
                ->leftJoin("myb_orderaction as moa", "a.orderid=moa.orderid");

        if (empty($orderby)) {
            $orderby = "totalcount desc";
        }
        if ($paytype != NULL) {
            $query->andWhere(['=', 'a.ctime', $paytype]);
        }
        if ($stime != NULL) {
            $query->andWhere(['>', 'a.ctime', $stime]);
        }
        if ($paytype != NULL) {
            $query->andWhere(['=', 'a.ctime', $paytype]);
        }

        if ($qd == 1) {
            //android
            $query->andWhere("a.order_from='android'");
            /*            $query->andWhere(['=', 'c.qd', "ios"]);
             */
        } else if ($qd == 2) {
            //Ios
            $query->andWhere("a.order_from='ios'");
            //$query->andWhere("(c.qd is null or c.qd <> 'ios')");
        } else if ($qd == 3) {
            //浏览器 微信公众号
            $query->andWhere(" a.order_from in ('android-h5','ios-h5','android-wx','ios-wx')");
        }

        if ($subjecttype != NULL) {
            $query->andWhere(['a.subjecttype' => $subjecttype]);
        }

        if ($status != NULL) {
            $query->andWhere(['a.status' => $status]);
        }
        if ($ordertitle != NULL) {
            $query->andWhere(['like', 'a.ordertitle', $ordertitle]);
        }
        $query->groupBy("a.mark,a.subjecttype")
                ->orderBy($orderby);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据     
        $rows = $query->offset($pages->offset)
                ->limit($pages->limit)
                //->orderBy($orderby)
                ->all();
        return ['models' => $rows, 'pages' => $pages];
        /* select mo.uid,sum(fee),count(mo.uid) as totlecount,cu.umobile,cud.sname from myb_orderinfo as mo 
          left join ci_user as cu on cu.id=mo.uid
          left join ci_user_detail as cud on cud.uid=mo.uid
          group by mo.uid
          order by totlecount desc limit 10 offset 2 */
    }

    /**
     * 获取团购的订单数量和订单总金额
     */
    public static function getGroupBuy($groupbuyid = '') {
        $query = (new \yii\db\Query())
                ->from(parent::tableName() . ' as a ')
                ->innerJoin("ci_user_detail as b", 'a.uid=b.uid')
                ->innerJoin("myb_group_buy as c", 'a.groupbuyid=c.groupbuyid')
                ->innerJoin("myb_course as d", 'd.courseid=c.courseid')
                ->innerJoin("ci_user as cu", 'cu.id=b.uid')
                ->where(['a.groupbuyid' => $groupbuyid])
                ->andWhere(['a.status' => 1]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $rows = $query->select(['a.*', 'b.*', 'c.course_group_fee', 'd.course_sale_price', 'cu.umobile'])->offset($pages->offset)
                        ->limit($pages->limit)->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 获取团购的订单数量和订单总金额
     */
    public static function getGroupBuyOrderCount($groupid = '', $type = 1) {

        switch ($type) {
            case 1:
                return self::find()->where(['groupbuyid' => $groupid])->andWhere(['status' => 1])->count();
                break;
            case 2:
                return self::find()->where(['groupbuyid' => $groupid])->andWhere(['status' => 1])->sum('fee');
                break;
//             case 3:
//                return self::find()->where(['groupbuyid' => $groupid])->andWhere(['status' => 1])->sum('fee');
//                break;
        }
    }
    
    
    /**
     *  获取订单总金额
     * @param type $subjecttype
     * @param type $status
     * @param type $ordertitle
     * @param type $username
     * @param type $orderid
     * @param type $stime
     * @param type $etime
     * @return type
     */
    public static function getOrderCount($subjecttype = NULL, $status = NULL, $ordertitle = NULL, $username = NULL, $orderid = NULL, $stime = NULL, $etime = NULL, $qd = NULL, $paytype = NULL, $provinceid = NULL, $professionid = NULL) {
        $query = (new \yii\db\Query())->from(parent::tableName() . ' as a')->select("a.*,b.*,c.*")//,d.bounty_fee
                ->innerJoin("ci_user_detail as b", 'a.uid=b.uid')
                ->innerJoin("ci_user as c", 'c.id=b.uid')
                ->leftJoin("myb_orderaction as moa", "a.orderid=moa.orderid");
        if ($subjecttype != NULL) {
            $query->andWhere(['a.subjecttype' => $subjecttype]);
        }
        if ($paytype != NULL) {
            $query->andWhere(['a.paytype' => $paytype]);
        }
        if ($provinceid != NULL) {
            $query->andWhere(['b.provinceid' => $provinceid]);
        }
        if ($professionid != NULL) {
            $query->andWhere(['b.professionid' => $professionid]);
        }
        if ($status != NULL) {
            $query->andWhere(['a.status' => $status]);
        }
        if ($ordertitle != NULL) {
            $query->andWhere(['like', 'a.ordertitle', $ordertitle]);
        }
        if ($username != NULL) {
            $query->andWhere(['like', 'b.sname', $username]);
        }
        if ($orderid != NULL) {
            $query->andWhere(['a.orderid' => $orderid]);
        }
        if ($stime != NULL) {
            $query->andWhere(['>=', 'a.ctime', $stime]);
        }

        if ($etime != NULL) {
            $query->andWhere(['<', 'a.ctime', $etime]);
        }

        if ($qd == 1) {
            //android
            $query->andWhere("a.order_from='android'");
            /*            $query->andWhere(['=', 'c.qd', "ios"]);
             */
        } else if ($qd == 2) {
            //Ios
            $query->andWhere("a.order_from='ios'");
            //$query->andWhere("(c.qd is null or c.qd <> 'ios')");
        } else if ($qd == 3) {
            //浏览器 微信公众号
            $query->andWhere(" a.order_from in ('android-h5','ios-h5','android-wx','ios-wx')");
        }

        //获取数据     

        $rows = $query->all();

        $rows['count'] = 0;
        //$rows['bounty_fee']=0;
        $rows['order_count'] = 0;

        #订单搜索的总价格相加
        if ($rows) {
            $rows['order_count'] = count($rows);
            foreach ($rows as $k => $v) {
                $rows['count'] += $v['fee'];
                //$rows['bounty_fee'] += $v['bounty_fee'];
            }
        }
        return $rows;
    }

}
