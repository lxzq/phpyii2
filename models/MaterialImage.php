<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "material_image".
 *
 * @property string $id
 * @property integer $cover_id
 * @property string $cover_url
 * @property string $media_id
 * @property string $wechat_url
 * @property integer $add_time
 * @property integer $user_id
 * @property string $token
 * @property integer $is_use
 */
class MaterialImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'material_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cover_id', 'add_time', 'user_id', 'is_use'], 'integer'],
            [['cover_url', 'wechat_url'], 'string', 'max' => 255],
            [['media_id', 'token'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'cover_id' => '图片在本地的ID',
            'cover_url' => '本地URL',
            'media_id' => '微信端图文消息素材的media_id',
            'wechat_url' => '微信端的图片地址',
            'add_time' => '创建时间',
            'user_id' => '用户ID',
            'token' => 'Token',
            'is_use' => '可否使用',
        ];
    }
}
