<?php
namespace mis\controllers\tweet;

use Yii;
use mis\components\MBaseAction;
use common\service\DictdataService;

/**
 *获取帖子分类
 */
class GetTagsAction extends MBaseAction
{ 
    /**
     * 获取帖子分类
     */
    public function run()
    {  
        
    	$tag_json=DictdataService::getTweetTypeAndTag();
    	$data = $tag_json['data'];
        $tags=[];
        foreach ($data as $key => $value) {
                if($value['name'] == $_POST['f_catalog']){
                    foreach ($value['catalog'] as $key1 => $value1) {
                    if( $_POST['s_catalog'] ==  $value1['name']){                   
                       $tags=$value1['tag_group'];
                    }  
                }
                              
            }  
        }
        return $this->controller->outputMessage(['errno'=>0,'data'=>$tags]);
    
    }
}
