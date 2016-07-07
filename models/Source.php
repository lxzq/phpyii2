<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_source".
 *
 * @property integer $SourceID
 * @property string $SourceName
 * @property string $DataSource
 * @property integer $SourceParentID
 * @property integer $SourceType
 */
class Source extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_source';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SourceParentID', 'SourceType'], 'integer'],
            [['SourceName', 'DataSource'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SourceID' => 'Source ID',
            'SourceName' => 'Source Name',
            'DataSource' => 'Data Source',
            'SourceParentID' => 'Source Parent ID',
            'SourceType' => 'Source Type',
        ];
    }
    /**
     * 关联资源表
     */
    /*public function getTriggers()
    {
        return $this->hasMany(PushTrigger::className(),['source_id'=>'SourceID']);
    }*/
}
