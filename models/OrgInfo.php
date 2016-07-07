<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "org_info".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $name
 * @property string $logo
 * @property string $notes
 * @property string $describe
 */
class OrgInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['logo'], 'string', 'max' => 150],
            [['describe'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '机构名称',
            'logo' => 'Logo',
            'notes' => 'Notes',
            'describe'=>'机构简介'
        ];
    }
    public function getShop(){
        return $this->hasMany(OrgShopInfo::className(),['org_id'=>'id']);
    }
}

