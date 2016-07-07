<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-03-01
 * Time: 11:01
 */

namespace app\models;


use yii\base\Model;

class WeixinMoneyRecordForm extends Model
{

    public $userId;
    public $childId;
    public $money;//实收金额
    public $type;//1 充值 2 优惠
    public $shouldMoney;//应收金额
    public $notes;//备注


    public function rules()
    {
        return [
            // username and password are both required

        ];
    }

}