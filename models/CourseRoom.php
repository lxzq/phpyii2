<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_room".
 *
 * @property integer $id
 * @property string $name
 * @property integer $shop_id
 * @property string $code
 * @property string $video_code
 */
class CourseRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id'], 'integer'],
            [['name', 'code','video_code'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '教室名称',
            'shop_id' => '店铺ID',
            'code' => '门禁编号',
            'video_code'=>'直播设备号'
        ];
    }
}
