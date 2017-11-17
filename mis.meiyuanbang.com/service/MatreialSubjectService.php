<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\MaterialSubject;
use common\models\myb\CapacitymodelMaterial;
use common\models\myb\Tweet;
use common\models\myb\TagGroup;
use common\models\myb\Tags;
use common\service\dict\BookDictDataService;
use mis\service\ResourceService;

/**
 * 
 */
class MatreialSubjectService extends MaterialSubject {

    public static function getSubjectByPage() {
        $query = parent::find();
        $countQuery = $query->from(parent::tableName())->where(['<>','status',1])->count();
        $pages = new Pagination(['totalCount' => $countQuery]);

        $query = new \yii\db\Query();
        $models = $query->select("*")->from(parent::tableName())->where(['status' => 0])
                 ->where(['<>','status',1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('ctime DESC')
                ->all();
        return ['models' => $models, 'pages' => $pages, 'pageSize' => 1];
    }

    /**
     * 重载model的save方法，保存后处理缓存
     * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $ret = parent::save($runValidation, $attributeNames);
        //处理缓存
        $redis = Yii::$app->cache;
        if ($isnew == false) {
            //清除detail缓存     
            $rediskey = "material_subject_detail" . $this->subjectid;
            $redis->delete($rediskey);
            //清除列表缓存
            $rediskeyl = "material_subject_list";
            $redis->delete($rediskeyl);
        } else {
            //增加列表
            $rediskey = "material_subject_list";
            $redis->lpush($rediskey, $this->subjectid, true);
            $redis->expire($rediskey, 3600 * 24 * 3);
        }
        $redis->delete("subject_list");
        $redis->delete("subject_list_".$this->subject_typeid);
        $redis->delete("material_subject_detail" . $this->subjectid);
        return $ret;
    }

    /**
     * 获取标签
     * @param type $type
     * @param type $catalog
     * @param type $tag
     * @return boolean
     */
    public static function getTag($type = 1, $catalog, $tag) {
        if ($type == 1) {
            #material
            $Name = 'MatreialSubjectService';
            #$ret = CapacitymodelMaterial::find()->select('new_tags,new_s_catalog_id')->where(['materialid' => $id])->asArray()->one();
        } else {
            #vesttweet
            $Name = 'TweetServiceTwo';
            # $ret = Tweet::find()->select('new_tags,new_s_catalog_id')->where(['tid' => $id])->asArray()->one();
        }
        $query = new \yii\db\Query();
        $models = $query->select("tag_group_name,tag_group_type,taggroupid")->from(TagGroup::tableName())//->where(['status' => 1])
                ->where(['s_catalog_id' => $catalog])
                ->all();
        if (!empty($models)) {
            foreach ($models as $key => $val) {
                $models[$key]['array'] = $query->select("tag_name")->from(Tags::tableName())//->where(['status' => 1])
                        ->where(['taggroupid' => $val['taggroupid']])
                        ->all();
            }
        }


        $array = [];
        foreach ($models as $k => $v) {
            foreach ($v['array'] as $kk => $vv) {
                $models[$k]['arr'][$vv['tag_name']] = $vv['tag_name'];
            }
            if ($v['tag_group_type'] == 1) {
                $models[$k]['tag_catalog_types'] = 'radio';
            } else {
                $models[$k]['tag_catalog_types'] = 'checkbox';
            }
            $models[$k]['Name'] = 'Name#' . $k;
        }

        $str = "";
        foreach ($models as $kvk => $vkv) {
            $str .="<tr><td style='width:7.7%'>" . $vkv['tag_group_name'] . "</td><td>"; # '.$vkv['Name'].'
            $str .=BookDictDataService::radioChechboxTwo($Name . '[' . $vkv['Name'] . ']' . '[]', $vkv['arr'], $vkv['tag_catalog_types'], $tag,7);
            $str .='</td></tr>';
        }
        $str .= '';
        return ['models' => $str, 'id' => $id, 'tag' => $tag];
    }

    //获取专题下面的老师
    static public function getSubject($id) {
        $res = MatreialSubjectService::find()->select(['rids'])->where(['subjectid' => $id])->asArray()->one();
        if (strpos($res['rids'], ',') !== FALSE) {
            $userArray = explode(',', $res['rids']);
            foreach ($userArray as $key => $val) {
                $user['data'][] = ResourceService::find()->select(['img','rid'])->where(['rid' => $val])->asArray()->all();
            }
        } else {
            $user['data'][] = ResourceService::find()->select(['img','rid'])->where(['rid' => $res['rids']])->asArray()->all();
        }
        $user['id'] = $id;
        return $user;
    }

}
