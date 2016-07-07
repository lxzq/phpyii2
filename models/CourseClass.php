<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_class".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $shop_id
 * @property integer $teacher_id
 * @property string $title
 * @property string $start_time
 * @property string $end_time
 * @property string $class_time
 * @property string $kaike_time
 * @property integer $max_nums
 * @property integer $min_nums
 * @property integer $status
 * @property string $age
 * @property string $material_price
 * @property string $pro_a
 * @property string $pro_b
 */
class CourseClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'shop_id'], 'required'],
            [['id', 'course_id', 'shop_id', 'teacher_id', 'max_nums', 'min_nums', 'status'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['age'], 'string', 'max' => 100],
            [['class_time','kaike_time'], 'string', 'max' => 200]
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
            'shop_id' => 'Shop ID',
            'teacher_id' => 'Teacher ID',
            'title' => 'Title',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'class_time' => 'Class Time',
            'max_nums' => 'Max Nums',
            'min_nums' => 'Min Nums',
            'status' => 'Status',
            'age' => 'age',
            'pro_a'=>'分成甲方',
            'pro_b'=>'分成乙方'
        ];
    }

    public function getCourse()
    {
        return $this->hasOne(CourseInfo::className(), ['id' => 'course_id']);
    }
    public function getTeacher()
    {
        return $this->hasOne(TeacherInfo::className(), ['id' => 'teacher_id']);
    }
    public function getShop()
    {
        return $this->hasOne(ShopInfo::className(), ['id' => 'course_id']);
    }

    public function getPrice(){
        return $this->hasMany(CoursePrice::className(),["class_id"=>'id']);
    }
}
