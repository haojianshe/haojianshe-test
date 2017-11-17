<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\MybActivityArticleService;
use mis\service\QaService;
use mis\service\NewsService;
use mis\service\NewsDataService;
use mis\service\ResourceService;

/**
 * 编辑问答
 */
class EditquestionsAction extends MBaseAction {

    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;

        if (!$request->isPost) {
            //处理get请求
            $ret = $this->getHandle();
        } else {
            //处理post请求
            $ret = $this->postHandle();
        }
        return $this->controller->render('editquestions', $ret);
    }

    /**
     * 处理get访问的情况
     */
    private function getHandle() {
        $request = Yii::$app->request;
        //判断参数
        $newsid = $request->get('newsid');
        if ($newsid) {
            //编辑
            if (!is_numeric($newsid)) {
                die('非法输入');
            }
        } else {
            //新添加
            $newsid = 0;
        }
        $ret = $this->getRetModel($newsid);
        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $request = Yii::$app->request;
        if ($request->post('content') == '') {
            echo "<script>alert('内容不能空');window.history.go(-1);</script>";
            exit;
        }
        //先获取model
        $newModel = new NewsService();
        $newDataModel = new NewsDataService();
        $articleModel = new QaService();
        $resourceModel = new ResourceService();
        if ($request->post('isedit') == 1) {
            $newsid = $request->post('newsid');
            $newModel = NewsService::findOne(['newsid' => $newsid]);
            $newDataModel = NewsDataService::findOne(['newsid' => $newsid]);
            $articleModel = QaService::findOne(['newsid' => $newsid]);
        } else {
            $newsid = 0;
               $newModel->username = $usermodel->mis_realname;
            
        }
        # 封面样式 1/2/3/4 无图样式/通栏样式/左图样式/三图样式
        $thumb = '';
        $cover_type = $request->post('cover_type');
        if ($cover_type == 2 || $cover_type == 3) {
            $resourceModel->img = $request->post('thumb')[0];
            $resourceModel->resource_type = 0;
            $resourceModel->save();
//            $thumb = $resourceModel->attributes['rid'];
            $thumb = (string) $resourceModel->attributes['rid'];
        } elseif ($cover_type == 4) {
            #第一张图片
            $resourceModel->img = $request->post('thumb')[0];
            $resourceModel->resource_type = 0;
            $resourceModel->save();
            $thumbOne = $resourceModel->attributes['rid'];

            $resource = new ResourceService();
            #第二张图片
            $resource->img = $request->post('thumb')[1];
            $resource->resource_type = 0;
            $resource->save();
            $thumbTwo = $resource->attributes['rid'];

            $resourcess = new ResourceService();
            #第三张图片
            $resourcess->img = $request->post('thumb')[2];
            $resourcess->resource_type = 0;
            $resourcess->save();
            $thumbThree = $resourcess->attributes['rid'];
            $thumb = $thumbOne . ',' . $thumbTwo . ',' . $thumbThree;
        }
        //检查缩略图
        $usermodel = \Yii::$app->user->getIdentity();
        //写入/insert
        $newModel->load($request->post());
        $newModel->title = $request->post('title');
        $newModel->keywords = $request->post('keywords');
        $newModel->desc = $request->post('desc');
        $newModel->status = 0;
        $newModel->thumb = $thumb;
        $newModel->catid = 5;
     
        $newModel->ctime = time();
        $newModel->listorder = 0;
        try {
            if ($newModel->save(true)) {
                $articleModel->newsid = $newModel->attributes['newsid'];
                $articleModel->ctime = time();
                $articleModel->cover_type = $request->post('cover_type');
                $articleModel->ask_limit = $request->post('ask_limit');
                $articleModel->answer_uids = $request->post('answer_uids');
                $articleModel->activity_type = $request->post('activity_type');
                $articleModel->save();
                $newDataModel->newsid = $newModel->attributes['newsid'];
                $newDataModel->content = $request->post('content');
                $newDataModel->hits = $request->post('hits');
                $newDataModel->copyfrom = $request->post('copyfrom');
                $newDataModel->supportcount = $request->post('supportcount');
                $newDataModel->save();
                $ret['msg'] = '保存成功';
                $ret['isclose'] = true;
            }
        } catch (Exception $ex) {
            $ret['msg'] = '保存失败';
            $ret['isclose'] = false;
        }
        //修改完毕情况缓存
        $redis = Yii::$app->cache;
        $rediskey = "activity_qa_" . $newModel->attributes['newsid'];
        $redis->delete($rediskey);
        $redis->delete('all_qa_list');
        return $ret;
    }

    /**
     * 根据newsid获取活动model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回活动编辑页的model
     */
    private function getRetModel($newsid) {
        if ($newsid == 0) {
            //获取精讲详细信息
            $ret[] = '';
        } else {
            $newModel = NewsService::findOne(['newsid' => $newsid]);
            $newDataModel = NewsDataService::findOne(['newsid' => $newsid]);
            $articleModel = QaService::findOne(['newsid' => $newsid]);
            if ($newModel->thumb) {
                $query = "select img from ci_resource where rid in ($newModel->thumb)";
                $connection = Yii::$app->db; //连接
                $command = $connection->createCommand($query);
                $data = $command->queryAll();
            }
            $ret['model'] = [
                'content' => $newDataModel->content,
                'hits' => $newDataModel->hits,
                'supportcount' => $newDataModel->supportcount,
                'copyfrom' => $newDataModel->copyfrom,
                'title' => $newModel->title,
                'keywords' => $newModel->keywords,
                'desc' => $newModel->desc,
                'newsid' => $newModel->newsid,
                'cover_type' => $articleModel->cover_type,
                'ask_limit' => $articleModel->ask_limit,
                'answer_uids' => $articleModel->answer_uids,
                'img' => isset($data) ? $data : ""
            ];
        }
        return $ret;
    }

}
