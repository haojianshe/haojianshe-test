<?php

namespace mis\service;

use Yii;
use common\models\myb\SoundResource;
use yii\data\Pagination;


class SoundResourceService extends SoundResource {

    /**
     * 获取音频列表
     * @param int $studiomenuid
     * @return intger
     */
    public static function getByPage($sound_type,$filename,$desc) {

        $query = self::find()->where(['status'=>1]);
        if($sound_type){
            $query->andWhere(['sound_type'=>$sound_type]);

        }
        if($filename){
            $query->andWhere(['like','filename',$filename]);
        }        
        if($desc){
            $query->andWhere(['like','desc',$desc]);
        }


        $countQuery = clone $query;
        //分页获取
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 30]);
   
        $rows = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('ctime DESC')
                ->asArray()
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }


}
