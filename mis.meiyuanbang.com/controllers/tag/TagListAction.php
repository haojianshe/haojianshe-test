<?php
namespace  mis\controllers\tag;
use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\TagsService;
/**
* 标签组对应标签列表
*/
class TagListAction extends MBaseAction
{
    public $resource_id = 'operation_tag';

    public function run()
    { 
    	$request = Yii::$app->request;
    	//标签组id
        $id=$request->get("id");
        if(empty($id)){
        	die('标签分类错误！');
        }
        //分页获取标签
        $data = TagsService::getTagsByPage($id);
        $data['taggroupid']=$id;
        return $this->controller->render('taglist', $data);
    }
}