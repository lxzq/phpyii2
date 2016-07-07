<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_registration".
 *
 * @property integer $id
 * @property string $username
 * @property integer $number
 * @property string $phone
 * @property string $create_time
 * @property integer $shopId
 */
class ShopRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'number', 'phone', 'shopId'], 'required'],
            [['number', 'shopId'], 'integer'],
            [['create_time'], 'safe'],
            [['username', 'phone'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'number' => 'Number',
            'phone' => 'Phone',
            'create_time' => 'Create Time',
            'shopId' => 'Shop ID',
        ];
    }

    public function getShop(){
        return $this->hasOne(ShopInfo::className(),['id'=>'shopId']);
    }
}
