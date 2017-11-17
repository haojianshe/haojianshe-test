<?php

namespace api\modules\v2_3_3\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectTeacherPicService;
use api\service\ResourceService;
use common\service\CommonFuncService;

/**
 * 获取批改老师常用范例图
 * @author Administrator
 *
 */
class GetteacherpicAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        $utime = $this->requestParam('utime');
        if (!$utime) {
            $utime = 0;
        }
        //老师对应目录编号和上次更新时间
        $type = $this->requestParam('type');
        if (isset($type) && $type == 1) {
            $f_catalog_id = $this->requestParam('f_catalog_id', true);
            $s_catalog_id = $this->requestParam('s_catalog_id') ? $this->requestParam('s_catalog_id') : 0;
            //(1)获取图片的资源id
            $rids = CorrectTeacherPicService::getPageByCatalog($f_catalog_id,$s_catalog_id, $this->_uid, $utime, $rn);
        } else {
            $folderid = $this->requestParam('folderid', true);
            //(1)获取图片的资源id
            $rids = CorrectTeacherPicService::getPageByUtime($folderid, $this->_uid, $utime, $rn);
        }
        //(2)获取每个图片的详细信息
        $data = [];
        if ($rids) {
            foreach ($rids as $k => $v) {
                $tmp = ResourceService::getResourceDetail($v['rid']);
                $tmp['des'] = $tmp['description'];
                unset($tmp['description']);
                if (empty($tmp['img'])) {
                    continue;
                }
                $tmp['img']->t = CommonFuncService::getPicByType((array) $tmp['img']->n, 't');
                $tmp['img']->l = CommonFuncService::getPicByType((array) $tmp['img']->n, 'l');
                $tmp['utime'] = $v['utime'];
                $data[] = $tmp;
            }
        }
        $ret['content'] = $data;
        //返回数据
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
