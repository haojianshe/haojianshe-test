<?php

namespace api\service;

use Yii;
use common\models\myb\MaterialSubject;
use common\redis\Cache;
use common\models\myb\Tags;
use common\service\CommonFuncService;

/**
 * 专题相关方法
 */
class MaterialSubjectService extends MaterialSubject {

    /**
     * 得到专题列表
     * @param  [type] $lastid [description]
     * @param  [type] $rn     [description]
     * @return [type]         [description]
     */
    public static function getMaterialList($lastid, $rn) {
        $rediskey = "material_subject_list";
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $mlist = $redis->lrange($rediskey, 0, -1);
        if (empty($mlist)) {
            //数据库获取
            $mlist_db = self::getMaterialListDB();
            foreach ($mlist_db as $key => $value) {
                $mlist[] = $value['subjectid'];
                $redis->rpush($rediskey, $value['subjectid'], true);
            }
            $redis->expire($rediskey, 3600 * 24 * 3);
        }
        if ($lastid === 0) {
            $return_data = array_slice($mlist, 0, $rn);
        } else {
            $idx = array_search($lastid, $mlist);
            $return_data = array_slice($mlist, $idx + 1, $rn);
        }

        return $return_data;
    }

    /**
     * 得到数据库中所有专题
     * @return [type] [description]
     */
    public static function getMaterialListDB() {
        //数据库中获取专题id
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select subjectid from ' . parent::tableName() . ' where status=0 order by subjectid desc limit 500');
        $data = $command->queryAll();
        return $data;
    }

    /**
     * 得到单条专题详情
     * @param  [type] $mid [description]
     * @return [type]      [description]
     */
    public static function getMaterialDetail($sid) {
        $rediskey = "material_subject_detail" . $sid;
        $redis = Yii::$app->cache;
        $mdetail = $redis->hgetall($rediskey);
        if (empty($mdetail)) {
            //数据库获取
            $mdetaildb = MaterialSubject::findOne(['subjectid' => $sid]);
            if (empty($mdetaildb)) {
                return 0;
            }
            //判断是否存在
            if ($mdetaildb) {
                $mdetail = $mdetaildb->attributes;
                //计算图片数
                $mdetail['imgcount'] = count(explode(",", $mdetail['rids']));
                $redis->hmset($rediskey, $mdetail);
                $redis->expire($rediskey, 3600 * 24 * 3);
            }
        }
        return $mdetail;
    }

