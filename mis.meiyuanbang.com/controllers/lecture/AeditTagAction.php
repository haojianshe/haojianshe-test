<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use mis\service\LectureTagService;
use common\models\myb\LectureTagNews;
use mis\service\ResourceService;
use mis\service\NewsService;

/**
 * mis用户删除action
 */
class AeditTagAction extends MBaseAction {

    public $resource_id = 'operation_lecture';

    /**
     * 只支持post删除
     */
    public function run() {

        $request = Yii::$app->request;
        $msg = '';
        $isclose = false;
        $newid = $request->get('newsid');
        $lecture_tagid = $request->get('lecture_tagid');
        #获取精讲专题
        $res = LectureTagNews::find()->select('newsid')->where(['lecture_tagid' => $lecture_tagid])->asArray()->all();

        $arr = [];
        if (!empty($res)) {
            foreach ($res as $key => $v) {
                $arr[$key] = $v['newsid'];
            }
        }
        #如果有文章选择，则去session里面的值
        if (isset($_SESSION['chkval'])) {
            $arr = explode(',', $_SESSION['chkval']);
        }

        #如果缩略图有三张怎么办？或者两张。。等产品确认后继续开发
        $news_data = NewsService::find()->select('newsid,title')->where(['newsid' => $arr])->andWhere(['status' => 0])->asArray()->all();
        if (!$request->isPost) {
            //get访问，判断是edit还是add,返回不同界面

            if ($lecture_tagid) {
                $model = LectureTagService::find()->select('*')->where(['lecture_tagid' => $lecture_tagid])->asArray()->one();
            } else {
                $model = new LectureTagService();
            }
            return $this->controller->render('aedit_tag', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'newsid' => $newid, 'lecture_tagid' => $lecture_tagid, 'news_data' => $news_data]);
        } else {
            if ($request->post('isedit') == 1) {
                LectureTagNews::deleteAll(['lecture_tagid' => $request->post('LectureTagService')['lecture_tagid']]);
                //update
                $model = LectureTagService::findOne(['lecture_tagid' => $request->post('LectureTagService')['lecture_tagid']]);
                $model->IsNewRecord = false;
                $model->newsid = $request->post('newsid');
                $model->load($request->post());
            } else {
                //insert
                $model = new LectureTagService();
                $model->load($request->post());
                //todo 
                $msg = '保存成功';
                $model->newsid = $request->post('newsid');
                $model->ctime = time();
                $model->save();
                $isclose = true;
            }
            if ($model->save()) {
                $lectid = $model->attributes['lecture_tagid'];
                $array = $request->post('news_data');
                if ($array) {
                    $arr = explode(',', $array);
                    $newarray = array_filter($arr);
                    #可以先把 lecture_tagid 删除，然后再写入
                    $res = NewsService::find()->select(['title', 'newsid'])->where(['newsid' => $newarray])->asArray()->all();
                    if ($res) {
                        $connection = Yii::$app->db;
                        $str = " insert into myb_lecture_tag_news (lecture_tagid,newsid,title,ctime) values ";
                        foreach ($res as $key => $val) {
                            $str .="('{$lectid}','{$val['newsid']}','{$val['title']}','" . time() . "'),";
                        }
                        $newstr = substr($str, 0, strlen($str) - 1) . ';';
                        $commandSql = $connection->createCommand($newstr);
                        $commandSql->execute();
                    }
                }
                if ($_SESSION['chkval']) {
                    unset($_SESSION['chkval']);
                }
                $isclose = true;
                $msg = '保存成功';
            } else {
                $msg = '保存失败';
            }
            return $this->controller->render('aedit_tag', ['model' => $model, 'msg' => $msg, 'isclose' => $isclose, 'newsid' => $newid, 'lecture_tagid' => $lecture_tagid, 'news_data' => $news_data]);
        }
    }

}
