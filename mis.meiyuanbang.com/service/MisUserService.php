<?php

namespace mis\service;

use Yii;
use yii\web\IdentityInterface;
use yii\data\Pagination;
use mis\models\MisUser;

/**
 * mis用户相关的业务逻辑层
 * 本方法实现了IdentityInterface，可以做为yii\web\user类的登录验证类使用
 * @author Administrator
 *
 */
class MisUserService extends MisUser implements IdentityInterface {

    /**
     * 封装IdentityInterface方法,根据id获取实体
     */
    public static function findIdentity($id) {
        return static::findOne(['mis_userid' => $id]);
    }

    /**
     * 封装IdentityInterface方法，暂时不实现
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * 封装IdentityInterface方法
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * 封装IdentityInterface方法
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * 封装IdentityInterface方法
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 根据用户名获取用户信息
     * @param unknown $username
     */
    public static function findByUsername($username) {
        return static::findOne(['mis_username' => $username, 'status' => 0]);
    }

    /**
     * 验证用户密码
     * 自己重写，与yii提供的验证方法不一样
     */
    public function validatePassword($password) {
        if (md5($password) === $this->password) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 分页获取所有后台用户信息
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照
     */
    public static function getUserByPage() {
        $query = parent::find();
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 100]);
        //获取数据
        $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('mis_userid DESC')
                ->all();
        return ['models' => $models, 'pages' => $pages];
    }

    /**
     * 保存时操作缓存
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        //清除用户角色缓存
        $redis_key = "misroleids" . $this->mis_userid;
        $redis->delete($redis_key);
        return $ret;
    }

    /**
     * 获取精讲发布人员
     */
    public static function getUserDetail() {
        return self::find()->select(['mis_realname', 'mis_userid'])->where(['status' => 0])->asArray()->all();
    }

}
