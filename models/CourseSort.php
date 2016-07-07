<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_sort".
 *
 * @property integer $id
 * @property string $course_title
 * @property integer $course_sort
 */
class CourseSort extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_sort';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_sort'], 'integer'],
            [['course_title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_title' => 'Course Title',
            'course_sort' => 'Course Sort',
        ];
    }
}
