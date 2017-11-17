<?php
namespace mis\controllers\push;

use Yii;
use mis\components\MBaseAction;
use mis\service\MisXingePushService;

/**
 * 推送列表
 */
class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_push';
	
	public function run()
    {
        $request = Yii::$app->request;
        $device_open_detail = $request->get('device_open_detail');
        $title = $request->get('title');
        $where='';
        if (!empty($title)) {
           $where= ' title like "%'.$title.'%" ';
        }
        //筛选推送类型
        if (!empty($device_open_detail)) {
            if ($where!='') {
                $where.= ' and device_open_detail="'.$device_open_detail.'"';
            }else{
                 $where= ' device_open_detail="'.$device_open_detail.'"';
            }           
        }
        $data  =  MisXingePushService::getByPage($where);
        //处理推送连接内参数
        foreach ($data['models'] as $key => $value) {
                $parts = parse_url($value['url_params']);
                $parameter = explode('&',$parts['query']);
                foreach($parameter as $val){
                    $tmp = explode('=',$val);
                    $data['models'][$key][$tmp[0]] = $tmp[1];
                }
        }
    	return $this->controller->render('index',$data);

    }
}
