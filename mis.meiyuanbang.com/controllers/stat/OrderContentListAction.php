<?php

namespace mis\controllers\stat;

use Yii;
use mis\components\MBaseAction;
use mis\service\OrderinfoService;

/**
 * 订单内容列表统计
 */
class OrderContentListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_stat';

    public function run() {
        $request = Yii::$app->request;
        $search['stime'] = $request->get("stime");
        $search['etime'] = $request->get("etime");

        $search['orderby'] = $request->get("orderby");
        $search['paytype'] = $request->get("paytype");

        $search['qd'] = $request->get("qd");
        $search['subjecttype'] = $request->get("subjecttype") ? $request->get("subjecttype") : NULL;
        $search['status'] = $request->get("status");

        $search['ordertitle'] = $request->get("ordertitle") ? $request->get("ordertitle") : NULL;
        $data['models']=[];
        //var_dump( $search['orderby']);exit;
        if ($search['stime'] || $search['etime']) {
            $data = OrderinfoService::getContentByPage($search['orderby'],strtotime($search['stime']),strtotime($search['etime']),$search['paytype'],$search['qd'],$search['subjecttype'],$search['status'],$search['ordertitle']);
              $cout_data= OrderinfoService::getOrderCount($search['subjecttype'], $search['status'], $search['ordertitle'], NULL, NULL, strtotime($search['stime']), strtotime($search['etime']),$search['qd'],$search['paytype']);
             $data=array_merge($data,$cout_data);
        }
        if (empty($search['stime']) && empty($search['etime'])) {
            $search['stime'] = date("Y-m-d 00:00:00", strtotime("-30 day"));
            $search['etime'] = date('Y-m-d 00:00:00', strtotime("+1 day"));
        }
        $data['search'] = $search;
        return $this->controller->render('ordercoutentlist', $data);
    }

}
