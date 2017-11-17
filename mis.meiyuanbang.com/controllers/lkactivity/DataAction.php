<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\MybActivityArticleService;
use mis\service\NewsService;
use mis\service\NewsDataService;

/**
 * 整理精讲数据到联考活动文章
 */
class DataAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        $newDataStr = "13501,13500,13499,13498,13497,13496,13495,13494,13493,13492";
        # $query = "select mn.*,mnd.* from myb_news as mn INNER JOIN myb_news_data as mnd on mn.newsid=mnd.newsid where mn.newsid in ($newDataStr)";
        $query = "select mn.*,mnd.* from myb_news as mn INNER JOIN myb_news_data as mnd on mn.newsid=mnd.newsid where catid in (0) order by mn.newsid desc limit 10";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($query);
        $newData = $command_count->queryAll();
        foreach ($newData as $key => $val) {
            $this->setNewsData($val);
        }
    }

    private function setNewsData($val) {
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
    }

}
