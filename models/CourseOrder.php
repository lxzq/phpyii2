<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_order".
 *
 * @property integer $id
 * @property integer $class_id
 * @property integer $price_id
 * @property integer $course_id
 * @property double $totalprice
 * @property string $creatTime
 * @property string $paymentTime
 * @property integer $status
 * @property integer $userid
 * @property string $orderno
 */
class CourseOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_id', 'price_id', 'course_id', 'status', 'userid'], 'integer'],
            [['totalprice'], 'number'],
            [['creatTime', 'paymentTime'], 'safe'],
            [['orderno'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'price_id' => 'Price ID',
            'course_id' => 'Course ID',
            'totalprice' => 'Totalprice',
            'creatTime' => 'Creat Time',
            'paymentTime' => 'Payment Time',
            'status' => 'Status',
            'userid' => 'Userid',
            'orderno' => 'Orderno',
        ];
    }

    public function getClass()
    {
        return $this->hasOne(CourseClass::className(), ['id' => 'class_id']);
    }

    public function getCourse()
    {
        return $this->hasOne(CourseInfo::className(), ['id' => 'course_id']);
    }

    public function getPrice()
    {
        return $this->hasOne(CoursePrice::className(), ['id' => 'price_id']);
    }

    public function getUser()
    {
        return $this->hasOne(CourseUser::className(), ['id' => 'userid']);
    }
}
