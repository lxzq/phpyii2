<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "material_file".
 *
 * @property string $id
 * @property integer $file_id
 * @property string $cover_url
 * @property string $media_id
 * @property string $wechat_url
 * @property integer $add_time
 * @property integer $user_id
 * @property string $token
 * @property string $title
 * @property integer $type
 * @property string $introduction
 * @property integer $is_use
 */
class MaterialFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'material_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'file_id', 'add_time', 'user_id', 'type', 'is_use'], 'integer'],
            [['introduction'], 'string'],
            [['cover_url', 'wechat_url'], 'string', 'max' => 255],
            [['media_id', 'token', 'title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'file_id' => '上传文件',
            'cover_url' => '本地URL',
            'media_id' => '微信端图文消息素材的media_id',
            'wechat_url' => '微信端的文件地址',
            'add_time' => '创建时间',
            'user_id' => '用户ID',
            'token' => 'Token',
            'title' => '素材名称',
            'type' => '类型1语音2视频',
            'introduction' => '描述',
            'is_use' => '可否使用',
        ];
    }
}
