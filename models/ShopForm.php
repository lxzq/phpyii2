<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/10
 * Time: 9:17
 */

namespace app\models;


use yii\base\Model;

class ShopForm extends Model
{

    public $id;
    public $name;
    public $logo;
    public $notes;
    public $address;
    public $psword;

    public function rules()
    {
        return [
            [[ 'name'], 'required','message'=>'{attribute}不能为空！']
        ];
    }
}