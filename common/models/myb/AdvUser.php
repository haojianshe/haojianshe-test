<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_adv_user".
 *
 * @property integer $advuid
 * @property string $name
 * @property string $adminuser
 * @property string $mobile
 * @property string $address
 * @property string $marks
 * @property string $username
 * @property integer $ctime
 * @property integer $status
 * @property string $advcount
 */
class AdvUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_adv_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'adminuser', 'mobile', 'username', 'ctime'], 'required'],
            [['ctime', 'status', 'advcount'], 'integer'],
            [['name', 'adminuser'], 'string', 'max' => 100],
            [['mobile', 'username'], 'string', 'max' => 50],
            [['address', 'marks'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advuid' => 'Advuid',
            'name' => 'Name',
            'adminuser' => 'Adminuser',
            'mobile' => 'Mobile',
            'address' => 'Address',
            'marks' => 'Marks',
            'username' => 'Username',
            'ctime' => 'Ctime',
            'status' => 'Status',
            'advcount' => 'Advcount',
        ];
    }
}
