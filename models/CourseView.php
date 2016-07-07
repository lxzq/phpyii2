<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_view".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $course_name
 * @property integer $course_room
 * @property integer $course_class
 * @property string $course_date
 * @property string $notes
 * @property integer $course_teacher
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $start_m
 * @property integer $end_m
 * @property string $live_start_date
 * @property string $live_end_date
 * @property string $createtime
 */
class CourseView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_view';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id'], 'required'],
            [['shop_id', 'course_room', 'course_class','course_teacher'], 'integer'],
            [['course_name', 'course_date','notes'], 'string', 'max' => 100]
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
            'course_name' => '上课名称',
            'course_room' => '上课教室',
            'course_class' => '上课班级',
            'course_date' => '上课时间',
             'notes' => '备注',
            'course_teacher' => '上课老师',
            'start_time'=>'开始小时',
            'end_time'=>'结束小时',
            'start_m'=>'开始分钟',
            'end_m'=>'结束分钟',
            'live_start_date'=>'直播开始时间',
            'live_end_date'=>'直播截止时间',
            'createtime'=>'创建时间'
        ];
    }

    public function getShop(){
        return $this->hasOne(ShopInfo::className(),["id"=>"shop_id"]);
    }

    public function getRoom(){
        return $this->hasOne(CourseRoom::className(),['id'=>'course_room']);
    }

    public function getClass(){
        return $this->hasOne(CoursePlaceClass::className(),['id'=>'course_class']);
    }

    public function getTeacher(){
        return $this->hasOne(TeacherInfo::className(),["id"=>'course_teacher']);
    }
}
