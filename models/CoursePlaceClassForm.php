<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:27
 */

namespace app\models;


use yii\base\Model;

class CoursePlaceClassForm extends Model
{
    public $id;
    public $shop_id;
    public $course_id;
    public $name;
    public $notes;
    public $teacher;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required','message'=>'班级名字不能空'],
            [['notes'], 'string', 'max' => 50,'message'=>'备注不能超过50字'],
        ];
    }
}