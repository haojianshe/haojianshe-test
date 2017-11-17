<?php

namespace mis\controllers\sound;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\SoundResourceService;

/**
 * 编辑语音 
 */
class EditAction extends MBaseAction {
    public function run() {
        $request = Yii::$app->request;
        $msg = '';
        $isclose = false;
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $soundid = $request->get('soundid');
            if ($soundid) {
                //edit
                if (!is_numeric($soundid)) {
                    die('非法输入');
                }
                //根据id取出数据
                $model = SoundResourceService::findOne(['soundid' => $soundid]);
                return $this->controller->render('edit', ['model' => $model,'msg' => $msg]);
            } else{
            	$model = new SoundResourceService();
                return $this->controller->render('edit', ['model' => $model,'msg' => $msg]);
            }
        } else {
            if ($request->post('isedit') == 1) {
                //update
                $model = SoundResourceService::findOne(['soundid' => $request->post('SoundResourceService')['soundid']]);
                $model->IsNewRecord = false;
                $model->load($request->post());
            } else {
                 //insert
                $model = new SoundResourceService();
                $model->load($request->post());
                $model->ctime=time();
            }
           
            //设置音频类型
            $info = pathinfo($model->sourceurl);
            switch (strtolower($info['extension'])) {
            	case 'mp3':
            		$model->filetype=1;
            		break;
            	case 'amr':
            		$model->filetype=2;
            		break;
            	default:
            		break;
            }
            if ($model->save()) {
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            return $this->controller->render('edit', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose]);
        }
    }

}
