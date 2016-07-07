<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_info".
 *
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $notes
 * @property string $address
 * @property integer $area_id
 * @property string $psword
 * @property integer $dept_no
 * @property integer $dept_id
 */
class ShopInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['notes'], 'string'],
            [['area_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['logo'], 'string', 'max' => 150],
            [['address'], 'string', 'max' => 150],
            [['psword'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'logo' => 'Logo',
            'notes' => 'Notes',
            'area_id' => '区域ID，预留字段',
            'address' => '店铺地址',
            'psword' => '店铺密码',
            'dept_no'=>'对应一卡通部门编号',
            'dept_id'=>'对应一卡通部门ID'
        ];
    }
}
