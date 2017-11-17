<?php

namespace mis\controllers\stat;

use Yii;
use mis\components\MBaseAction;
use mis\service\OrderinfoService;

/**
 * 订单用户列表统计
 */
class OrderUserListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_stat';

    public function run() {
        $request = Yii::$app->request;
        $search['stime'] = $request->get("stime");
        $search['etime'] = $request->get("etime");

        $search['paytype'] = $request->get("paytype");
        $search['username'] = $request->get("username");
        $search['umobile'] = $request->get("umobile");
        $search['orderby'] = $request->get("orderby");
        $search['provinceid'] = $request->get("provinceid");
        $search['professionid'] = $request->get("professionid");

        $search['qd'] = $request->get("qd");
        $search['subjecttype'] = $request->get("subjecttype") ? $request->get("subjecttype") : NULL;
        $search['status'] = $request->get("status");
        $search['ordertitle'] = $request->get("ordertitle") ? $request->get("ordertitle") : NULL;

        $data['models']=[];
        if ($search['stime'] || $search['etime']) {
            $data = OrderinfoService::getUserByPage($search['orderby'],strtotime($search['stime']),strtotime($search['etime']),$search['paytype'], $search['username'],$search['umobile'],$search['subjecttype'],$search['status'],$search['ordertitle'], $search['qd'],$search['provinceid'],$search['professionid']);
             $cout_data=  OrderinfoService::getOrderCount($search['subjecttype'], $search['status'], $search['ordertitle'], $search['username'], NULL, strtotime($search['stime']), strtotime($search['etime']),$search['qd'],$search['paytype'],$search['provinceid'],$search['professionid']);
              $data=array_merge($data,$cout_data);
        }
        if (empty($search['stime']) && empty($search['etime'])) {
            $search['stime'] = date("Y-m-d 00:00:00", strtotime("-30 day"));
            $search['etime'] = date('Y-m-d 00:00:00', strtotime("+1 day"));
        }
        $data['search'] = $search;
        return $this->controller->render('orderuserlist', $data);
    }

}
