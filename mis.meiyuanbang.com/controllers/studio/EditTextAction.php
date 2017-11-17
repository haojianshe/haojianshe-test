<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioArticleService;
use mis\service\NewsDataService;
use mis\service\NewsService;

/**
 * 文本编辑
 */
class EditTextAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        $usermodel = \Yii::$app->user->getIdentity();
        $isclose = false;
        $msg = '';
        // 图片分类
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面
            $studiomenuid = $request->get('studiomenuid');
            $articleid = $request->get('articleid');
            $newid = $request->get('newid');
            if (is_numeric($articleid) && empty($request->post('isedit'))) {
                //根据id取出数据
                $array['dataone'] = StudioArticleService::findOne(['articleid' => $articleid, 'studiomenuid' => $studiomenuid]);
                if ($newid) {
                    $array['datatwo'] = NewsService::findOne(['newsid' => $newid]);
                    $array['datathree'] = NewsDataService::findOne(['newsid' => $newid]);
                }
                return $this->controller->render('edit_text', ['model' => $array, 'msg' => $msg, 'studiomenuid' => $studiomenuid]);
            } else {
                $model = new StudioService();
                return $this->controller->render('edit_text', ['model' => $model, 'msg' => $msg, 'usersinfo' => []]);
            }
        } else {

            if ($request->post('isedit') == 1) {
                //编辑
                $array['datatwo'] = NewsService::findOne(['newsid' => $request->post('NewsService')['newsid']]);
                $array['datathree'] = NewsDataService::findOne(['newsid' => $request->post('NewsService')['newsid']]);
                #$array['dataone'] = StudioArticleService::findOne(['studiomenuid' => $studiomenuid]);
                $array['datatwo']->title = $request->post('NewsService')['title'];
                $array['datathree']->content = $request->post('NewsService')['content'];
                if($array['datatwo']->save()){
                    $array['datathree']->save();
                     $isclose = true;
                     $msg = '保存成功';
                }
            } else {
                //插入
                $usermodel = \Yii::$app->user->getIdentity();
                $array['datatwo'] = new NewsService();
                $array['datatwo']->title = $request->post('NewsService')['title'];
                $array['datatwo']->desc = (string) '0';
                $array['datatwo']->thumb = (string) '0';
                $array['datatwo']->catid = 7;
                $array['datatwo']->ctime = time();
                $array['datatwo']->username = $usermodel->mis_realname;
                $array['datatwo']->listorder = 0;
                $array['datatwo']->keywords = '';

                //操作员
                if ($array['datatwo']->save()) {
                    $array['datathree'] = new NewsDataService();
                    $array['datathree']->newsid = $array['datatwo']->attributes['newsid'];
                    $array['datathree']->content = $request->post('NewsService')['content'];
                    $array['datathree']->save();
                    $articleModel = new StudioArticleService();
                    $articleModel->newsid = $array['datatwo']->attributes['newsid'];
                    $articleModel->ctime = time();
                    $articleModel->cover_type = 1;
                    $articleModel->studiomenuid = $request->post('NewsService')['studiomenuid'];
                    #$articleModel->studiomenuid = $request->post('studiomenuid');
                    $articleModel->article_type = 2;
                    $articleModel->status = 1;
                    $articleModel->save();
                    $isclose = true;
                    $msg = '保存成功';
                } else {
                    $msg = '保存失败';
                }
            }
            // $usersinfo = UserService::getInfoByUids($model->teacheruid);
            return $this->controller->render('edit_text', ['model' => $array, 'msg' => $msg, 'isclose' => $isclose, 'catalog' => $config]);
        }
    }

}
