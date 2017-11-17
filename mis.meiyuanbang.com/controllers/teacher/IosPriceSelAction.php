<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use common\service\dict\CorrectIosProductIdService;

/**
 * IOS 价格选择
 */
class IosPriceSelAction extends MBaseAction
{
    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_teacher';
    
    public function run()
    {
        $request = Yii::$app->request;
        $price = $request->get('price');
        $data['price']=$price;
        $data['iosprices']=CorrectIosProductIdService::getIosProductPriceId();
        return $this->controller->render('iospricesel',$data);
    }
}
