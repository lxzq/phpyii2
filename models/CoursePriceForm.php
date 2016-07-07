<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:27
 */

namespace app\models;


use yii\base\Model;

class CoursePriceForm extends Model
{
    public $id;
    public $class_id;
    public $course_nums;
    public $week_nums;
    public $org_price;
    public $discount_price;
    public $is_delete;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['$course_id','$class_id'], 'required'],
            [['$course_id', '$class_id'], 'integer'],
            [['$title'], 'string', 'max' => 100],
        ];
    }
}