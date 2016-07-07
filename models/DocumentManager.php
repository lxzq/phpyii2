<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_manager".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $add_time
 * @property string $path
 * @property string $notes
 */
class DocumentManager extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_manager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['add_time'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['path', 'notes'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '文件上传人',
            'name' => '文档名称',
            'add_time' => '上传时间',
            'path' => '文件路径',
            'notes' => '文件说明',
        ];
    }

    public function getUser(){
        return $this->hasOne(UserInfo::className(),['id'=>'user_id']);
    }
}
