<?php

namespace mis\controllers\capacity;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use common\service\dict\CapacityModelDictDataService;
use mis\service\CapacityModelMaterialService;
use common\service\AliOssService;
use mis\service\MatreialSubjectService;

#马甲用户
use mis\service\UserService;
use mis\service\MisUserVestService;

/**
 * 编辑能力模型 
 */
class EditAction extends MBaseAction {

    /**
     * 能力模型编辑
     */
    public function run() {
        $request = Yii::$app->request;
        $classtype['maintype'] = CapacityModelDictDataService::getCorrectMainType();
        $classtype['subtype'] = CapacityModelDictDataService::getCorrectSubType();
        $classtype['captype'] = CapacityModelDictDataService::getCorrectScoreItem();

        $mis_userid = Yii::$app->user->getIdentity()->mis_userid;


        //获取马甲用户
        $uids = MisUserVestService::getVestUser($mis_userid);
        $uid_array = explode(",", $uids);
        $user_infos = array();
        foreach ($uid_array as $key => $value) {
            $user_infos[] = UserService::findOne(["uid" => $value])->attributes;
        }

        $msg = '';
        $isclose = false;
        if (!$request->isPost) {

            //get访问，判断是edit还是add,返回不同界面
            $materialid = $request->get('materialid');
            $catalog = $request->get('catalog');
            if ($materialid) {

                //edit
                if (!is_numeric($materialid)) {
                    die('非法输入');
                }
                $model = CapacityModelMaterialService::findOne(['materialid' => $materialid]);
                $catalog = $model->s_catalog_id;
                $tag = $model->tags;
                //根据id取出数据
                $tagList = MatreialSubjectService::getTag(1, $catalog, $tag);
                # print_r($tagList);
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'data' => $tagList, 'classtype' => json_encode($classtype), "users" => $user_infos]);
            } else {
                //add
                $model = new CapacityModelMaterialService();
                return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'classtype' => json_encode($classtype), "users" => $user_infos]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //update
                $model = CapacityModelMaterialService::findOne(['materialid' => $request->post('CapacityModelMaterialService')['materialid']]);
                $model->IsNewRecord = false;
                $model->utime = time();

                $array = [];
                foreach ($request->post('MatreialSubjectService') as $key => $val) {
                    foreach ($val as $k => $v) {
                        $array[] = $v;
                    }
                }
                $model->tags = implode(',', $array);
                //用于判断转素材推送
                $model->load($request->post());
            } else {
                //insert
                $model = new CapacityModelMaterialService();
                $model->load($request->post());
                $model->ctime = time();
                $model->status = 0;
                //添加新用户,密码md5加密
            }
            $img_infohw = AliOssService::getFileHW($model->picurl);
            $img_info['n']['h'] = $img_infohw['height'];
            $img_info['n']['w'] = $img_infohw['width'];
            $img_info['n']['url'] = $model->picurl;
            $model->picurl = json_encode($img_info);
            //用户提交
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'classtype' => json_encode($classtype), "users" => $user_infos]);
        }
    }

}
