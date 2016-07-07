<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_price".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $course_nums
 * @property string $week_nums
 * @property double $org_price
 * @property double $discount_price
 * @property integer $is_delete
 */
class CourseManagerPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_manager_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'course_nums', 'week_nums'], 'required'],
            [['course_id', 'course_nums', 'week_nums', 'is_delete'], 'integer'],
            [['org_price', 'discount_price'], 'number']
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
            'course_nums' => 'Course Nums',
            'week_nums' => 'Week Nums',
            'org_price' => 'Org Price',
            'discount_price' => 'Discount Price',
            'is_delete' => 'Is Delete',
        ];
    }

    public function getClass(){
        return $this->hasOne(CourseInfo::className(),['id'=>'course_id']);
    }
}
