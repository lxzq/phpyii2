<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-02-22
 * Time: 13:22
 */

namespace app\models;


use yii\base\Model;

class ChildClassForm extends Model
{

    public $classList;
    public $addDate;
    public $price;
    public $payType;
    public $yy;
    public $classAp;
    public $classGw;
    public $notes;
    public $receiptId;
    public $moneyType;


    public function rules()
    {
        return [
            [[ 'classList'], 'required','message'=>'{attribute}不能为空！']
        ];
    }
}