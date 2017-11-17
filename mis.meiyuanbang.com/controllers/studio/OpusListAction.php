<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioOpusService;
use mis\service\ResourceService;

/**
 * 作品展示
 */
class OpusListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_studio';
    public function run() {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $studiomenuid = $request->get('studiomenuid');
        //分页列表
        $data = StudioOpusService::getByPage($uid,$studiomenuid);
        foreach($data['models'] as $key=>$val){
           $data['models'][$key]['resource'] = json_decode(ResourceService::find()->select('img')->where(['rid'=>$val['resourceid']])->asArray()->one()['img'],1)['n']['url'];
        }
        return $this->controller->render('opus_list', $data);
    }

}
