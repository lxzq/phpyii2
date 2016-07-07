<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "child_class".
 *
 * @property integer $id
 * @property integer $child_id
 * @property integer $class_id
 * @property integer $price_id
 * @property integer $course_id
 * @property string $add_date
 * @property string $yy
 * @property double $price
 * @property integer $pay_type
 * @property string $class_ap
 * @property string $class_gw
 * @property string $notes
 * @property integer $couser_order_id
 * @property integer $record_id
 * @property integer $is_delete
 * @property integer $course_num
 */
class ChildClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'child_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['child_id', 'class_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'child_id' => 'Child ID',
            'class_id' => 'Class ID',
            'price_id' => 'price_id',
            'add_date' => 'add_date',
            'yy' => 'yy',
            'price' => 'price',
            'pay_type' => 'pay_type',
            'course_id' => 'course_id',
            'class_ap' => 'class_ap',
            'class_gw' => 'class_gw',
            'notes' => 'notes',
            'couser_order_id' => 'couser_order_id',
            'record_id'=>'申报课程流水ID',
            'is_delete'=>'is_delete',
            'course_num'=>'课程课次',
            'is_delete'=>'is_delete'
        ];
    }

    public function getClass()
    {
        return $this->hasMany(CourseClass::className(), ['id' => 'class_id']);
    }

    public function getChild()
    {
        return $this->hasOne(ChildInfo::className(), ['id' => 'child_id']);
    }

    public function getPrices()
    {
        return $this->hasMany(CoursePrice::className(), ["id" => "price_id"]);
    }

    public function getMoney()
    {
        return $this->hasOne(CoursePrice::className(), ["id" => "price_id"]);
    }
    public function getCourse(){
        return $this->hasOne(CourseInfo::className(),['id'=>'course_id']);
    }
}

