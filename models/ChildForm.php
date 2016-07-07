<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-02-22
 * Time: 13:22
 */

namespace app\models;


use yii\base\Model;

class ChildForm extends Model
{

    public $id;
    public $userName;
    public $childName;
    public $childSex;
    public $phone;
    public $birthday;
    public $shopId;
    public $card;
    public $address;
    public $school;
    public $class;
    public $work;
    public $secondName;
    public $secondPhone;
    public $secondWork;

    public function rules()
    {
        return [['userName','required','message'=>'爸爸必须填写.'],
                 ['phone','required','message'=>'手机号必须填写.'],
                 ['childName','required','message'=>'宝贝姓名必须填写.'],
               ];
    }
}