<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "org_shop_info".
 *
 * @property integer $id
 * @property string $org_id
 * @property string $shop_id
 */
class OrgShopInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org_shop_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id', 'shop_id'], 'required'],
            [['org_id', 'shop_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_id' => 'Org ID',
            'shop_id' => 'Shop ID',
        ];
    }

    public function getOrg()
    {
        return $this->hasOne(OrgInfo::className(), ['id' => 'org_id']);
    }

    public function getShop()
    {
        return $this->hasOne(ShopInfo::className(), ['id' => 'shop_id']);
    }
}
