<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_teacher".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $teacher_id
 */
class CourseTeacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_teacher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'teacher_id'], 'required'],
            [['course_id', 'teacher_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'teacher_id' => 'Teacher ID',
        ];
    }
}
