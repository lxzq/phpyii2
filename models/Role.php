<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_role".
 *
 * @property integer $id
 * @property integer $role_name
 * @property string $role_desc
 * @property string $create_date
 * @property string $crete_user_id
 * @property string $modify_date
 * @property string $modify_user_id
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_name','role_desc'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'role_name' => '角色名称',
            'role_desc' => '角色描述'
        ];
    }
}
