<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_dis_cr".
 *
 * @property integer $id
 * @property integer $dis_act_id
 * @property integer $course_id
 * @property integer $course_num
 * @property double $price_one
 * @property double $price_two
 */
class CourseDisCr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_dis_cr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dis_act_id', 'course_id', 'course_num', 'price_two'], 'required'],
            [['dis_act_id', 'course_id', 'course_num'], 'integer'],
            [['price_one', 'price_two'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dis_act_id' => '优惠课程活动ID',
            'course_id' => '优惠课程',
            'course_num' => '课时',
            'price_one' => '原价',
            'price_two' => '优惠价',
        ];
    }

    public function getCourse(){
        return $this->hasOne(CourseInfo::className(),["id"=>"course_id"]);
    }
}
