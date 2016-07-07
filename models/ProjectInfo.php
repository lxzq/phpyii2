<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_info".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $name
 * @property string $image
 * @property string $notes
 * @property double $price
 * @property string $describe
 */
class ProjectInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'name'], 'required'],
            [['shop_id'], 'integer'],
            [['notes'], 'string'],
            [['price'], 'number'],
            [['name', 'image'], 'string', 'max' => 100],
            [['describe'], 'string', 'max' => 500]
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
            'name' => '项目名称',
            'image' => '项目图片',
            'notes' => '项目详情',
            'price' => '项目价格',
            'describe' => '项目介绍',
        ];
    }
}
