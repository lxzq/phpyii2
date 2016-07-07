<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_drive".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $drive_name
 * @property string $drive_code
 */
class ShopDrive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_drive';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id'], 'required'],
            [['shop_id'], 'integer'],
            [['drive_name', 'drive_code'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => '店铺ID',
            'drive_name' => '设备名称',
            'drive_code' => '设备编号',
        ];
    }
}
