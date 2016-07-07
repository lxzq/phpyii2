<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:27
 */

namespace app\models;


use yii\base\Model;

class CourseClassForm extends Model
{
    public $id;
    public $course_id;
    public $shop_id;
    public $teacher_id;
    public $title;
    public $start_time;
    public $end_time;
    public $class_time;
    public $kaike_time;
    public $max_nums;
    public $min_nums;
    public $status;
    public $age;
    public $material_price;
    public $proA;
    public $proB;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['$course_id','$shop_id'], 'required'],
            [['$course_id', '$shop_id', 'teacher_id', '$max_nums', '$min_nums'], 'integer'],
            [['$title'], 'string', 'max' => 100],
        ];
    }
}