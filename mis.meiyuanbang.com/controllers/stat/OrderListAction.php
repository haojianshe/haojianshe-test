<?php

namespace mis\controllers\stat;

use Yii;
use mis\components\MBaseAction;
use mis\service\OrderinfoService;

/**
 * 订单列表统计
 */
class OrderListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_stat';

    public function run() {
        $request = Yii::$app->request;
        $search['is_hide'] = $request->get("is_hide");
        $search['stime'] = $request->get("stime");
        $search['etime'] = $request->get("etime");
        $search['uid'] = $request->get("uid");
        $search['mark'] = $request->get("mark");

        $search['provinceid'] = $request->get("provinceid");
        $search['professionid'] = $request->get("professionid");


        $search['orderby'] = $request->get("orderby");
        $search['coupon_name'] = $request->get("coupon_name");
        $search['paytype'] = $request->get("paytype");
        //是否团购
        $search['groupbuyid'] = $request->get("groupbuyid");

        $search['qd'] = $request->get("qd");
        $search['subjecttype'] = $request->get("subjecttype") ? $request->get("subjecttype") : NULL;
        $search['status'] = $request->get("status");
        $search['username'] = $request->get("username") ? $request->get("username") : NULL;
        $search['orderid'] = $request->get("orderid") ? $request->get("orderid") : NULL;
        $search['ordertitle'] = $request->get("ordertitle") ? $request->get("ordertitle") : NULL;
        $data['models'] = [];
        if ($search['stime'] || $search['etime']) {
            $data = OrderinfoService::getByPage($search['subjecttype'], $search['status'], $search['ordertitle'], $search['username'], $search['orderid'], strtotime($search['stime']), strtotime($search['etime']), $search['qd'], $search['coupon_name'], $search['paytype'], $search['orderby'], $search['uid'], $search['mark'], $search['provinceid'], $search['professionid'], $search['groupbuyid']);
        }
        if (empty($search['stime']) && empty($search['etime'])) {
            $search['stime'] = date("Y-m-d 00:00:00", strtotime("-30 day"));
            $search['etime'] = date('Y-m-d 00:00:00', strtotime("+1 day"));
        }
        $data['search'] = $search;
        return $this->controller->render('orderlist', $data);
    }

}
