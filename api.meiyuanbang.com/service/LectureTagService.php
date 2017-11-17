<?php

namespace api\service;

use Yii;
use common\models\myb\LectureTag;

/**
 * 
 * @author ihziluoh
 * 
 * 精讲专题标签
 */
class LectureTagService extends LectureTag {
    /**
     * 通过专题id 获取专题标签
     * @param  [type] $newsid [description]
     * @return [type]         [description]
     */
    public static function getLectureTagByNewsid($newsid){
        $ret=self::find()->where(['status'=>1])->andWhere(['newsid'=>$newsid])->orderBy("listorder desc")->asArray()->all();
        return $ret;
    }
}
