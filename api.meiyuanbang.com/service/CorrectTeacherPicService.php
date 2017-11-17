<?php

namespace api\service;

use Yii;
use common\models\myb\CorrectTeacherPic;

/**
 * 
 * @author Administrator
 *
 */
class CorrectTeacherPicService extends CorrectTeacherPic {

    /**
     * 分页获取批改老师常用图片id
     * @param unknown $folderId
     * @param unknown $lasttime
     * @param unknown $rn
     * @return unknown|NULL
     */
    static function getPageByUtime($folderId, $uid, $lasttime, $rn) {
        //从数据库获取数据
        $query = static::find()->select(['rid', 'utime'])
                ->where(['teacher_uid' => $uid])
                ->andWhere(['folderid' => $folderId]);
        if ($lasttime) {
            $query = $query->andWhere(['<', 'utime', $lasttime]);
        }
        $rids = $query->orderBy('utime DESC')
                ->limit($rn)
                ->all();
        return $rids;
    }

    /**
     * 根据图片的一级二级分类来获取老师的常用返利图
     * @param type $f_catalog_id
     * @param type $s_catalog_id
     * @param type $uid
     * @param type $lasttime
     * @param type $rn
     * @return type
     */
    static function getPageByCatalog($f_catalog_id, $s_catalog_id, $uid, $lasttime, $rn) {
        //从数据库获取数据
        $query = static::find()->select(['rid', 'utime'])
                ->where(['teacher_uid' => $uid])
                ->andWhere(['f_catalog_id' => $f_catalog_id]);
        if ($s_catalog_id > 0) {
            $query = $query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }
        //分页时间戳
        if ($lasttime) {
            $query = $query->andWhere(['<', 'utime', $lasttime]);
        }
        $rids = $query->orderBy('utime DESC')
                ->limit($rn)
                #->createCommand()->getRawSql();
                ->all();
        return $rids;
    }

    /**
     * 判断老师是否添加过常用范例图
     * @param unknown $uid
     * @param unknown $rid
     * @param unknown $folderid
     * @param unknown $utime 为了保证多图上传时utime不重复，则给当前时间加时间戳
     * @return boolean
     */
    static function addPic($uid, $rid, $folderid, $utime,$f_catalog_id=0,$s_catalog_id=0) {
        //判断是否添加过这个素材
        $model = static::findOne(['teacher_uid' => $uid, 'rid' => $rid]);
        if (!$model) {
            //未添加过的图片才添加到常用范例图
            $model = new CorrectTeacherPicService();
            $model->teacher_uid = $uid;
            $model->rid = $rid;
            $model->ctime = time();
            $model->f_catalog_id = $f_catalog_id;
            $model->s_catalog_id = $s_catalog_id;
            $model->utime = $utime;
            $model->folderid = $folderid;
            $model->save();
            return true;
        } else {
            $model->utime = $utime;
            $model->save();
        }
        return false;
    }

}
