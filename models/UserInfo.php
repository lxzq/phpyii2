<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $phone
 * @property string $nickname
 * @property string $userface
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $authKey
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $weibo_uid
 * @property string $openid
 * @property integer $qq_uid
 * @property integer $sex
 * @property string $location
 * @property string $password
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'qq_uid', 'sex'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['nickname', 'password_hash', 'password_reset_token', 'email', 'authKey', 'weibo_uid', 'openid', 'location', 'password'], 'string', 'max' => 100],
            [['userface'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'nickname' => 'Nickname',
            'userface' => 'Userface',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'authKey' => 'Auth Key',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'weibo_uid' => 'Weibo Uid',
            'openid' => 'Openid',
            'qq_uid' => 'Qq Uid',
            'sex' => 'Sex',
            'location' => 'Location',
            'password' => 'Password',
        ];
    }
}
