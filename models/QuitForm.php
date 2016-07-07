<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-20
 * Time: 15:21
 */

namespace app\models;


use yii\base\Model;

class QuitForm extends Model
{

    public $recordId;
    public $payType;
    public $payMoney;
    public $payTime;
    public $notes;
    public $receiptId;

    public function rules()
    {
        return [['payMoney','required','message'=>'金额必须填写.'],
            ['notes','required','message'=>'退课原因必须填写.'],
            ['payType','required','message'=>'必须填写.'],
            ['receiptId','required','message'=>'收据编号必须填写.'],
        ];
    }

}