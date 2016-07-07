<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-04-19
 * Time: 17:23
 */

namespace app\models;


use yii\base\Model;

class OtherMoneyRecordForm extends Model
{

    public $id;
    public $shopId;
    public $payName;
    public $addDate;
    public $payType;
    public $payMoney;
    public $receiptId;
    public $notes;

}