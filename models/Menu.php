<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_role".
 *
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_name','menu_url'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'menu_name' => '菜单名字',
            'menu_ulr' => '菜单URL'
        ];
    }
}
