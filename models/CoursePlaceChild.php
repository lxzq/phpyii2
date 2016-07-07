<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_place_child".
 *
 * @property integer $id
 * @property integer $course_place_id
 * @property integer $child_id
 * @property string $add_time
 * @property integer $status
 */
class CoursePlaceChild extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_place_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_place_id', 'child_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_place_id' => 'Course Place ID',
            'child_id' => 'Child ID',
            'add_time'=>'添加时间',
            'status'=>'0未分成 1待分成'
        ];
    }
}
