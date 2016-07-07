<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_group".
 *
 * @property string $id
 * @property string  $weixin_menu_id
 * @@property string $weixin_group_id
 * @property integer $sort
 * @property string $name
 * @property string $group_id
 * @property string $token
 * @property integer $user_id
 * @property integer $add_time
 * @property integer $change_user_id
 * @property integer $change_time
 */
class MenuGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'user_id', 'add_time', 'change_user_id', 'change_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['group_id', 'token'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'sort' => '排序',
            'name' => '菜单组名',
            'group_id' => '分组id',
            'token' => 'Token',
            'user_id' => '用户id',
            'add_time' => '添加时间',
            'change_user_id' => '用户id',
            'change_time' => '修改时间',
            'weixin_group_id'=>'微信分组id'
        ];
    }
}
