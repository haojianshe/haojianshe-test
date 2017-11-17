<?php
namespace mis\controllers\sound;
use Yii;
use mis\components\MBaseAction;
use mis\service\SoundResourceService;
class IndexAction extends MBaseAction
{
  //音频列表
  public $resource_id = 'operation_video';
  public function run()
    {
    	$request = Yii::$app->request;
        $search['sound_type']=  trim($request->get('sound_type'));
        $search['filename']=  trim($request->get('filename'));
        $search['desc']=  trim($request->get('desc'));

		$data=SoundResourceService::getByPage($search['sound_type'],$search['filename'],$search['desc']);
		$data['search']=$search;
    	return $this->controller->render('index',$data); 
    }
}
