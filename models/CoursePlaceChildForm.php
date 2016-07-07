<?php
/**
 * Created by PhpStorm.
 * User: WWW
 * Date: 2015-12-09
 * Time: 16:27
 */

namespace app\models;


use yii\base\Model;

class CoursePlaceChildForm extends Model
{
    public $id;
    public $course_place_id;
    public $child_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }
}