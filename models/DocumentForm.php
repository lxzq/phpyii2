<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-05-05
 * Time: 9:33
 */

namespace app\models;


use yii\base\Model;

class DocumentForm extends Model
{
    public $userId;
    public $name;
    public $addTime;
    public $path;
    public $notes;

}