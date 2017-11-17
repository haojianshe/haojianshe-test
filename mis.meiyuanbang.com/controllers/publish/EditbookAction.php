<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use mis\service\PublishingBookService;
use mis\service\NewsService;
use mis\service\NewsDataService;
use mis\service\ResourceService;

/**
 * 编辑文章
 */
class EditbookAction extends MBaseAction {

    public $resource_id = 'operation_publish';

    public function run() {
        $request = Yii::$app->request;

        if (!$request->isPost) {
            //处理get请求
            $ret = $this->getHandle();
        } else {
            //处理post请求
            $ret = $this->postHandle();
        }
        $ret['uid'] = $request->get('uid');
        return $this->controller->render('editbook', $ret);
    }

    /**
     * 处理get访问的情况
     */
    private function getHandle() {
        $request = Yii::$app->request;
        //判断参数
        $bookid = $request->get('bookid');
        if ($bookid) {
            //编辑
            if (!is_numeric($bookid)) {
                die('非法输入');
            }
        } else {
            //新添加
            $bookid = 0;
        }
        $ret = $this->getRetModel($bookid);
        return $ret;
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        $request = Yii::$app->request;
        //先获取model
        $newModel = new NewsService();
        $newDataModel = new NewsDataService();
        $articleModel = new PublishingBookService();
        $resourceModel = new ResourceService();
        if ($request->post('isedit') == 1) {
            $bookid = $request->post('bookid');
            $articleModel = PublishingBookService::findOne(['bookid' => $bookid]);
            $newModel = NewsService::findOne(['newsid' => $articleModel->newsid]);
            $newDataModel = NewsDataService::findOne(['newsid' => $articleModel->newsid]);
            
        } else {
            $newsid = 0;
            $newModel->ctime = time();
            $articleModel->ctime = time();
            PublishingBookService::setCaheBook('', $request->post('uidid'), 4);
        }
        $uid = $request->post('uidid');
        $rid = $request->post('rid');

          //检查内容
        if (empty($request->post('content'))) {
            echo "<script>alert('内容不能空');window.history.go(-1);</script>";
            exit;
        }
        //检查缩略图
        $usermodel = \Yii::$app->user->getIdentity();
        //写入/insert
        $newModel->load($request->post());
        $newModel->title = $request->post('title');
        $newModel->keywords = $request->post('keywords');
        $newModel->desc = $request->post('desc');
        $newModel->status = 0;
        $newModel->thumb = isset($rid) ? $rid : "";
        $newModel->catid = 6;
        $newModel->username = $usermodel->mis_realname;
        $newModel->listorder = 0;
        $newModel->save(true);
        if ($newModel->save(true)) {
            $articleModel->newsid = $newModel->attributes['newsid'];
            $articleModel->uid = $uid;
            $articleModel->buy_url = $request->post('buy_url');
            $articleModel->f_catalog_id = $request->post('f_catalog_id');
            $articleModel->s_catalog_id = $request->post('s_catalog_id');
            $articleModel->publishing_name = $request->post('publishing_name');
            $articleModel->price = $request->post('price');
            $articleModel->status = 1;
            $articleModel->save();
            $newDataModel->newsid = $newModel->attributes['newsid'];
            $newDataModel->content = $request->post('content');
            $newDataModel->hits = $request->post('hits');
            $newDataModel->copyfrom = $request->post('copyfrom');
            $newDataModel->supportcount = $request->post('supportcount');
            $newDataModel->save();
            #去掉图书列表和单个图书编辑的缓存
            PublishingBookService::setCaheBook($articleModel->attributes['bookid'], $uid, 1);
            $ret['msg'] = '保存成功';
            $ret['isclose'] = true;
        } else {
            $ret['msg'] = '保存失败';
            $ret['isclose'] = false;
        }

        return $ret;
    }

    /**
     * 根据newsid获取活动model
     * newsid为0代表新建 不为0则从数据库取数据
     * 返回活动编辑页的model
     */
    private function getRetModel($bookid) {
        if ($bookid == 0) {
            //获取精讲详细信息
            $ret[] = '';
        } else {
            $articleModel = PublishingBookService::findOne(['bookid' => $bookid]);
            $newModel = NewsService::findOne(['newsid' => $articleModel->newsid]);
            $newDataModel = NewsDataService::findOne(['newsid' => $articleModel->newsid]);

            if ($newModel->thumb) {
                $query = "select img from ci_resource where rid in ($newModel->thumb)";
                $connection = Yii::$app->db; //连接
                $command = $connection->createCommand($query);
                $data = $command->queryOne();
                $imgArray = json_decode($data['img'], 1);
            }
            $ret['model'] = [
                'bookid' => $articleModel->bookid,
                'content' => $newDataModel->content,
                'hits' => $newDataModel->hits,
                'supportcount' => $newDataModel->supportcount,
                'copyfrom' => $newDataModel->copyfrom,
                'title' => $newModel->title,
                'keywords' => $newModel->keywords,
                'desc' => $newModel->desc,
                'newsid' => $newModel->newsid,
                'buy_url' => $articleModel->buy_url,
                'rid' => $newModel->thumb,
                'price' => $articleModel->price,
                'publishing_name' => $articleModel->publishing_name,
                's_catalog_id' => $articleModel->s_catalog_id,
                'f_catalog_id' => $articleModel->f_catalog_id,
                'img' => isset($imgArray['n']['url']) ? $imgArray['n']['url'] : ""
            ];
        }
        return $ret;
    }

}
