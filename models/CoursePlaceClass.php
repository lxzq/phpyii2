<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_place_class".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $course_id
 * @property string $name
 * @property string $notes
 * @property integer $teacher_id
 */
class CoursePlaceClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_place_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'course_id'], 'integer'],
            [['name', 'notes'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'course_id' => 'Class ID',
            'name' => 'Name',
            'notes' => 'Notes',
            'teacher_id'=>'teacher_id'
        ];
    }

    public function getCourse(){
        return $this->hasOne(CourseInfo::className(),['id'=>'course_id']);
    }

    public function getShop(){
        return $this->hasOne(ShopInfo::className(),['id'=>'shop_id']);
    }

    public function getNum(){
        return $this->hasMany(CoursePlaceChild::className(),['course_place_id'=>'id']);
    }
    public function getTeacher(){
        return $this->hasOne(TeacherInfo::className(),['id'=>'teacher_id']);
    }
}

