<?php
/**
 * Created by PhpStorm.
 * User: made
 * Date: 2016年5月10日
 * Time: 16:36:04
 */

namespace app\models;


use yii\base\Model;

class CourseManagerPriceForm extends Model
{
    public $id;
    public $course_id;
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
            [['$course_id'], 'required'],
            [['$course_id'], 'integer'],
            [['$title'], 'string', 'max' => 100],
        ];
    }
}