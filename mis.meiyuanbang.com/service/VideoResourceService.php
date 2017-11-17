<?php

namespace mis\service;

use Yii;
use common\models\myb\VideoResource;
use yii\data\Pagination;

class VideoResourceService extends VideoResource {

    /**
     * 获取视频列表 $listtype=“sel” 选择列表 
     * @param  string $video_type [description]
     * @param  [type] $listtype   [description]
     * @return [type]             [description]
     */
    public static function getDataByPage($video_type = '', $desc = '', $listtype = '') {
        $query = parent::find();
        $query->from(parent::tableName())->where(['status' => 1]);
        if (intval($video_type) > 0) {
            $query->andWhere(['video_type' => $video_type]);
        }else if($video_type == -1){
            $query->andWhere("video_type is null");
        }

        if ($desc) {
            $query->andWhere(['like', 'desc', $desc]);
        }
        if ($listtype == "sel") {
            $query->andWhere(['not', ['sourceurl' => null]]);
            $query->andWhere(['not', ['m3u8url' => null]]);
        }
        /*
          if($condition){
          $query->andWhere(['condition'=>$condition]);
          }
         */
        $countQuery = $query->count();
        $pages = new Pagination(['totalCount' => $countQuery]);

        $query = new \yii\db\Query();
        $query->select("*")->from(parent::tableName())->where(['status' => 1]);
        /*
          if($condition){
          $query->andWhere(['condition'=>$condition]);
          }
         */
        if (intval($video_type) > 0) {
            $query->andWhere(['video_type' => $video_type]);
        }else if($video_type == -1){
            $query->andWhere("video_type is null");
        }
        if ($desc) {
            $query->andWhere(['like', 'desc', $desc]);
        }
        if ($listtype == "sel") {
            $query->andWhere(['not', ['sourceurl' => null]]);
            $query->andWhere(['not', ['m3u8url' => null]]);
        }
        $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('videoid DESC')
                ->all();
        return ['models' => $models, 'pages' => $pages, 'pageSize' => 1];
    }

    /**
     * 更新runid 通过filename查找更新
     * @param  [type] $filename [description]
     * @param  [type] $runid    [description]
     * @return [type]           [description]
     */
    public static function updateRunidByFileName($filename, $runid) {
        $findvideo = self::find()->where(['filename' => $filename])->one();
        if ($findvideo) {
            $findvideo->runid = $runid;
            $ret = $findvideo->save();
            if ($ret) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function updateM3u8UrlByRunid($runid) {
        $findvideo = self::find()->where(['runid' => $runid])->one();
        if ($findvideo) {
            $sourceurl = $findvideo->sourceurl;
            //获取文件名
            $filename_fomat = substr(strrchr($sourceurl, "/"), 1);
            $filename = strstr($filename_fomat, '.', true);
            //取得文件名前url
            $lastpoint = strrpos($sourceurl, '/');
            $url_head = substr($sourceurl, 0, $lastpoint);
            //更改host
            $m3u8host = parse_url(Yii::$app->params['videourl'])['host'];
            $url_head_arr = parse_url($url_head);
            $url_head = $url_head_arr["scheme"] . '://' . $m3u8host . $url_head_arr["path"];
            $findvideo->m3u8url = $url_head . "/" . $runid . '/' . $filename . '.m3u8';
            $ret = $findvideo->save();
            if ($ret) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 设置视频直播转录播
     * @param int    $liveid   直播变化id
     * @param string $m3u8url  m3u8url地址
     * @param string $mp4url  mp4url 地址
     * @param string $filename 文件名
     */
    public static function setVideoLive($liveid, $m3u8url = null, $mp4url = null, $filename = null, $runid = null) {
        $redis = Yii::$app->cache;
        $res = false;
        if (is_numeric($liveid)) {
            //根据liveid获取直播的封面
            $liveObj = LiveService::findOne(['liveid' => $liveid]);
            if (!empty($liveObj) && empty($liveObj->videoid)) {
                //(3)video插入记录
                try {
                    $innerTransaction = Yii::$app->db->beginTransaction();
                    $videoRes = new VideoResourceService();
                    $videoRes->video_type = 1;
                    $videoRes->maintype = $liveObj->f_catalog_id;
                    $videoRes->subtype = $liveObj->s_catalog_id;
                    $videoRes->filename = $filename;
                    $videoRes->coverpic = $liveObj->live_thumb_url;
                    $videoRes->sourceurl = $mp4url;
                    $videoRes->m3u8url = $m3u8url;
                    $videoRes->runid = $runid;
                    $videoRes->status = 1;
                    $videoRes->ctime = time();
                    if ($videoRes->save()) {
                        //(4)更新live表中videoid字段并清除缓存
                        $liveObj->videoid = (string) $videoRes->attributes['videoid'];
                        $liveObj->save();
                        $innerTransaction->commit();
                    }
                    $redis_key = 'get_live_data_info_' . $liveid;
                    $redis_k = 'studio_live_list';
                    $redis->delete($redis_key);
                    $redis->delete('live_detail_'.$liveid);
                    $redis->delete("get_video_add_" . $videoRes->attributes['videoid']);
                    $redis->delete($redis_k);
                    $redis->delete("studio_coures_list");
                    $redis->delete("studio_live_list");
                    $redis->delete("studio_studio_course");
                    $res = true;
                } catch (Exception $ex) {
                    return $e->getMessage();
                }
            }
        }
        return $res;
    }

}
