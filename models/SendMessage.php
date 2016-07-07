<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "send_message".
 *
 * @property string $id
 * @property string $bind_keyword
 * @property string $preview_openids
 * @property integer $group_id
 * @property integer $type
 * @property string $media_id
 * @property integer $send_type
 * @property string $send_openids
 * @property string $msg_id
 * @property string $content
 * @property string $msgtype
 * @property string $token
 * @property integer $appmsg_id
 * @property integer $voice_id
 * @property integer $video_id
 * @property integer $CreateTime
 */
class SendMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'send_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['preview_openids', 'send_openids', 'content'], 'string'],
            [['group_id', 'type', 'send_type', 'appmsg_id', 'voice_id', 'video_id', 'CreateTime'], 'integer'],
            [['bind_keyword'], 'string', 'max' => 50],
            [['media_id', 'token'], 'string', 'max' => 100],
            [['msg_id'], 'string', 'max' => 255],
            [['msgtype'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'bind_keyword' => '关联关键词',
            'preview_openids' => '预览人OPENID',
            'group_id' => '群发对象',
            'type' => '素材来源',
            'media_id' => '微信素材ID',
            'send_type' => '发送方式',
            'send_openids' => '要发送的OpenID',
            'msg_id' => 'msg_id',
            'content' => '文本消息内容',
            'msgtype' => '消息类型',
            'token' => 'token',
            'appmsg_id' => '图文id',
            'voice_id' => '语音id',
            'video_id' => '视频id',
            'CreateTime' => '群发时间',
        ];
    }
}
