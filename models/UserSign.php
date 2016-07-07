<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_sign".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $add_time
 * @property string $sign_address
 */
class UserSign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_sign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'add_time'], 'required'],
            [['user_id'], 'integer'],
            [['add_time'], 'safe'],
            [['sign_address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'add_time' => 'Add Time',
            'sign_address' => 'Sign Address',
        ];
    }

    public function getUser(){
        return $this->hasOne(UserInfo::className(),['id'=>'user_id']);
    }
}
