<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_discount_option".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $option
 * @property string $option_name
 * @property string $option_val
 */
class CourseDiscountOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_discount_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'option', 'option_val'], 'required'],
            [['type', 'option'], 'integer'],
            [['option_name', 'option_val'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '1 原价优惠  2 折扣价优惠',
            'option' => '优惠选项',
            'option_name' => '选项名字',
            'option_val' => '选项值',
        ];
    }
}
