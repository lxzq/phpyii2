<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_push_task".
 *
 * @property integer $id
 * @property string $touser
 * @property integer $master
 * @property integer $masterValue
 * @property string $send_content
 * @property string $send_time
 * @property string $send_type
 * @property integer $status
 */
class PushTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_push_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['master', 'masterValue', 'status'], 'integer'],
            [['send_content'], 'string'],
            [['send_time'], 'safe'],
            [['touser', 'send_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'touser' => 'Touser',
            'master' => '推送任务类型1:go,2:php',
            'masterValue' => '推送任务类型值',
            'send_content' => 'Send Content',
            'send_time' => 'Send Time',
            'send_type' => '0：微信推送；1：app推送',
            'status' => '0:未发送；1：已发送；',
        ];
    }
}
