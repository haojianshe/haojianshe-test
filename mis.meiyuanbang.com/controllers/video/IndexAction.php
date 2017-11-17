<?php

namespace mis\controllers\video;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoResourceService;
use common\service\DictdataService;

/**
 * 视频列表
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_lesson';
    public $resource_id = 'operation_video';

    public function run() {
        $request = Yii::$app->request;
        $video_type = $request->get("video_type");#类型
        $desc = $request->get("desc");#描述
        //分页获取考点列表
        $data = VideoResourceService::getDataByPage($video_type,$desc);
        //处理一二级分类
        foreach ($data['models'] as $key => $value) {
            $data['models'][$key]['f_catalog'] = DictdataService::getTweetMainTypeById($data['models'][$key]['maintype']);
            if ($data['models'][$key]['maintype']) {
                $data['models'][$key]['s_catalog'] = DictdataService::getTweetSubTypeById($data['models'][$key]['maintype'], $data['models'][$key]['subtype']);
            } else {
                $data['models'][$key]['s_catalog'] = '';
            }
        }
        $data['video_type'] = $video_type;
        $data['desc'] = $desc;
        return $this->controller->render('index', $data);
    }

}
