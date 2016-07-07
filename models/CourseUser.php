<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_user".
 *
 * @property integer $id
 * @property string $phone
 * @property string $bbname
 */
class CourseUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'bbname'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'bbname' => 'Bbname',
        ];
    }
}
