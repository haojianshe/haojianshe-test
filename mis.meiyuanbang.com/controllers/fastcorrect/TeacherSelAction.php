<?php
namespace mis\controllers\fastcorrect;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserCorrectService;
use common\models\myb\UserCorrect;

/**
 * 选择红笔老师
 */
class TeacherSelAction extends MBaseAction
{
    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_teacher';
    
    public function run()
    {
         $request = Yii::$app->request;
         $uids = $request->get('uids');
        
        //获取批改老师
        $data =  UserCorrectService::getByPageOnFc();
        $data['uids']=explode(",", $uids);  
        return $this->controller->render('search',$data);
    }
}
