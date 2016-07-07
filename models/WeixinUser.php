<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "weixin_user".
 *
 * @property integer $id
 * @property string $nickname
 * @property string $userface
 * @property string $openid
 * @property integer $sex
 * @property integer $group_id
 * @property string $remark
 * @property string $token
 * @property string $province
 * @property string $city
 * @property integer $is_del
 * @property integer $subscribe_time
 * @property integer $updated_time
 * @property string $weixin_group_id
 */
class WeixinUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weixin_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'group_id', 'is_del', 'subscribe_time', 'updated_time'], 'integer'],
            [['nickname'], 'string', 'max' => 20],
            [['userface'], 'string', 'max' => 255],
            [['openid', 'token', 'city'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 30],
            [['province'], 'string', 'max' => 10],
            [['weixin_group_id'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'nickname' => '昵称',
            'userface' => '头像',
            'openid' => 'openid',
            'sex' => '性别1男2女',
            'group_id' => '分组id',
            'remark' => '备注',
            'token' => 'token',
            'province' => '省份',
            'city' => '城市',
            'is_del' => '状态1关注2取消关注',
            'subscribe_time' => '关注时间',
            'updated_time' => '更新时间',
            'weixin_group_id' => '微信用户组id',
        ];
    }
}
