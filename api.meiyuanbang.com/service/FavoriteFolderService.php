<?php

namespace api\service;

use Yii;
use common\models\myb\FavoriteFolder;
use api\service\FavoriteService;

/**
 * 
 * @author ihziluoh
 * 
 * 画夹
 */
class FavoriteFolderService extends FavoriteFolder {

    const MARK = FALSE;
    //cache key
    const FAV_FOLDER_KEY = 'favorite_folder_list_';
    //cache time
    const CACHE_TIME = 259200;

    /**
     * @desc 获取不同用户的画夹列表
     * @param  int   $uid    用户id
     * @return array $retust 获取列表
     */
    public static function getUserFavFoldList($uid) {
        $redis = Yii::$app->cache;
        $redis_key = self::FAV_FOLDER_KEY . $uid;
        $json = $redis->get($redis_key);
        if ($json) {
            $ret = json_decode($json, true);
        } else {
            //没有取到则从数据库获取
            $ret = (new \yii\db\Query())->select(['folderid', 'uid', 'name', 'fav_count', 'ctime'])
                    ->from(parent::tableName())
                    ->where(['uid' => $uid, 'status' => 0])
                    ->orderBy(' ctime desc')
                    ->all();
            //存缓存
            $strjson = json_encode($ret);
            //收藏夹列表为不常创建模块 缓存3天
            $redis->set($redis_key, $strjson, self::CACHE_TIME);
        }
        return $ret;
    }

    /**
     * @desc  用户 添加 || 修改 画夹
     * @param int     $uid        用户id
     * @param string  $folderName 画夹名称 
     */
    public static function AddUserFavFolder($uid, $folderName, $folderid = 0) {
        //是否有重名的画夹
        if (!self::getFavFolderName($uid, $folderName, $folderid)) {
            if ($folderid) {
                $model = FavoriteFolder::findOne(['folderid' => $folderid]);
            } else {
                $model = new FavoriteFolder();
                $model->uid = $uid;
                $model->ctime = time();
            }
            $model->name = $folderName;
            if ($model->save()) {
                self::RemoveCache($uid);
                return !self::MARK;
            }
        }
        return self::MARK;
    }

    /**
     * @desc 删除画夹
     * @param int $folderid  画夹id
     * @param int $uid       用户id
     */
    public static function DelUserFavFolder($uid, $folderid, $type = 0, $fid = '') {
        if ($type == 0) {
            //删除用户自己的画夹
            $where = 'folderid in(' . $folderid . ') and uid=' . $uid;
            $res = self::deleteAll($where);
            FavoriteService::deleteAll($where);
            self::RemoveCache($uid, 1);
        } elseif ($type == 1) {
            $countId = explode(',', $fid);
            //查找收藏夹的id
            $favorite = FavoriteService::findOne(['fid'=>$fid]);
            $favorite_count = FavoriteFolder::findOne(['folderid' => $favorite->folderid]);
            //改变收藏夹内的收藏数
            $favorite_count->fav_count = $favorite_count->fav_count-count($countId);
            $favorite_count->save();
            
            
            //批量改变画夹内作品
            $res = FavoriteService::updateAll(array('folderid' => $folderid), " fid in ( " . $fid . " ) and uid = " . $uid);
            $fav = FavoriteFolder::findOne(['folderid' => $folderid]);
            //修改收藏数
            $fav->fav_count = $fav->fav_count + count($countId);
            $fav->save();
        }
        if ($res) {
            self::RemoveCache($uid);
            return !self::MARK;
        }
        return self::MARK;
    }

    /**
     * @desc 判断用户下面是否已经存在画夹
     * @param int     $uid
     * @param string  $folderName
     * @param int     $folderid
     * @return object $result  
     */
    public static function getFavFolderName($uid, $folderName, $folderid) {
        if ($folderid) {
            //判断用户的其他画夹名字是否与修改的画夹名称重复
            return self::find()->where(['<>', 'folderid', $folderid])->andWhere(['uid' => $uid, 'name' => $folderName])->one();
        } else
        //添加时是否有重复的画夹存在
            return self::findOne(['uid' => $uid, 'name' => $folderName]);
    }

    /**
     * @desc 清空cache
     * @param int $uid 用户id
     */
    public static function RemoveCache($uid, $type = 0) {
        $redis = Yii::$app->cache;
        if ($type == 1) {
            $redis->delete("userext_" . $uid);
        } else
            $redis->delete(self::FAV_FOLDER_KEY . $uid);
    }

    /**
     * 得到用户画夹列表
     * @return [type] [description]
     */
    public static function getUserFavFolderList($uid, $limit = 10, $lastid = NULL) {
        //得到所有用户画夹
        $query = self::find()->where(['uid' => $uid])->andWhere(['status' => 0]);
        if ($lastid) {
            $query->andWhere(['<', 'folderid', $lastid]);
        }
        $favfolders = $query->limit($limit)->orderBy("folderid desc")->asArray()->all();
        /* 获取画夹图片 */
        if ($favfolders) {
            foreach ($favfolders as $key => $value) {
                $fav = FavoriteService::find()->where(['folderid' => $value['folderid']])->limit(7)->orderBy("fid desc")->asArray()->all();
                $favfolders[$key]['images'] = FavoriteService::getFavContentInfo($fav);
            }
        }
        return $favfolders;
    }

    /**
     * 获取收藏过同一内容的画夹
     * @param  [type]  $tid    [description]
     * @param  [type]  $type   [description]
     * @param  integer $limit  [description]
     * @param  [type]  $lastid [description]
     * @return [type]          [description]
     */
    public static function getContentFavFloderInfos($tid, $type, $limit = 10, $lastid = NULL) {
        //获取收藏过改内容的画夹id
        $query = FavoriteService::find()->alias("a")->select("b.*,a.*")->where(['tid' => $tid])->andWhere(['type' => $type]);
        if ($lastid) {
            $query->andWhere(['<', "fid", $lastid]);
        }
        $query->leftJoin(self::tableName() . ' as b', 'a.folderid=b.folderid');
        $folders = $query->limit($limit)->orderBy("fid desc")->asArray()->all();
        /* //处理画夹id 变成数组
          $folderid_arr = [];
          foreach ($folderids as $key => $value) {
          $folderid_arr[] = $value['folderid'];
          } */
        return $folders;
    }

    /**
     * 批量获取画夹信息
     * @param  [type] $folderid_arr [description]
     * @return [type]               [description]
     */
    public static function getFolderInfoByArray($folderid_arr) {
        $folderinfo = self::find()->where(['in', 'folderid', $folderid_arr])->andWhere(['status' => 0])->asArray()->all();
        return $folderinfo; //获取画夹信息
    }

    /**
     * 获取画夹详情（包括内容列表）
     * @param  [type]  $folderid [description]
     * @param  integer $limit    [description]
     * @param  [type]  $lastid   [description]
     * @return [type]            [description]
     */
    public static function getFavFolderInfo($folderid) {
        $favinfo = self::find()->where(['folderid' => $folderid])->andWhere(['status' => 0])->asArray()->one();
        return $favinfo;
    }

    /**
     * 增加浏览量
     * @param [type] $folderid [description]
     */
    public static function addHits($folderid) {
        $model = self::find()->where(['folderid' => $folderid])->one();
        if ($model) {
            $model->hits = $model->hits + 1;
            $model->save();
        }
    }

}
