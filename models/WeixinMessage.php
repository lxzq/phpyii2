<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "weixin_message".
 *
 * @property string $id
 * @property string $ToUserName
 * @property string $FromUserName
 * @property integer $CreateTime
 * @property string $MsgType
 * @property string $MsgId
 * @property string $Content
 * @property string $PicUrl
 * @property string $MediaId
 * @property string $Format
 * @property string $ThumbMediaId
 * @property string $Title
 * @property string $Description
 * @property string $Url
 * @property integer $collect
 * @property integer $deal
 * @property integer $is_read
 * @property integer $type
 * @property integer $is_material
 */
class WeixinMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weixin_message';
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'ToUserName' => 'Token',
            'FromUserName' => 'OpenID',
            'CreateTime' => '创建时间',
            'MsgType' => '消息类型',
            'MsgId' => '消息ID',
            'Content' => '文本消息内容',
            'PicUrl' => '图片链接',
            'MediaId' => '多媒体文件ID',
            'Format' => '语音格式',
            'ThumbMediaId' => '缩略图的媒体id',
            'Title' => '消息标题',
            'Description' => '消息描述',
            'Url' => 'Url',
            'collect' => '收藏状态',
            'deal' => '处理状态',
            'is_read' => '是否已读',
            'type' => '消息分类',
            'is_material' => '设置为文本素材',
        ];
    }
}
