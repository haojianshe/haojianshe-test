<?php
namespace mis\controllers\vesttweet;


use Yii;
use yii\base\Action;
use mis\service\TweetService;
use mis\components\MBaseAction;
use common\service\DictdataService;
use mis\service\LessonService;
use mis\service\UserService;
use mis\service\MisUserVestService;
/**
 * 发帖 
 */
class AddMaterialAction extends MBaseAction
{
    /**
    *发帖
     */
    public $resource_id = 'operation_vesttweet';
    public function run()
    {
        $mis_userid=Yii::$app->user->getIdentity()->mis_userid;
        //获取马甲用户
        $uids=MisUserVestService::getVestUser($mis_userid);
        $uid_array=explode(",", $uids);
        $user_infos=array();
        foreach ($uid_array as $key => $value) {
           $user_infos[]=UserService::findOne(["uid"=>$value])->attributes;
        }
      
        $request = Yii::$app->request;
        // 图片分类
        $config['imgmgr_level_1'] = DictdataService::getTweetMainType();
        $config['imgmgr_level_2'] =  DictdataService::getTweetSubType();
        $msg='';
        $isclose = false;       
       
        //add
        $model = new TweetService();
        return $this->controller->render('addmaterial', ['model' => $model,'msg'=>$msg,'catalog'=>$config,"users"=>$user_infos]);
            
    }
}