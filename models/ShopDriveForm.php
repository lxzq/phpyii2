<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/14
 * Time: 14:54
 */

namespace app\models;


use yii\base\Model;

class ShopDriveForm extends Model
{

    public $id;
    public $shopId;
    public $name;
    public $code;

    public function rules()
    {
        return [
            [[ 'name'], 'required','message'=>'{attribute}不能为空！']
        ];
    }
}