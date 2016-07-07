<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auto_reply".
 *
 * @property string $id
 * @property string $keyword
 * @property string $type
 * @property string $content
 * @property integer $group_id
 * @property string $image_id
 * @property integer $user_id
 * @property string $token
 */
class AutoReply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_reply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['group_id', 'image_id', 'user_id'], 'integer'],
            [['keyword'], 'string', 'max' => 255],
            [['type', 'token'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'keyword' => '关键词',
            'type' => '消息类型1text文本2news图文3images图片',
            'content' => '文本内容',
            'group_id' => '图文id',
            'image_id' => '上传图片',
            'user_id' => '管理员ID',
            'token' => 'Token'
        ];
    }
}
