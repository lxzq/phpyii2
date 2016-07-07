<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-05-31
 * Time: 11:17
 */

namespace app\models;


use yii\base\Model;

class ChildClassRecordModel extends Model
{

    public $recordId;
    public $money;
    public $payType;
    public $receiptId;

    public function rules()
    {
        return [['money','required','message'=>'金额必须填写.'],
            ['receiptId','required','message'=>'收据编号必须填写.'],
            ['payType','required','message'=>'必须填写.'],
        ];
    }
}