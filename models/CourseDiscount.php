<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_discount".
 *
 * @property integer $id
 * @property string $discount_describe
 * @property string $discount_image
 * @property integer $discount_pattern
 * @property string $start_time
 * @property string $end_time
 * @property double $discount_condition
 * @property double $discount_value
 */
class CourseDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discount_describe'], 'string'],
            [['discount_pattern'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['discount_condition', 'discount_value'], 'number'],
            [['discount_image'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'discount_describe' => 'Discount Describe',
            'discount_image' => 'Discount Image',
            'discount_pattern' => 'Discount Pattern',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'discount_condition' => 'Discount Condition',
            'discount_value' => 'Discount Value',
        ];
    }
}
