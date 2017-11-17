<?php

namespace api\modules\v2_3_3\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectTeacherFolderService;
use api\service\CorrectTeacherPicService;
use api\service\UserDetailService;

/**
 * 批改老师添加常用范例图
 * @author Administrator
 *
 */
class AddteacherpicAction extends ApiBaseAction {

    public function run() {
        //判断是否红笔老师
        if (!UserDetailService::isCorrectTeacher($this->_uid)) {
            //返回非法用户
            $this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL);
        }
        //获取图片id列表和目录编号
        $rids = $this->requestParam('rids', true);
        $folderid = $this->requestParam('folderid', true);

        //主分类
        $f_catalog_id = $this->requestParam('f_catalog_id');
        //二级分类
        $s_catalog_id = $this->requestParam('s_catalog_id');

        $addcount = 0;
        $arrrid = explode(',', $rids);
        $utime = time();
        foreach ($arrrid as $k => $v) {
            //判断是否添加过这个素材
            if ($v) {
                if (CorrectTeacherPicService::addPic($this->_uid, $v, $folderid, $utime, $f_catalog_id, $s_catalog_id)) {
                    $addcount += 1;
                    $utime = $utime + 1;
                }
            }
        }
        //调整批改老师的常用范例图数量
        if ($addcount > 0) {
            CorrectTeacherFolderService::updatePicCount($folderid, $addcount);
        }
        $data['addcount'] = $addcount;
        //返回数据
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
