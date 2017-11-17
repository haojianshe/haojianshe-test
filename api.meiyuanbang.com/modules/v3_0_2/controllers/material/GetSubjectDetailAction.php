<?php

namespace api\modules\v3_0_2\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\service\MaterialSubjectService;
use api\service\ResourceService;
use api\service\FavoriteService;
use common\service\CommonFuncService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 获取专题detail页数据
 */
class GetSubjectDetailAction extends ApiBaseAction {

    public function run() {
        $sid = $this->requestParam('sid', true);

        //(1)获取专题详情
        $subjectinfo = MaterialSubjectService::getMaterialDetail($sid);
        if ($subjectinfo) {
            $subjectinfo['other_subject_list'] = MaterialSubjectService::getSubjectMaterialDetail($subjectinfo['ctime'], $subjectinfo['subject_typeid']);
            if (empty($subjectinfo['other_subject_list'])) {
                $subjectinfo['other_subject_list'] = null;
            }
            $subjectinfo['subject_pic'] = json_decode($subjectinfo["picurl"])->n->url;
            MaterialSubjectService::addHits($sid);
            //获取收藏状态
            $subjectinfo['fav'] = FavoriteService::getFavStatusByUidTid($this->_uid, $sid, 1);
            $favinfo=FavoriteService::getFavInfoByContent($sid,1);
            $subjectinfo=array_merge($subjectinfo,$favinfo);
            //获取图片信息
            $rids_arr = explode(",", $subjectinfo['rids']);
            $subjectinfo['imgs_list'] = [];
            foreach ($rids_arr as $key => $value) {
                $tmp = ResourceService::getResourceDetail($value);
                if ($tmp) {
                    //素材增加列表图片返回
                    $tmp['img']->l = CommonFuncService::getPicByType((array) $tmp['img']->n, 'l');
                    $tmp['img']->s = CommonFuncService::getPicByType((array) $tmp['img']->n, 's');
                    $subjectinfo['imgs_list'][] = $tmp;
                }
            }
            //分享相关信息
            $subjectinfo['share']['title'] = $subjectinfo["title"];
            $subjectinfo['share']['img'] = json_decode($subjectinfo["picurl"])->n->url;
            $subjectinfo['share']['desc'] = $subjectinfo["material_desc"];
            $subjectinfo['share']['url'] = Yii::$app->params['sharehost'] . "/material/index?subjectid=" . $sid;
        } else {
            $subjectinfo = [];
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, ['content' => $subjectinfo]);
    }

}
