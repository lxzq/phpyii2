<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-03-02
 * Time: 14:18
 */

namespace app\models;


use yii\base\Model;

class CourseViewForm extends Model
{

    public $id;
    public $shopId;
    public $courseName;
    public $courseRoom;
    public $courseClass;
    public $courseDate;
    public $startTime;
    public $endTime;
    public $startM;
    public $endM;

    public $courseTeacher;
    public $notes;
}