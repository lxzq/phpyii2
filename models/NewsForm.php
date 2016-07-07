<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-07
 * Time: 14:11
 */

namespace app\models;


use yii\base\Model;

class NewsForm extends Model
{

    public $id;
    public $title;
    public $addTime;
    public $image;
    public $content;
    public $details;
    public $userId;

}