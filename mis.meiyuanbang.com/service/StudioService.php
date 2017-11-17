<?php

namespace mis\service;

use Yii;
use common\models\myb\Studio;
use yii\data\Pagination;
use common\service\dict\CourseDictDataService;

class StudioService extends Studio {

    /**
     * 分页获取画室列表
     */
    public static function getByPage($f_catalog_id = null, $s_catalog_id = null, $status = null) {

        $query = parent::find()->where(['<>', 'status', 2]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows_query = (new \yii\db\Query())
                ->select("b.uid,b.sname,q.contact_user,q.studio_mobile,q.studio_tel,q.username,q.status")
                ->innerJoin('ci_user_detail as b', 'q.uid=b.uid')
                ->from(parent::tableName() . ' as q')
                ->where(['<>', 'q.status', 2])
                ->andWhere(['<>', 'b.studio_type', 2]);
        $rows = $rows_query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('q.ctime DESC')
                ->all();

        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 删除用户缓存
     */
    public static function delCache($uid) {
        $redis = \Yii::$app->cache;
        $redis_key = 'user_detail_' . $uid;
        $redis_key_menu = 'studio_menu_synopsis_' . $uid;
        $redis->delete($redis_key);
        $redis->delete($redis_key_menu);
    }

    /**
     * 保存前处理缓存
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        if (isset($this->courseid)) {
            $redis->delete("course_detail_" . $this->courseid);
        }
        if (isset($this->f_catalog_id)) {
            $redis->delete("course_list_" . $this->f_catalog_id . "_" . $this->s_catalog_id);
            $redis->delete("course_list_" . $this->f_catalog_id . "_0");
             $redis->delete("course_list_" . $this->f_catalog_id . "_" . $this->s_catalog_id."_3");
        $redis->delete("course_list_" . $this->f_catalog_id . "_" . $this->s_catalog_id."_1");
        }
        if (isset($this->teacheruid)) {
            $redis->delete("teacher_course_list" . $this->teacheruid);
        }
        return $ret;
    }

}
