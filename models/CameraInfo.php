<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "camera_info".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $image
 * @property string $notes
 * @property string $code
 * @property string $uri
 * @property integer $type
 * @property integer $sort_num
 * @property integer $lb
 * @property integer $status
 */
class CameraInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'camera_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'name'], 'required'],
            [['project_id', 'type','sort_num'], 'integer'],
            [['name', 'code'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 512],
            [['notes', 'uri'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'name' => 'Name',
            'image' => 'Image',
            'notes' => 'Notes',
            'code' => 'Code',
            'uri' => 'Uri',
            'type' => 'Type',
            'sort_num' => 'Sort Num',
            'lb'=>'lb'
        ];
    }

    /**
     * @return mixed查询最大的排序序号
     */
    public static function maxSort(){
      return  self::find()->max("sort_num");
    }
}
