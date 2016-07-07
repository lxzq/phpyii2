<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:27
 */

namespace app\models;


use yii\base\Model;

class CourseDiscountForm extends Model
{
    public $id;
    public $discount_describe;
    public $discount_image;
    public $discount_pattern;
    public $start_time;
    public $end_time;
    public $discount_condition;
    public $discount_value;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['$discount_pattern'], 'required'],
            [['$discount_pattern'], 'integer'],
            [['$discount_describe'], 'string', 'max' => 100],
        ];
    }
}