<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use common\service\DictdataService;

/**
 * IOS 价格选择
 */
class IosPriceSelAction extends MBaseAction
{
    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_course';
    
    public function run()
    {
        $request = Yii::$app->request;
        $price = $request->get('price');
        $data['price']=$price;
        $data['iosprices']=DictdataService::getIosProductPriceId();
        return $this->controller->render('iospricesel',$data);
    }
}