    /**
     * 增加专题浏览量
     * @param [type] $sid [description]
     */
    public static function addHits($sid) {
        $mdetail = MaterialSubject::findOne(['subjectid' => $sid]);
        if (!$mdetail) {
            return true;
        }
        $mdetail->hits = $mdetail->hits + 1;
        if ($mdetail->save()) {
            $rediskey = "material_subject_detail" . $sid;
            $redis = Yii::$app->cache;
            $ret = $redis->hincrby($rediskey, 'hits', 1);
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
     * 增加专题点赞数
     * @param [type] $sid [description]
     */
    public static function addZan($sid) {
        $mdetail = MaterialSubject::findOne(['subjectid' => $sid]);
        if (!$mdetail) {
            return 0;
        }
        $mdetail->supportcount = $mdetail->supportcount + 1;
        if ($mdetail->save()) {
            $rediskey = "material_subject_detail" . $sid;
            $redis = Yii::$app->cache;
            $ret = $redis->hincrby($rediskey, 'supportcount', 1);
            if ($ret) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     * 缓存获取一级分类对应的列表
     * @param  [type]  $f_catalog_id [description]
     * @param  [type]  $lastid       [description]
     * @param  integer $rn           [description]
     * @return [type]                [description]
     */
    public static function getSubjectList($f_catalog_id, $lastid = NULL, $rn = 50) {
        $redis = Yii::$app->cache;
        if (!$f_catalog_id) {
            $rediskey = "subject_list";
        } else {
            $rediskey = "subject_list_" . $f_catalog_id;
        }

        $list_arr = $redis->lrange($rediskey, 0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if (empty($list_arr)) {
            $model = self::getSubjectListDb($f_catalog_id);
            $ids = '';
            foreach ($model as $key => $value) {
                $ids.=$value['subjectid'] . ',';
                $ret = $redis->rpush($rediskey, $value['subjectid'], true);
            }
            $redis->expire($rediskey, 3600 * 24 * 3);
            $ids = substr($ids, 0, strlen($ids) - 1);
            $list_arr = explode(',', $ids);
        }
        //分页数据获取
        if (empty($lastid)) {
            $idx = 0;
            $ids_data = $redis->lrange($rediskey, 0, $rn - 1);
        } else {
            $idx = array_search($lastid, $list_arr);
            $ids_data = $redis->lrange($rediskey, $idx + 1, $idx + $rn);
        }
        return $ids_data;
    }

    /**
     * 数据库获取列表
     * @param  [type] $f_catalog_id [description]
     * @return [type]               [description]
     */
    public static function getSubjectListDb($f_catalog_id) {
        $query = self::find()->select('subjectid')->where(['status' => 0]); //->andWhere(['subject_typeid' => $f_catalog_id]);

        if ($f_catalog_id == 0) {
            //全部
            $query->orderBy(' stick_date desc,ctime desc');
        } else if ($f_catalog_id == 1) {
            //最热
            $query->orderBy(' stick_date desc,ctime desc,hits desc');
        } else {
            //名师 大师 联考 校考
            $query->andWhere(['subject_typeid' => $f_catalog_id])->orderBy(' stick_date desc,ctime desc');
        }
        #echo $query->createCommand()->getRawSql();
        #exit;
        return $query->all();
    }

    /**
     * 得到单条专题详情
     * @param  [type] $mid [description]
     * @return [type]      [description]
     */
    public static function getSubjectMaterialDetail($time, $subject_typeid) {
        $query = self::find()->select('subjectid')->where(['<', 'ctime', $time])->andWhere(['status' => 0]);
        if ($subject_typeid) {
            //名师 大师 联考 校考
            $query->andWhere(['subject_typeid' => $subject_typeid]);
        }
        $row = $query->orderBy(' subjectid desc ')->limit(10)->asArray()->all(); //->createCommand()->getRawSql();
        if (empty($row)) {
            return false;
        }
        $arr = [];
        if ($row) {
            foreach ($row as $key => $val) {
                $arr[$key] = $val['subjectid'];
            }
        }

        if (count($arr) > 4) {
            //取出时间小于该专题的10个专题，随机取4个做为推荐
            $array = array_rand(array_flip($arr), 4);
            $row = self::find()->select(['subjectid', 'title', 'picurl'])->where(['in', 'subjectid', $array])->asArray()->all();
        } else {
            $row = self::find()->select(['subjectid', 'title', 'picurl'])->where(['in', 'subjectid', $arr])->asArray()->all();
        }

        foreach ($row as $k => $v) {
            $newArray[] = [
                'subjectid' => $v['subjectid'],
                'title' => $v['title'],
                'picurl' => json_decode($v["picurl"])->n->url,
            ];
        }
        return $newArray;
    }

    public static function getMaterialSubjectByRand($f_catalog_id = 0, $limit = 10, $orderby = 'rand') {
        $query = self::find()->select('subjectid')->where(['status' => 0]);
        if (intval($f_catalog_id) > 0) {
            $query->andWhere(['subject_typeid' => $f_catalog_id]);
        }
        $query->limit($limit);
        switch ($orderby) {
            case 'rand':
                $query->orderBy("rand()");
                break;

            default:
                $query->orderBy("subjectid desc");
                break;
        }
        $subjectid_arr = $query->all();
        $return_data = [];
        if ($subjectid_arr) {
            foreach ($subjectid_arr as $key => $value) {
                $subjectinfo = self::getMaterialDetail($value['subjectid']);
                $subjectinfo["picurl"] = json_decode($subjectinfo["picurl"]);
                $subjectinfo["picurl"]->l = CommonFuncService::getPicByType((array) $subjectinfo['picurl']->n, 'l');
                $subjectinfo["picurl"]->t = CommonFuncService::getPicByType((array) $subjectinfo['picurl']->n, 't');
                $return_data[] = $subjectinfo;
            }
        }
        return $return_data;
    }

    /**
     * 获取素材下面的筛选tag
     * @param int $f_catalog_id
     * @param int $s_catalog_id
     */
    public static function getMaterialTag($f_catalog_id, $s_catalog_id) {
        $redis = Yii::$app->cache;
        $redis_key = 'get_material_tag_' . $f_catalog_id . '_' . $s_catalog_id; //缓存key
        $mlist = $redis->get($redis_key);
        if (empty($mlist)) {
            $data = (new \yii\db\Query())->select(['tag_group_name as name', 'tag_group_type', 'taggroupid', 'is_display'])
                    ->from('myb_tag_group')
                    ->where(['f_catalog_id' => $f_catalog_id])
                    ->andWhere(['s_catalog_id' => $s_catalog_id])
                    ->andWhere(['status' => 1])
                    ->all();
            foreach ($data as $key => $val) {
                $data[$key]['tags'] = Tags::find()->select('tag_name')->where(['taggroupid' => $val['taggroupid']])->asArray()->all();
            }
            foreach ($data as $k => $v) {
                foreach ($v['tags'] as $kk => $vv) {
                    $data[$k]['tags'][$kk] = $vv['tag_name'];
                }
            }
            $newarray = [];
            foreach ($data as $kk => $vv) {
                if ($vv['is_display'] == 1) {
                    $newarray['tag_show'][] = $vv;
                } else {
                    $newarray['tag_hide'][] = $vv;
                }
            }
            $mlist = json_encode($newarray);
            $redis->set($redis_key, $mlist);
            $redis->expire($redis_key, 3600 * 24 * 3);
        }
        if (empty($mlist)) {
            return array();
        } else {
            return json_decode($mlist, 1);
        }
    }
    /**
     * 首页推荐
     * @param  [type] $search_arr [description]
     * @return [type]             [description]
     */
    public static function getHomeRecommend($search_arr){
        $ret_data=[];
        foreach ($search_arr as $key => $value) {
            $materialsub=self::getMaterialSubjectByRand($value['f_catalog_id'],$value['limit']);
            if($materialsub){
                $ret_data=array_merge($ret_data,$materialsub);
            }
        }
        return $ret_data;
    }

}
