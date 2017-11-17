<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioArticleService;
use mis\service\NewsService;
use mis\service\NewsDataService;
use mis\service\ResourceService;

/**
 * 编辑文章
 */
class EditArticleAction extends MBaseAction {

    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;

        //主类型id
        $studiomenuid = $request->get('studiomenuid');
        //文章id
        $articleid = $request->get('articleid');
        $newid = $request->get('newid');
        $uid = $request->get('uid');
        $menuid = $request->get('menuid');
        if (!$request->isPost) {
            //处理get请求
            $ret = $this->getRetModel($articleid, $newid);
        } else {
            //处理post请求

            $ret = $this->postHandle();
        }
        $models['newid'] = $newid;
        $models['studiomenuid'] = $studiomenuid;
        $models['articleid'] = $articleid;
        $models['models'] = $ret;
        $models['uid'] = $uid;
        $models['menuid'] = $menuid;
        return $this->controller->render('editarticle', $models);
    }

    /**
     * 处理post访问的情况
     */
    private function postHandle() {
        
          $request = Yii::$app->request;
        $uid = $request->get('uid');
        $menuid = $request->get('menuid');
          $redis = Yii::$app->cache;
         $redis->delete('studio_teacher_list_' . $uid . '_menutype_' .$menuid);
        $request = Yii::$app->request;
        if ($request->post('content') == '') {
            echo "<script>alert('内容不能空');window.history.go(-1);</script>";
            exit;
        }

        //先获取model
        $newModel = new NewsService();
        $newDataModel = new NewsDataService();
        $articleModel = new StudioArticleService();
        $resourceModel = new ResourceService();
        if ($request->post('isedit') == 1) {
            $newsid = $request->post('newsid');
            $articleid = $request->post('articleid');
            $newModel = NewsService::findOne(['newsid' => $newsid]);
            $newDataModel = NewsDataService::findOne(['newsid' => $newsid]);
            $articleModel = StudioArticleService::findOne(['articleid' => $articleid]);
        } else {
            $newsid = 0;
        }
        # 封面样式 1/2/3/4 无图样式/通栏样式/左图样式/三图样式
        $thumb = '';
        $cover_type = $request->post('cover_type');
        $redis = Yii::$app->cache;
        $redis_key = 'studio_class_type_' . $menuid . '_' . $uid; //缓存key
        $redis->delete($redis_key);
        
        if ($cover_type == 2 || $cover_type == 3 || $cover_type == 5) {
            $resourceModel->img = $request->post('thumb')[0];
            $resourceModel->resource_type = 0;
            $resourceModel->save();
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
        $newModel->catid = 7;
        $newModel->username = $usermodel->mis_realname;
        $newModel->ctime = time();
        $newModel->listorder = 0;
        $newModel->save(true);
        

            
            
        if ($newModel->save(true)) {
            $articleModel->newsid = $newModel->attributes['newsid'];
            $articleModel->ctime = time();
            $articleModel->cover_type = $request->post('cover_type');
            $articleModel->studiomenuid = $request->post('studiomenuid');
            $articleModel->article_type = 1;
            $articleModel->status = 1;
            $articleModel->save();
            $newDataModel->newsid = $newModel->attributes['newsid'];
            $newDataModel->content = $request->post('content');
            $newDataModel->hits = $request->post('hits');
            $newDataModel->copyfrom = $request->post('copyfrom');
            $newDataModel->supportcount = $request->post('supportcount');
            $newDataModel->save();
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
    private function getRetModel($articleid, $newsid) {
        if ($newsid == 0) {
            //获取精讲详细信息
            $ret[] = '';
        } else {
            $newModel = NewsService::findOne(['newsid' => $newsid]);
            $newDataModel = NewsDataService::findOne(['newsid' => $newsid]);
            $articleModel = StudioArticleService::findOne(['newsid' => $newsid]);
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
                'img' => isset($data) ? $data : ""
            ];
        }
        return $ret;
    }

}
