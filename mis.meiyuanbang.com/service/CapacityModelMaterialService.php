<?php
namespace mis\service;

use Yii;
use common\models\myb\CapacityModelMaterial;
use yii\data\Pagination;
/**
 * mis用户相关的业务逻辑层
 * 本方法实现了IdentityInterface，可以做为yii\web\user类的登录验证类使用
 * @author Administrator
 *
 */
class CapacityModelMaterialService extends CapacityModelMaterial 
{
    public static function getCapacityMaterialByPage($f_catalog_id,$s_catalog_id,$item_id){
        $query=parent::find();
        $query->from(parent::tableName())
                ->where(['status'=>0]);
                if($f_catalog_id){
                    $query->andWhere(['f_catalog_id'=>$f_catalog_id]);
                }
                if($s_catalog_id){
                    $query->andWhere(['s_catalog_id'=>$s_catalog_id]);
                }
                if($item_id){
                    $query->andWhere(['item_id'=>$item_id]);
                }
                $countQuery=$query->count();
        $pages= new Pagination(['totalCount'=>$countQuery]);
        $query=new \yii\db\Query();
        $query->select("*")->from(parent::tableName())->where(['status'=>0]);
                if($f_catalog_id){
                    $query->andWhere(['f_catalog_id'=>$f_catalog_id]);
                }
                if($s_catalog_id){
                    $query->andWhere(['s_catalog_id'=>$s_catalog_id]);
                }
                if($item_id){
                    $query->andWhere(['item_id'=>$item_id]);
                }
        $models=$query->offset($pages->offset)->limit($pages->limit)->orderBy("materialid desc")->all();
        return ['models'=>$models,"pages"=>$pages,'pageSize'=>1];

    }
    /**
     * 清除缓存
     * @param  [type] $fcatalogid [description]
     * @param  [type] $scatalogid [description]
     * @param  [type] $item_id    [description]
     * @return [type]             [description]
     */
    public static function clearCMMRedis($fcatalogid,$scatalogid,$item_id){
        $redis = Yii::$app->cache;     
        $redis_key = 'CapacityModelMaterial_' . $fcatalogid . '_' . $scatalogid . '_' . $item_id;
        $ret=$redis->delete($redis_key);
        if($ret){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 重载model的save方法，保存后处理缓存
     * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
     */
    public function save($runValidation = true, $attributeNames = NULL){
        $isnew = $this->isNewRecord;
        $ret = parent::save($runValidation,$attributeNames);
        //处理缓存
        if($isnew==false){
            self::clearCMMRedis($this->f_catalog_id,$this->s_catalog_id,$this->item_id);
        }
        return $ret;
    }
}
