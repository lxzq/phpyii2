<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_role".
 *
 */
class AppInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_code','app_name'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'app_code' => '应用编码',
            'app_name' => '应用名称',
            'app_desc' => '应用描述',
            'is_show' => '是否显示',
            'app_icon' => '应用图标',
        ];
    }
}
