<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_push_trigger".
 *
 * @property integer $id
 * @property string $trigger_name
 * @property string $trigger_rule
 * @property string $source
 */
class PushTrigger extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_push_trigger';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trigger_rule'], 'string'],
            [['trigger_name', 'source'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trigger_name' => 'Trigger Name',
            'trigger_rule' => 'Trigger Rule',
            'source' => 'Source',
        ];
    }
    /**
     * 关联资源表
     */
    public function getSources()
    {
        return $this->hasOne(Source::className(),['SourceID'=>'source_id']);
    }
}

