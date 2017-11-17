<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use mis\service\MybActivityArticleService;
use mis\service\NewsService;
use mis\service\NewsDataService;

/**
 * 整理精讲数据到联考活动文章
 */
class CopynewdataAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_lecture';

    public function run() {

        $request = Yii::$app->request;
        $newDataStr = $request->post('newsid');
        $title = $request->post('title');
        $model = NewsService::find()->where(['title' => $title])->andWhere(['catid' => 4])->andWhere(['status' => 0])->count(); // NewsService::findOne(['title' => $title, 'catid' => '4'])->count(); //->where()->andWhere([])->all(); # ->count(); //
        if ($model) {
            $num = 1;
        }
        $rows = (new \yii\db\Query())
                ->select(['mn.*', 'mnd.*'])
                ->from('myb_news as mn')
                ->innerJoin('myb_news_data as mnd', 'mn.newsid=mnd.newsid')
                ->where(['mn.newsid' => $newDataStr])
                ->one();
        if (!empty($rows)) {
            $this->setNewsData($rows, $num);
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '添加失败']);
    }

    private function setNewsData($val, $num = 0) {
        $newModel = new NewsService();
        $newDataModel = new NewsDataService();
        $articleModel = new MybActivityArticleService();
        if (empty($val['thumb'])) {
            $cover_type = 1;
        } elseif (substr_count($val['thumb'], ",") == 1) {
            $cover_type = 2;
        } elseif (substr_count($val['thumb'], ",") == 0) {
            $cover_type = 3;
        } elseif (substr_count($val['thumb'], ",") == 2) {
            $cover_type = 4;
        }
        #插入到news表
        $newModel->title = $val['title'];
        $newModel->keywords = $val['keywords'];
        $newModel->desc = $val['desc'];
        $newModel->status = 0;
        $newModel->thumb = $val['thumb'];
        $newModel->catid = 4;
        $newModel->username = $val['username'];
        $newModel->ctime = time();
        $newModel->listorder = 0;
        $newModel->utime = $val['utime'];
        $newModel->save();

        #插入到new_data
        $newDataModel->newsid = $newModel->attributes['newsid'];
        $newDataModel->content = $val['content'];
        $newDataModel->hits = $val['hits'];
        $newDataModel->cmtcount = $val['cmtcount'];
        $newDataModel->supportcount = $val['supportcount'];
        $newDataModel->copyfrom = $val['copyfrom'];
        $newDataModel->reserve1 = $val['reserve1'];
        $newDataModel->reserve2 = $val['reserve2'];
        $newDataModel->reserve3 = $val['reserve3'];
        $newDataModel->save();

        #插入到文章管理表
        $articleModel->newsid = $newModel->attributes['newsid'];
        $articleModel->ctime = time();
        $articleModel->cover_type = $cover_type;
        $articleModel->activity_type = 1;
        $articleModel->save();
        if ($num) {
            return $this->controller->outputMessage(['errno' => 2, 'msg' => '活动文章已经存在,已添加成功！']);
        } else {
            return $this->controller->outputMessage(['errno' => 0, 'msg' => '成功']);
        }
    }

}
